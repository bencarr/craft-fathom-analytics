<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\Color;
use Craft;

class Browsers extends BaseWidget
{
    public string $range = 'last_30_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Browsers');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getBrowsers($range, $this->range);
        $response = collect($response)
            ->filter(fn($row) => Color::forBrowser($row['browser']));

        return $this->renderChart('browsers', [
            'labels' => collect($response)->pluck('browser')->toArray(),
            'datasets' => [
                [
                    'label' => Craft::t('fathom', 'Pageviews'),
                    'backgroundColor' => collect($response)
                        ->map(fn($row) => Color::forBrowser($row['browser'])->atAlpha(0.5))
                        ->toArray(),
                    'data' => collect($response)->pluck('pageviews')->toArray(),
                ],
                [
                    'label' => Craft::t('fathom', 'Visitors'),
                    'backgroundColor' => collect($response)
                        ->map(fn($row) => Color::forBrowser($row['browser'])->atAlpha(0.5))
                        ->toArray(),
                    'data' => collect($response)->pluck('visits')->toArray(),
                ],
            ],
        ], [
            'response' => $response,
            'colors' => collect($response)->pluck('browser', 'browser')->map(fn($browser) => Color::forBrowser($browser)),
        ]);
    }
}
