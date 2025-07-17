<?php
namespace App\EventListener;

use App\Entity\MsisdnFleet;
use App\Exception\AccessDeniedHttpException;
use App\Helper\PeriodValidator;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MsisdnFleetDateListener
{
    public function __construct(private PeriodValidator $validator)
    {}

    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->checkDate($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->checkDate($args);
    }

    private function checkDate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (! $entity instanceof MsisdnFleet) {
            return;
        }

        if (! $this->validator->isWithinAllowedPeriod()) {
            throw new AccessDeniedHttpException('Ajout/Modification non autorisée en dehors du 1 au 25.');
        }
    }
}
