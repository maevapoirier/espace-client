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
            new TwigFilter('french', [$this, 'translateInFrench']),
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

    public function translateInFrench(string $status): string
    {
        if ($status == "overdue") {
            return "A régler";
        }
        if ($status == "paid") {
            return "Acquitée";
        }
    }
}
