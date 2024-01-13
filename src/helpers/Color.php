<?php

namespace bencarr\fathom\helpers;

use Illuminate\Support\Collection;

class Color
{
    // Browsers
    public const SAFARI = 'rgb(1 187 235 / <alpha>)';
    public const FIREFOX = 'rgb(255 128 19 / <alpha>)';
    public const EDGE = 'rgb(5 82 155 / <alpha>)';
    public const CHROME = 'rgb(31 150 66 / <alpha>)';
    public const OPERA = 'rgb(255 27 45 / <alpha>)';
    // Default
    public const DEFAULT = 'rgba(31 95 234 / <alpha>)';
    // Palette
    public const RED = 'rgb(225 45 57 / <alpha>)';
    public const AMBER = 'rgb(245 158 11 / <alpha>)';
    public const LIME = 'rgb(132 204 22 / <alpha>)';
    public const EMERALD = 'rgb(16 185 129 / <alpha>)';
    public const CYAN = 'rgb(44 177 188 / <alpha>)';
    public const BLUE = 'rgb(43 176 237 / <alpha>)';
    public const VIOLET = 'rgb(139 92 246 / <alpha>)';
    public const FUCHSIA = 'rgb(217 70 239 / <alpha>)';
    public const ROSE = 'rgb(244 63 94 / <alpha>)';
    public const ORANGE = 'rgb(249 115 22 / <alpha>)';
    public const YELLOW = 'rgb(240 180 41 / <alpha>)';
    public const GREEN = 'rgb(34 197 94 / <alpha>)';
    public const TEAL = 'rgb(39 171 131 / <alpha>)';
    public const SKY = 'rgb(14 165 233 / <alpha>)';
    public const INDIGO = 'rgb(99 102 241 / <alpha>)';
    public const PURPLE = 'rgb(168 85 247 / <alpha>)';
    public const PINK = 'rgb(218 18 125 / <alpha>)';

    public function __construct(public string $color, public float $alpha = 1.0)
    {
    }

    public function atAlpha(float $alpha): string
    {
        $this->alpha = $alpha;

        return (string) $this;
    }

    public static function palette(): Collection
    {
        return collect([
            self::RED,
            self::AMBER,
            self::LIME,
            self::EMERALD,
            self::CYAN,
            self::BLUE,
            self::VIOLET,
            self::FUCHSIA,
            self::ROSE,
            self::ORANGE,
            self::YELLOW,
            self::GREEN,
            self::TEAL,
            self::SKY,
            self::INDIGO,
            self::PURPLE,
            self::PINK,
        ])->mapInto(self::class);
    }

    public static function index(int $index)
    {
        $palette = self::palette();
        $i = $index % $palette->count();

        return $palette[$i];
    }

    public static function forBrowser(string $browser): self
    {
        return match ($browser) {
            'Safari' => new self(self::SAFARI),
            'Firefox', 'Mozilla' => new self(self::FIREFOX),
            'Edge' => new self(self::EDGE),
            'Chrome' => new self(self::CHROME),
            'Opera' => new self(self::OPERA),
            default => new self(self::DEFAULT),
        };
    }

    public function __toString(): string
    {
        return str_replace('<alpha>', $this->alpha, $this->color);
    }
}
