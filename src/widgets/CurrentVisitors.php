<?php

namespace bencarr\fathom\widgets;

use Craft;

class CurrentVisitors extends BaseWidget
{
    public static function displayName(): string
    {
        return Craft::t('fathom', 'Current Visitors');
    }
}
