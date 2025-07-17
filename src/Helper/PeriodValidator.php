<?php
namespace App\Helper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PeriodValidator
{
    public function __construct(private ParameterBagInterface $params)
    {}

    public function isWithinAllowedPeriod(): bool
    {
        if (! filter_var($this->params->get('activate_verif_period'), FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        $today = (new \DateTimeImmutable('now'))->format('j'); // jour du mois (1-31)
        return $today >= 1 && $today <= 25;
    }
}
