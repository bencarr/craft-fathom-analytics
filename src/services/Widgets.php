<?php

namespace bencarr\fathom\services;

use bencarr\fathom\events\RegisterWidgetRangesEvent;
use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\Color;
use bencarr\fathom\helpers\FathomDateGrouping;
use bencarr\fathom\helpers\WidgetDateRange;
use craft\helpers\DateTimeHelper;
use DateInterval;
use yii\base\Component;

class Widgets extends Component
{
    public function getRanges(): array
    {
        $event = new RegisterWidgetRangesEvent([
            'ranges' => [
                'today' => new WidgetDateRange(
                    label: 'Last 24 Hours',
                    start: DateTimeHelper::now()->sub(new DateInterval('PT24H')),
                    end: DateTimeHelper::now(),
                    interval: FathomDateGrouping::HOUR,
                ),
                'last_7_days' => new WidgetDateRange(
                    label: 'Last 7 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P7D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_14_days' => new WidgetDateRange(
                    label: 'Last 14 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P14D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_30_days' => new WidgetDateRange(
                    label: 'Last 30 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P30D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_60_days' => new WidgetDateRange(
                    label: 'Last 60 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P60D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_90_days' => new WidgetDateRange(
                    label: 'Last 90 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P90D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_180_days' => new WidgetDateRange(
                    label: 'Last 180 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P180D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_365_days' => new WidgetDateRange(
                    label: 'Last 365 Days',
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P1Y')),
                    end: DateTimeHelper::tomorrow(),
                ),
            ],
        ]);
        $this->trigger(FathomPlugin::EVENT_DEFINE_WIDGET_RANGES, $event);

        return $event->ranges;
    }

    public function getRange(string $key)
    {
        $ranges = $this->getRanges();
        return $ranges[$key];
    }

    public function getColorForBrowser(string $browser, float $alpha = 1.0)
    {
        return Color::forBrowser($browser)->atAlpha($alpha);
    }

    public function getColorForIndex(int $index, float $alpha = 1.0)
    {
        return Color::index($index)->atAlpha($alpha);
    }
}
