<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\UserInputDto;
use App\Dto\UserInputUpdateDto;
use App\Entity\User;
use App\Manager\UserManager;

final class UserPostProcessor implements ProcessorInterface
{
    public function __construct(private UserManager $userManager)
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if ($data instanceof UserInputDto || $data instanceof UserInputUpdateDto) {
            /** @var ?User $existingUser
             */
            $existingUser = $context['previous_data'] ?? null;

            if ($existingUser) {
                /**
                 * @var UserInputUpdateDto $data
                 */
                return $this->userManager->updateUser($existingUser, $data);
            }

            return $this->userManager->createUser($data);
        } else {
            throw new \InvalidArgumentException('Invalid data class');
        }
    }
}
