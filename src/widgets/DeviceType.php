<?php

namespace bencarr\fathom\widgets;

use Craft;

class DeviceType extends BaseWidget
{
    public string $range = 'last_30_days';

    public static function displayName(): string
    {
        return Craft::t('fathom', 'Device Type');
    }
}
