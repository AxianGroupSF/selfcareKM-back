<?php
namespace App\EventListener;

use App\Entity\MsisdnFleet;
use App\Helper\PeriodValidator;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MsisdnFleetDateListener
{
    public function __construct(private PeriodValidator $validator)
    {}

    public function prePersist(MsisdnFleet $msisdnFleet, LifecycleEventArgs $args): void
    {
        if (! $this->validator->isWithinAllowedPeriod()) {
            throw new AccessDeniedHttpException('Ajout non autorisé en dehors du 1 au 25.');
        }
    }

    public function preUpdate(MsisdnFleet $msisdnFleet, LifecycleEventArgs $args): void
    {
        if (! $this->validator->isWithinAllowedPeriod()) {
            throw new AccessDeniedHttpException('Modification non autorisée en dehors du 1 au 25.');
        }
    }
}
