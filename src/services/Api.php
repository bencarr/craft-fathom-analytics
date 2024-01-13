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
        return $this->cache('site', 60 * 60 * 24, function() {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'sites/' . $siteId)->json();
        });
    }

    public function getCurrentVisitors()
    {
        return $this->cache('current_visitors', 1, function() {
            $siteId = App::parseEnv(FathomPlugin::getInstance()->getSettings()->siteId);
            return $this->request('GET', 'current_visitors', [
                'query' => [
                    'site_id' => $siteId,
                    'detailed' => 'true',
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
