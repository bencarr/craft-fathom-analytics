<?php

namespace bencarr\fathom\services;

use bencarr\fathom\events\RegisterChartJsSettingsEvent;
use bencarr\fathom\events\RegisterWidgetRangesEvent;
use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\Color;
use bencarr\fathom\helpers\FathomDateGrouping;
use bencarr\fathom\helpers\WidgetDateRange;
use Craft;
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
                    label: Craft::t('fathom', 'Last {num,number} Hours', ['num' => 24]),
                    start: DateTimeHelper::now()->sub(new DateInterval('PT24H')),
                    end: DateTimeHelper::now(),
                    interval: FathomDateGrouping::HOUR,
                ),
                'last_7_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 7]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P7D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_14_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 14]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P14D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_30_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 30]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P30D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_60_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 60]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P60D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_90_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 90]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P90D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_180_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 180]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P180D')),
                    end: DateTimeHelper::tomorrow(),
                ),
                'last_365_days' => new WidgetDateRange(
                    label: Craft::t('fathom', 'Last {num,number} Days', ['num' => 365]),
                    start: DateTimeHelper::tomorrow()->sub(new DateInterval('P365D')),
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

    public function getChartJsSettings()
    {
        $event = new RegisterChartJsSettingsEvent([
            'settings' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => [
                    'intersect' => false,
                    'mode' => 'index',
                ],
                'borderWidth' => 2,
                'datasets' => [
                    'line' => [
                        'fill' => true,
                        'tension' => 0.2,
                        'pointBackgroundColor' => 'rgba(0 0 0 / 0)',
                        'pointBorderWidth' => 0,
                        'hoverBackgroundColor' => 'rgba(31 95 234 / 0.7)',
                        'hoverBorderColor' => 'rgba(31 95 234 / 0.7)',
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'display' => false,
                    ],
                    'tooltip' => [
                        'boxPadding' => 4,
                        'displayColors' => false,
                        'padding' => [
                            'left' => 12,
                            'right' => 12,
                            'top' => 8,
                            'bottom' => 8,
                        ],
                        'titleFont' => [
                            'size' => 14,
                        ],
                        'bodyFont' => [
                            'size' => 14,
                        ],
                    ],
                ],
            ],
        ]);

        $this->trigger(FathomPlugin::EVENT_REGISTER_CHART_JS_SETTINGS, $event);

        return $event->settings;
    }
}
