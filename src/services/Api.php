<?php

namespace bencarr\fathom\services;

use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\ApiResponse;
use Craft;
use craft\helpers\App;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\base\Component;

class Api extends Component
{
    public Client $client;
    public const BASE_URI = 'https://api.usefathom.com/v1/';

    public function init(): void
    {
        parent::init();

        if (!isset($this->client)) {
            $apiKey = App::parseEnv(FathomPlugin::getInstance()->getSettings()->apiKey);
            $this->client = Craft::createGuzzleClient([
                'base_uri' => self::BASE_URI,
                'cookies' => true,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', $apiKey),
                ],
            ]);
        }
    }

    public function getSite()
    {
        $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
        $response = $this->request('GET', 'sites/' . $siteId);

        if (!$response->isOk()) {
            return false;
        }

        return $response->json();
    }

    public function getCurrentVisitors()
    {
        return $this->cache('current_visitors', 60, function() {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'current_visitors', [
                'query' => [
                    'site_id' => $siteId,
                    'detailed' => 'true',
                ],
            ])->json();
        });
    }

    public function getTopPages(string $key)
    {
        $range = FathomPlugin::getInstance()->widgets->getRange($key);
        return $this->cache("top_pages.$key", 60 * 60, function() use ($range) {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'aggregations', [
                'query' => [
                    'entity' => 'pageview',
                    'entity_id' => $siteId,
                    'aggregates' => 'visits,pageviews',
                    'sort_by' => 'pageviews:desc',
                    'field_grouping' => 'pathname',
                    'timezone' => Craft::$app->getTimeZone(),
                    'date_from' => $range->start->format('Y-m-d'),
                    'date_to' => $range->end->format('Y-m-d'),
                    'limit' => 10,
                ],
            ])->json();
        });
    }

    public function getVisitorsChart(string $range, ?string $uri = null)
    {
        $widgetRange = FathomPlugin::getInstance()->widgets->getRange($range);

        return $this->cache("visitors_chart.$range", 60 * 60, fn() => $this
            ->getAggregation(
                params: [
                    'aggregates' => 'visits,pageviews',
                    'date_grouping' => $widgetRange->interval,
                    'sort_by' => 'timestamp:asc',
                ],
                range: $range,
                uri: $uri,
            )->json());
    }

    public function getBrowsers(string $range, ?string $uri = null)
    {
        return $this->cache("browsers.$range", 60 * 60, fn() => $this
            ->getAggregation(
                params: [
                    'aggregates' => 'visits,pageviews',
                    'sort_by' => 'pageviews:desc',
                    'field_grouping' => 'browser',
                ],
                range: $range,
                uri: $uri,
            )->json());
    }

    public function getDeviceTypes(string $range, ?string $uri = null)
    {
        return $this->cache("device_types.$range", 60 * 60, fn() => $this
            ->getAggregation(
                params: [
                    'aggregates' => 'visits,pageviews',
                    'sort_by' => 'pageviews:desc',
                    'field_grouping' => 'device_type',
                ],
                range: $range,
                uri: $uri,
            )
            ->json());
    }

    public function getTopReferrers(string $range, ?string $uri = null)
    {
        return $this->cache("top_referrers.$range", 60 * 60, fn() => $this
            ->getAggregation(
                params: [
                    'aggregates' => 'visits,pageviews',
                    'limit' => 10,
                    'field_grouping' => 'referrer_hostname',
                    'sort_by' => 'pageviews:desc',
                ],
                range: $range,
                uri: $uri,
            )
            ->json());
    }

    public function getOverview(string $range, ?string $uri = null)
    {
        return $this->cache("overview.$range", 60 * 60, fn() => $this
            ->getAggregation(
                params: ['aggregates' => 'visits,pageviews,avg_duration,bounce_rate'],
                range: $range,
                uri: $uri,
            )
            ->collect()
            ->first());
    }

    protected function getAggregation(array $params = [], ?string $range = null, ?string $uri = null): ApiResponse
    {
        $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
        $query = [
            ...$params,
            'entity' => 'pageview',
            'entity_id' => $siteId,
            'timezone' => Craft::$app->getTimeZone(),
        ];

        if ($range) {
            $widgetRange = FathomPlugin::getInstance()->widgets->getRange($range);
            $query['date_from'] = $widgetRange->start->format('Y-m-d');
            $query['date_to'] = $widgetRange->end->format('Y-m-d');
        }

        if ($uri) {
            $query['filters'] = json_encode([
                ['property' => 'pathname', 'operator' => 'is', 'value' => $uri],
            ]);
        }

        return $this->request('GET', 'aggregations', [
            'query' => $query,
        ]);
    }

    public function cache(string $key, int $duration, callable $setter)
    {
        return Craft::$app->getCache()?->getOrSet($key, $setter, $duration);
    }

    public function request(string $method, string $uri, array $options = []): ApiResponse
    {
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return new ApiResponse($response);
    }
}
