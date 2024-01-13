<?php

namespace bencarr\fathom\web\assets\chartjs;

use craft\web\AssetBundle;

class ChartJsAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $js = [
        'chartjs-4.4.1.umd.min.js',
    ];
}
