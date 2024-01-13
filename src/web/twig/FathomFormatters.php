<?php

namespace bencarr\fathom\web\twig;

use craft\helpers\StringHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extension
 */
class FathomFormatters extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('fathom_duration', function(float $avg_duration) {
                $avg_duration = round($avg_duration);
                $hours = floor($avg_duration / (60 * 60));
                $minutes = floor(($avg_duration / 60) % 60);
                $seconds = $avg_duration % 60;

                return preg_replace('/^00:/', '', sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds));
            }),
            new TwigFilter('fathom_number', function(int $number, int $abbreviation_threshold = 1_000) {
                [$value, $suffix] = match (true) {
                    $number >= 1_000_000_000_000 => [round($number / 1_000_000_000_000, 2), 'T'],
                    $number >= 1_000_000_000 => [round($number / 1_000_000_000, 2), 'B'],
                    $number >= 1_000_000 => [round($number / 1_000_000, 2), 'M'],
                    $number >= $abbreviation_threshold => [round($number / $abbreviation_threshold, 2), 'K'],
                    default => [number_format($number), null],
                };
                // If the whole number is 3 digits, we can do with a little less precision
                if (str_contains((string) $value, '.') && strlen(StringHelper::beforeFirst((string) $value, '.')) === 3) {
                    $value = round($value, 1);
                }

                return preg_replace('/\.0+?$/', '', $value) . $suffix;
            }),
        ];
    }
}
