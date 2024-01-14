<?php

namespace bencarr\fathom\web\assets\widgetresources;

use bencarr\fathom\web\assets\chartjs\ChartJsAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class WidgetResourcesAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';
    public $depends = [
        CpAsset::class,
        ChartJsAsset::class,
    ];
    public $js = [
        'widget-resources.js',
    ];
    public $css = [
        'widget-resources.css',
    ];
}
