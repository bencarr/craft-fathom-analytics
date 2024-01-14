<?php

namespace bencarr\fathom\widgets;

use Craft;

class TopPages extends BaseWidget
{
    public string $range = 'last_30_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Top Pages');
    }
}
