<?php
namespace App\Service;

use App\Entity\MsisdnFleet;
use App\Exception\InvalidCompanyActionException;
use Doctrine\ORM\EntityManagerInterface;

final class MsisdnFleetService
{
    public const ACTION_ENABLE  = 'enable';
    public const ACTION_DISABLE = 'disable';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function toggleStatus(string $action, MsisdnFleet $msisdnFleet): string | InvalidCompanyActionException
    {
        match ($action) {
            self::ACTION_ENABLE => $msisdnFleet->setStatus(true),
            self::ACTION_DISABLE => $msisdnFleet->setStatus(false),
            default => throw new InvalidCompanyActionException($action),
        };

        $this->entityManager->flush();

        return $action === self::ACTION_ENABLE
        ? 'Msisdn flotte activée.'
        : 'Msisdn flotte désactivée.';
    }
}
