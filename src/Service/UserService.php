<?php
namespace App\Service;

use App\Entity\User;
use App\Exception\InvalidActionException;
use Doctrine\ORM\EntityManagerInterface;

final class UserService
{
    public const ACTION_ENABLE  = 'enable';
    public const ACTION_DISABLE = 'disable';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function toggleStatus(string $action, User $user): string | InvalidActionException
    {
        match ($action) {
            self::ACTION_ENABLE => $user->setStatus(true),
            self::ACTION_DISABLE => $user->setStatus(false),
            default => throw new InvalidActionException($action),
        };

        $this->entityManager->flush();

        return $action === self::ACTION_ENABLE
        ? 'Utilisateur activé.'
        : 'Utilisateur désactivé.';
    }
}
