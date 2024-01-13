<?php

namespace bencarr\fathom\helpers;

use Craft;
use DateTime;
use yii\base\InvalidArgumentException;

class WidgetDateRange
{
    public function __construct(
        public string   $label,
        public DateTime $start,
        public DateTime $end,
        /** @var string[ */
        public string $interval = FathomDateGrouping::DAY,
    ) {
        $allowed_intervals = FathomDateGrouping::all();
        if (!in_array($this->interval, array_values($allowed_intervals))) {
            throw new InvalidArgumentException(Craft::t('fathom', "Value `{provided_value}` is not a valid date interval. Allowed values are {allowed_values}. For convenience, you can use constants defined on the `{helper_class}` class", [
                'provided_value' => $this->interval,
                'allowed_values' => collect($allowed_intervals)->values()->map(fn($value) => "`$value`")->join(', ', ', and '),
                'helper_class' => FathomDateGrouping::class,
            ]));
        }
    }
}
