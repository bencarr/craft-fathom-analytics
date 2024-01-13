<?php

namespace bencarr\fathom;

use bencarr\fathom\models\Settings;
use bencarr\fathom\services\Api;
use bencarr\fathom\widgets\CurrentVisitors;
use Craft;
use craft\base\Model;
use craft\base\Plugin;
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
 */
class FathomPlugin extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => ['api' => Api::class],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            // ...
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        $site = null;
        if ($this->getSettings()->siteId && $this->getSettings()->apiKey) {
            $site = $this->api->getSite();
        }
        return Craft::$app->view->renderTemplate('fathom/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
            'site' => $site,
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = CurrentVisitors::class;
        });
    }
}
