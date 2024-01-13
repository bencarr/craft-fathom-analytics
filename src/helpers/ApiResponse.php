<?php

namespace bencarr\fathom\helpers;

use Craft;
use craft\helpers\Json;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function isOk(): bool
    {
        return $this->response->getStatusCode() >= 200
            && $this->response->getStatusCode() < 300;
    }

    public function json()
    {
        return Json::decode($this->body());
    }

    public function collect($key = null): Collection
    {
        $collection = collect($this->json());
        if ($key) {
            return collect($collection->get($key));
        }

        return $collection;
    }

    public function body(): string
    {
        return (string) $this->response->getBody();
    }

    public function dd(): void
    {
        Craft::dd($this->json());
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
