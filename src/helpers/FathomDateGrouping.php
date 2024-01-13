<?php

namespace bencarr\fathom\helpers;

use ReflectionClass;

class FathomDateGrouping
{
    public const HOUR = 'hour';
    public const DAY = 'day';
    public const MONTH = 'month';
    public const YEAR = 'year';

    public static function all(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
