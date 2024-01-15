<?php

namespace bencarr\fathom\web\assets\widgets;

use bencarr\fathom\web\assets\chartjs\ChartJsAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class WidgetsAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';
    public $depends = [
        CpAsset::class,
        ChartJsAsset::class,
    ];
    public $js = [
        'widgets.js',
    ];
    public $css = [
        'widgets.css',
    ];
}
