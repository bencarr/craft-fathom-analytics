<?php

namespace bencarr\fathom\web\assets\widgetresources;

use bencarr\fathom\web\assets\chartjs\ChartJsAsset;
use craft\web\AssetBundle;

class WidgetResourcesAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';
    public $depends = [
        ChartJsAsset::class,
    ];
    public $js = [
        'widget-resources.js',
    ];
    public $css = [
        'widget-resources.css',
    ];
}
