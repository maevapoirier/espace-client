<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('percent', [$this, 'percent']),
            new TwigFilter('hour', [$this, 'convertInHour']),
        ];
    }

    public function percent(float $number, float $number2): float
    {
        return $number * 100 / $number2;
    }

    public function convertInHour(float $number): float
    {
        return $number * 8;
    }
}
