<?php

namespace bencarr\fathom\events;

use craft\base\Event;

class RegisterChartJsSettingsEvent extends Event
{
    public array $settings = [];
}
