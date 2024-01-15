<?php

namespace bencarr\fathom\web\assets\sidebar;

use bencarr\fathom\web\assets\chartjs\ChartJsAsset;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class SidebarAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $depends = [
        CpAsset::class,
        ChartJsAsset::class,
    ];
    public $css = [
        'sidebar.css',
    ];
}
