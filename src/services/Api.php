<?php

namespace bencarr\fathom\services;

use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\ApiResponse;
use bencarr\fathom\helpers\FathomDateGrouping;
use bencarr\fathom\helpers\WidgetDateRange;
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
        return $this->cache('site', 60 * 60 * 24, function() {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'sites/' . $siteId)->json();
        });
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

    public function getVisitorsChart(WidgetDateRange $range, string $key)
    {
        $cache_duration = match ($range->interval) {
            FathomDateGrouping::HOUR => 60 * 60,
            default => 60 * 60 * 5,
        };
        return $this->cache("visitors_chart.$key", $cache_duration, function() use ($range) {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'aggregations', [
                'query' => [
                    'entity' => 'pageview',
                    'entity_id' => $siteId,
                    'aggregates' => 'visits,pageviews',
                    'timezone' => Craft::$app->getTimeZone(),
                    'date_from' => $range->start->format('Y-m-d'),
                    'date_to' => $range->end->format('Y-m-d'),
                    'date_grouping' => $range->interval,
                    'sort_by' => 'timestamp:asc',
                ],
            ])->json();
        });
    }

    public function getBrowsers(WidgetDateRange $range, string $key)
    {
        return $this->cache("browsers.$key", 1, function() use ($range) {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'aggregations', [
                'query' => [
                    'entity' => 'pageview',
                    'entity_id' => $siteId,
                    'aggregates' => 'visits,pageviews',
                    'sort_by' => 'pageviews:desc',
                    'field_grouping' => 'browser',
                    'timezone' => Craft::$app->getTimeZone(),
                    'date_from' => $range->start->format('Y-m-d'),
                    'date_to' => $range->end->format('Y-m-d'),
                ],
            ])->json();
        });
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
            Craft::dd([$e->getCode(), $e->getMessage(), $e->getRequest(), $e->getResponse()]);
            $response = $e->getResponse();
        }

        return new ApiResponse($response);
    }
}
