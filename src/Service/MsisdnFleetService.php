<?php
namespace App\Service;

use App\Entity\MsisdnFleet;
use App\Helper\PeriodValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\AccessDeniedHttpException;
use App\Exception\InvalidMsisdnFleetActionException;

final class MsisdnFleetService
{
    public const ACTION_ENABLE  = 'enable';
    public const ACTION_DISABLE = 'disable';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private PeriodValidator $validator
    ) {}

    public function toggleStatus(string $action, MsisdnFleet $msisdnFleet): string | InvalidMsisdnFleetActionException
    {
        if (! $this->validator->isWithinAllowedPeriod()) {
            throw new AccessDeniedHttpException('Ajout/Modification non autorisée en dehors du 1 au 25.');
        }

        match ($action) {
            self::ACTION_ENABLE => $msisdnFleet->setStatus(true),
            self::ACTION_DISABLE => $msisdnFleet->setStatus(false),
            default => throw new InvalidMsisdnFleetActionException($action),
        };

        $this->entityManager->flush();

        return $action === self::ACTION_ENABLE
        ? 'Msisdn flotte activée.'
        : 'Msisdn flotte désactivée.';
    }
}
