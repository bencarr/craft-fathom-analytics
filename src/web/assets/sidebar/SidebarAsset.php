<?php

namespace bencarr\fathom\web\assets\sidebar;

use bencarr\fathom\web\assets\chartjs\ChartJsAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use craft\web\assets\htmx\HtmxAsset;

class SidebarAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $depends = [
        CpAsset::class,
        ChartJsAsset::class,
        HtmxAsset::class,
    ];
    public $css = [
        'sidebar.css',
    ];
    public $js = [
        'sidebar.js',
    ];
}
