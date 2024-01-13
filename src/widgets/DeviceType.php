<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\Color;
use Craft;

class DeviceType extends BaseWidget
{
    public string $range = 'last_90_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Device Type');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getDeviceTypes($range, $this->range);

        return $this->renderChart('device-type', [
            'labels' => collect($response)->pluck('device_type')->toArray(),
            'datasets' => [
                [
                    'label' => Craft::t('fathom', 'Pageviews'),
                    'backgroundColor' => collect($response)->map(fn($row, $i) => Color::index($i)->atAlpha(0.5))->toArray(),
                    'data' => collect($response)->pluck('pageviews')->toArray(),
                ],
                [
                    'label' => Craft::t('fathom', 'Visitors'),
                    'backgroundColor' => collect($response)->map(fn($row, $i) => Color::index($i)->atAlpha(0.5))->toArray(),
                    'data' => collect($response)->pluck('visits')->toArray(),
                ],
            ],
        ], [
            'response' => $response,
            'colors' => collect($response)->mapWithKeys(fn($row, $i) => [$row['device_type'] => Color::index($i)->atAlpha(1.0) ]),
        ]);
    }
}
