<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\UserInputDto;
use App\Entity\User;
use App\Manager\UserManager;

final class UserPostProcessor implements ProcessorInterface
{
    public function __construct(private UserManager $userManager)
    {}

    /**
     * @param UserInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        return $this->userManager->createUser($data);
    }
}
