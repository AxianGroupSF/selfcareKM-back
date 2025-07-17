<?php
namespace App\Helper;

class PeriodValidator
{
    public function isWithinAllowedPeriod(): bool
    {
        $today = (new \DateTimeImmutable('now'))->format('j'); // jour du mois (1-31)
        return $today >= 1 && $today <= 25;
    }
}
