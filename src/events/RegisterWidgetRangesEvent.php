<?php

namespace bencarr\fathom\events;

use bencarr\fathom\helpers\WidgetDateRange;
use craft\base\Event;

class RegisterWidgetRangesEvent extends Event
{
    /** @var WidgetDateRange[]  */
    public array $ranges = [];
}
