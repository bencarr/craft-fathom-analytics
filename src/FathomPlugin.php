<?php

namespace bencarr\fathom;

use bencarr\fathom\models\Settings;
use bencarr\fathom\services\Api;
use bencarr\fathom\services\Widgets;
use bencarr\fathom\web\assets\sidebar\SidebarAsset;
use bencarr\fathom\web\twig\FathomExtension;
use bencarr\fathom\widgets\Browsers;
use bencarr\fathom\widgets\CurrentVisitors;
use bencarr\fathom\widgets\DeviceType;
use bencarr\fathom\widgets\Overview;
use bencarr\fathom\widgets\TopPages;
use bencarr\fathom\widgets\TopReferrers;
use bencarr\fathom\widgets\VisitorsChart;
use Craft;
use craft\base\Element;
use craft\base\Model;
use craft\base\Plugin;
use craft\elements\Entry;
use craft\events\DefineHtmlEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Dashboard;
use yii\base\Event;

/**
 * Fathom Analytics plugin
 *
 * @method static FathomPlugin getInstance()
 * @method Settings getSettings()
 * @author Ben Carr
 * @copyright Ben Carr
 * @license MIT
 * @property-read Api $api
 * @property-read Widgets $widgets
 */
class FathomPlugin extends Plugin
{
    public const EVENT_DEFINE_WIDGET_RANGES = 'defineWidgetRanges';
    public const EVENT_REGISTER_CHART_JS_SETTINGS = 'registerChartJsSettings';

    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                'api' => Api::class,
                'widgets' => Widgets::class,
            ],
        ];
    }

    public function init(): void
    {
        parent::init();
        Craft::setAlias('@fathom', __DIR__);

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            // ...
        });
        Craft::$app->view->registerTwigExtension(new FathomExtension());
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        $overrides = Craft::$app->getConfig()->getConfigFromFile($this->handle);

        return Craft::$app->view->renderTemplate('fathom/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
            'overrides' => $overrides,
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = CurrentVisitors::class;
            $event->types[] = VisitorsChart::class;
            $event->types[] = Browsers::class;
            $event->types[] = DeviceType::class;
            $event->types[] = TopPages::class;
            $event->types[] = TopReferrers::class;
            $event->types[] = Overview::class;
        });

        Event::on(Entry::class, Element::EVENT_DEFINE_SIDEBAR_HTML, function(DefineHtmlEvent $event) {
            if ($event->sender->uri) {
                Craft::$app->getView()->registerAssetBundle(SidebarAsset::class);
                $event->html .= Craft::$app->getView()->renderTemplate('fathom/sidebar.twig', [
                    'entry' => $event->sender,
                ]);
            }
        });
    }
}
