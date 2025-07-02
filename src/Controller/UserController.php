<?php
namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService)
    {

    }

    public function toggleStatus(string $action, User $user): JsonResponse
    {
        $message = $this->userService->toggleStatus($action, $user);

        return new JsonResponse(['message' => $message]);
    }
}