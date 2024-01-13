<?php

namespace bencarr\fathom\web\twig;

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
            new TwigFilter('fathom_number', function(int $number) {
                [$value, $suffix] = match (true) {
                    $number >= 1_000_000_000_000 => [round($number / 1_000_000_000_000, 1), 'T'],
                    $number >= 1_000_000_000 => [round($number / 1_000_000_000, 1), 'B'],
                    $number >= 1_000_000 => [round($number / 1_000_000, 1), 'M'],
                    $number >= 10_000 => [round($number / 1_000, 1), 'K'],
                    default => [number_format($number), null],
                };

                return preg_replace('/\.0+?$/', '', $value) . $suffix;
            }),
        ];
    }
}
