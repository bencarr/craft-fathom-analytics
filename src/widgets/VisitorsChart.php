<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\FathomDateGrouping;
use Craft;
use DateTime;

class VisitorsChart extends BaseWidget
{
    public string $range = 'last_7_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Visitors Chart');
    }

    public function getBodyHtml(): ?string
    {
        $range = $this->getRanges()[$this->range];
        $response = FathomPlugin::getInstance()->api->getVisitorsChart($range, $this->range);

        return $this->renderChart('visitors-chart', [
            'labels' => collect($response)->map(fn($row) => (new DateTime($row['date']))->format($range->interval === FathomDateGrouping::HOUR ? 'ga' : 'M j')),
            'datasets' => [
                [
                    'label' => Craft::t('fathom', 'Pageviews'),
                    'backgroundColor' => 'rgba(31 95 234 / 0.1)',
                    'borderColor' => 'rgba(31 95 234 / 0.2)',
                    'data' => collect($response)->pluck('pageviews')->toArray(),
                ],
                [
                    'label' => Craft::t('fathom', 'Visitors'),
                    'backgroundColor' => 'rgba(31 95 234 / 0.4)',
                    'borderColor' => 'rgba(31 95 234 / 1)',
                    'data' => collect($response)->pluck('visits')->toArray(),
                ],
            ],
        ]);
    }
}
