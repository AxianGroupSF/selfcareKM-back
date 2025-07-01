<?php
namespace App\Service;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\InvalidCompanyActionException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CompanyService
{
    public const ACTION_ENABLE = 'enable';
    public const ACTION_DISABLE = 'disable';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function toggleStatus(string $action, Company $company): string|InvalidCompanyActionException
    {
        match ($action) {
            self::ACTION_ENABLE => $company->setStatus(true),
            self::ACTION_DISABLE => $company->setStatus(false),
            default => throw new InvalidCompanyActionException($action),
        };

        $this->entityManager->flush();

        return $action === self::ACTION_ENABLE
            ? 'Société activée.'
            : 'Société désactivée.';
    }
}
