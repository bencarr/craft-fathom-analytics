<?php

namespace bencarr\fathom\web\assets\widgetresources;

use craft\web\AssetBundle;
use craft\web\assets\htmx\HtmxAsset;

class WidgetResourcesAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';
    public $depends = [
        HtmxAsset::class,
    ];
    public $js = [
        'widget-resources.js',
    ];
    public $css = [
        'widget-resources.css',
    ];
}
