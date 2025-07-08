<?php
namespace App\Manager;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Right;
use App\Dto\UserInputDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createUser(UserInputDto $dto): User
    {
        $user = new User();
        $user->setLogin($dto->login)
                ->setEmail($dto->email)
                ->setPhone($dto->phone)
                ->setStatus($dto->status)
                ->setLdapUser($dto->isLdapUser ?? false)
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt(new \DateTimeImmutable());

        if (!$user->isLdapUser()) {
            if (!$dto->password) {
                throw new \InvalidArgumentException('Un mot de passe est requis pour les utilisateurs non LDAP.');
            }
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $dto->password)
            );
        }

        foreach ($dto->roles as $roleData) {
            $role = $this->em->getRepository(Role::class)->find($roleData['id']);
            if (!$role) {
                throw new \InvalidArgumentException("Ce rôle n'existe pas.");
            }

            // Ajouter les rights au rôle
            foreach ($roleData['rights'] as $rightId) {
                $right = $this->em->getRepository(Right::class)->find($rightId);
                if (!$right) {
                    throw new \InvalidArgumentException("Right inexistant.");
                }
                $role->addRight($right);
            }

            $user->addUserRole($role);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
