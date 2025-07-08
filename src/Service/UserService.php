<?php
namespace App\Service;

use App\Entity\User;
use App\Exception\HttpNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Ldap\Ldap;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\InvalidActionException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class UserService
{
    public const ACTION_ENABLE  = 'enable';
    public const ACTION_DISABLE = 'disable';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private Ldap $ldap,
        private ParameterBagInterface $parameterBag,
        private LoggerInterface $logger,
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

    public function checkIsldapUser($identifier): void
    {
        $this->ldap->bind($this->parameterBag->get('ldap_search_dn'), $this->parameterBag->get('ldap_search_password'));
        $this->logger->info('<info>Connection au serveur ldap en success !</info>');

        $query   = $this->ldap->query($this->parameterBag->get('ldap_base_dn'), '(sAMAccountName=' . $identifier . ')');
        $results = $query->execute();
        if ($results->count() <= 0) {
            throw new HttpNotFoundException('Utilisateur introuvable depuis LDAP !');
        }
    }
}
