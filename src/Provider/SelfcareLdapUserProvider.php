<?php
namespace App\Security;

use App\Constante\Constants;
use App\Constante\SelfcareConst;
use App\Entity\User;
use App\Exception\GeneralException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class SelfcareLdapUserProvider implements UserProviderInterface
{
    final public function __construct(
        private Ldap $ldap,
        private ParameterBagInterface $parameterBag,
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
        private UserRepository $userRepository,
        private LoggerInterface $logger,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function refreshUser(UserInterface $user): UserInterface
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $user->getUserIdentifier()]);

        if (! $user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return true;
    }

    /**
     * @param string $identifier
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     * @throws \App\Exception\UserAccountDisabledException
     * @throws \Throwable
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            $user = $this->userRepository->findOneBy(['username' => $identifier, 'isEnabled' => 1]);
            if (! $user instanceof User) {
                throw new NotFoundHttpException(SelfcareConst::INVALID_CREDENTIALS);
            }
            $request  = $this->requestStack->getCurrentRequest();

            if ($user->isLdapUser() && $request->getContent()) {
                $data = json_decode($request->getContent(), true);
                if (is_array($data) && isset($data['password'])) {
                    return $this->authenticateLdapUser($identifier, $data['password'], $user);
                }
            }

            // Sinon, on vérifie son mot de passe stocké en base
            return $user;
        } catch (\Exception $e) {
            $this->logger->warning($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }

    private function authenticateLdapUser(string $identifier, ?string $password, User $user): User
    {
        if (! $password) {
            throw new NotFoundHttpException('Mot de passe requis pour les utilisateurs LDAP.');
        }

        // Connexion au serveur LDAP
        $this->ldap->bind($this->parameterBag->get('ldap_search_dn'), $this->parameterBag->get('ldap_search_password'));
        $this->logger->info('<info>Connexion au serveur LDAP réussie.</info>');

        $query   = $this->ldap->query($this->parameterBag->get('ldap_base_dn'), '(sAMAccountName=' . $identifier . ')');
        $results = $query->execute();
        if ($results->count() <= 0) {
            throw new NotFoundHttpException(SelfcareConst::INVALID_CREDENTIALS);
        }

        // Vérification du mot de passe via LDAP
        $dn = $results[0]->getDn();
        $this->ldap->bind($dn, $password);
        $this->logger->info('<info>Utilisateur LDAP authentifié avec succès.</info>');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
