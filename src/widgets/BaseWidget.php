<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\events\RegisterChartJsSettingsEvent;
use bencarr\fathom\events\RegisterWidgetRangesEvent;
use bencarr\fathom\FathomPlugin;
use bencarr\fathom\helpers\FathomDateGrouping;
use bencarr\fathom\helpers\WidgetDateRange;
use bencarr\fathom\web\assets\widgetresources\WidgetResourcesAsset;
use Craft;
use craft\base\Widget;
use craft\helpers\DateTimeHelper;
use craft\helpers\StringHelper;
use DateInterval;

class BaseWidget extends Widget
{
    public function getTitle(): ?string
    {
        return null;
    }

    public static function isSelectable(): bool
    {
        return true;
    }

    public static function icon(): ?string
    {
        return null;
    }

    public function renderWidget(string $path, array $data = []): ?string
    {
        Craft::$app->getView()->registerAssetBundle(WidgetResourcesAsset::class);

        return Craft::$app->getView()->renderTemplate("fathom/widgets/$path", [
            'widget' => $this,
            'slug' => StringHelper::toKebabCase($this->displayName()),
            'hasRange' => isset($this->range),
            'timestamp' => DateTimeHelper::now(),
            'ranges' => $this->getRanges(),
            ...$data,
        ]);
    }

    public function renderChart(string $path, array $chart_data = [], array $twig_data = [])
    {
        $chart_id = "widget{$this->id}-chart";
        Craft::$app->getView()->registerJsVar('chartId', $chart_id);
        Craft::$app->getView()->registerJsVar('chartData', $chart_data);
        Craft::$app->getView()->registerJsVar('chartSettings', $this->getChartJsSettings());

        return $this->renderWidget($path, [
            ...$twig_data,
            'chartId' => $chart_id,
        ]);
    }

    protected function getRanges()
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

    protected function getChartJsSettings()
    {
        $event = new RegisterChartJsSettingsEvent([
            'settings' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => [
                    'intersect' => false,
                    'mode' => 'index',
                ],
                // 'backgroundColor' => 'rgba(31 95 234 / 0.2)',
                // 'borderColor' => 'rgba(31 95 234 / 0.5)',
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
