<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use Craft;

class Browsers extends BaseWidget
{
    public string $range = 'last_90_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Browsers');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getBrowsers($range, $this->range);
        $colors = [
            'Edge' => 'rgb(5 82 155 / <alpha>)',
            'Opera' => 'rgb(255 27 45 / <alpha>)',
            'Firefox' => 'rgb(255 128 19 / <alpha>)',
            'Mozilla' => 'rgb(255 128 19 / <alpha>)',
            'Safari' => 'rgb(1 187 235 / <alpha>)',
            'Chrome' => 'rgb(31 150 66 / <alpha>)',
        ];
        $response = collect($response)
            ->filter(fn($row) => array_key_exists($row['browser'], $colors));

        return $this->renderChart('browsers', [
            'labels' => collect($response)->pluck('browser')->toArray(),
            'datasets' => [
                [
                    'label' => Craft::t('fathom', 'Pageviews'),
                    'backgroundColor' => collect($response)
                        ->map(fn($row) => $colors[$row['browser']] ?? 'rgba(31 95 234 / <alpha>)')
                        ->map(fn($color) => str_replace('<alpha>', '0.5', $color))
                        ->toArray(),
                    'borderColor' => collect($response)
                        ->map(fn($row) => $colors[$row['browser']] ?? 'rgba(31 95 234 / <alpha>)')
                        ->map(fn($color) => str_replace('<alpha>', '0.8', $color))
                        ->toArray(),
                    'data' => collect($response)->pluck('pageviews')->toArray(),
                ],
                [
                    'label' => Craft::t('fathom', 'Visitors'),
                    'backgroundColor' => collect($response)
                        ->map(fn($row) => $colors[$row['browser']] ?? 'rgba(31 95 234 / <alpha>)')
                        ->map(fn($color) => str_replace('<alpha>', '0.5', $color))
                        ->toArray(),
                    'borderColor' => collect($response)
                        ->map(fn($row) => $colors[$row['browser']] ?? 'rgba(31 95 234 / <alpha>)')
                        ->map(fn($color) => str_replace('<alpha>', '0.8', $color))
                        ->toArray(),
                    'data' => collect($response)->pluck('visits')->toArray(),
                ],
            ],
        ], [
            'response' => $response,
            'colors' => $colors,
        ]);
    }
}
