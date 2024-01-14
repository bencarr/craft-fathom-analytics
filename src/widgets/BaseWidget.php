<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\events\RegisterChartJsSettingsEvent;
use bencarr\fathom\FathomPlugin;
use bencarr\fathom\web\assets\widgetresources\WidgetResourcesAsset;
use Craft;
use craft\base\Widget;
use craft\helpers\StringHelper;
use putyourlightson\sprig\Sprig;

class BaseWidget extends Widget
{
    public array $data = [];

    public function getTitle(): ?string
    {
        return null;
    }

    public function getSlug(): string
    {
        $class = StringHelper::afterLast(static::class, '\\');

        return StringHelper::toKebabCase($class);
    }

    public static function isSelectable(): bool
    {
        return true;
    }

    public static function icon(): ?string
    {
        return null;
    }

    public function getBodyHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(WidgetResourcesAsset::class);
        Sprig::bootstrap();

        return Craft::$app->getView()->renderTemplate("fathom/widget.twig", [
            'widget' => $this,
        ]);
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

    public function toSprig(): array
    {
        return [
            'displayName' => $this->displayName(),
            'slug' => $this->getSlug(),
            'id' => $this->id,
            'colspan' => $this->colspan,
            'range' => $this->range ?? null,
            'data' => $this->data,
            'chartSettings' => $this->getChartJsSettings(),
        ];
    }
}
