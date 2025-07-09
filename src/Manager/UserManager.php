<?php
namespace App\Manager;

use App\Dto\UserInputDto;
use App\Dto\UserInputUpdateDto;
use App\Entity\Right;
use App\Entity\Role;
use App\Entity\User;
use App\Exception\BadRequestException;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator,
        private UserService $userService
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

        if (! $user->isLdapUser()) {
            if (! $dto->password) {
                throw new BadRequestException('Un mot de passe est requis pour les utilisateurs non LDAP.');
            }
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $dto->password)
            );
        } else {
            $this->userService->checkIsldapUser($dto->login);
            $user->setPassword(null);
        }

        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            $messages = [];
            foreach ($violations as $violation) {
                $messages[] = $violation->getMessage(); // juste le message
            }

            throw new BadRequestException(implode(' | ', $messages));
        }

        foreach ($dto->roles as $roleData) {
            $role = $this->em->getRepository(Role::class)->find($roleData['id']);
            if (! $role) {
                throw new BadRequestException("Ce rôle n'existe pas.");
            }

            // Ajouter les rights au rôle
            foreach ($roleData['rights'] as $rightId) {
                $right = $this->em->getRepository(Right::class)->find($rightId);
                if (! $right) {
                    throw new BadRequestException("Right inexistant.");
                }
                $role->addRight($right);
            }

            $user->addUserRole($role);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function updateUser(User $user, UserInputUpdateDto $dto): User
    {
        $user->setLogin($dto->login)
            ->setEmail($dto->email)
            ->setPhone($dto->phone)
            ->setStatus($dto->status);

        if (! $user->isLdapUser() && ! empty($dto->password)) {
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $dto->password)
            );
        }
        if ($user->isLdapUser()) {
            $this->userService->checkIsldapUser($dto->login);
        }

        // Validation
        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            $messages = [];
            foreach ($violations as $violation) {
                $messages[] = $violation->getMessage();
            }
            throw new BadRequestException(implode(' | ', $messages));
        }

        // Mise à jour des rôles/rights
        $user->getUserRole()->clear();
        foreach ($dto->roles as $roleData) {
            $role = $this->em->getRepository(Role::class)->find($roleData['id']);
            if (! $role) {
                throw new BadRequestException("Ce rôle n'existe pas.");
            }

            foreach ($roleData['rights'] as $rightId) {
                $right = $this->em->getRepository(Right::class)->find($rightId);
                if (! $right) {
                    throw new BadRequestException("Right inexistant.");
                }
                $role->addRight($right);
            }

            $user->addUserRole($role);
        }

        $this->em->flush();

        return $user;
    }
}
