<?php

namespace bencarr\fathom\widgets;

use bencarr\fathom\web\assets\widgetresources\WidgetResourcesAsset;
use Craft;
use craft\base\Widget;
use craft\helpers\DateTimeHelper;

class CurrentVisitors extends Widget
{
    public static function displayName(): string
    {
        return Craft::t('fathom', 'Current Visitors');
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

        // $response = FathomPlugin::getInstance()->api->getCurrentVisitors();
        $response = [
            "total" => 144,
            "content" => [
                [
                    "pathname" => "/invitational",
                    "hostname" => "https://tallwoodband.com",
                    "total" => 100,
                ],
                [
                    "pathname" => "/blog/being-a-wabbit",
                    "hostname" => "https://tallwoodband.com",
                    "total" => 44,
                ], ],
            "referrers" => [
                [
                    "referrer_hostname" => "https://usefathom.com",
                    "referrer_pathname" => "/why-we-love-bugs-bunny",
                    "total" => 32,
                ],
                [
                    "referrer_hostname" => "http://daffyduck.com",
                    "referrer_pathname" => "/blog/i-am-sick-of-his-antics",
                    "total" => 33,
                ],
                [
                    "referrer_hostname" => "https://usefathom.com",
                    "referrer_pathname" => "/elmer-fudd",
                    "total" => 4,
                ],
            ],
        ];

        return Craft::$app->getView()->renderTemplate('fathom/widgets/current-visitors', [
            'widget' => $this,
            'timestamp' => DateTimeHelper::now(),
            'response' => $response,
        ]);
    }
}
