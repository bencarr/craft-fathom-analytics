<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\FathomPlugin;
use bencarr\fathom\web\assets\widgets\WidgetsAsset;
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
        return '@fathom/widget-icon.svg';
    }

    public function getBodyHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(WidgetsAsset::class);
        Sprig::bootstrap();

        return Craft::$app->getView()->renderTemplate("fathom/widget.twig", [
            'widget' => $this,
        ]);
    }

    public function toSprig(): array
    {
        return [
            'displayName' => static::displayName(),
            'slug' => $this->getSlug(),
            'id' => $this->id,
            'colspan' => $this->colspan,
            'range' => $this->range ?? null,
            'data' => $this->data,
            'chartSettings' => FathomPlugin::getInstance()->widgets->getChartJsSettings(),
        ];
    }
}
