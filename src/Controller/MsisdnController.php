<?php
namespace App\Controller;

use App\Entity\MsisdnFleet;
use App\Service\MsisdnFleetService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MsisdnController extends AbstractController
{
    public function __construct(private MsisdnFleetService $msisdnFleetService)
    {

    }

    public function toggleStatus(string $action, MsisdnFleet $msisdnFleet): JsonResponse
    {
        $message = $this->msisdnFleetService->toggleStatus($action, $msisdnFleet);

        return new JsonResponse(['message' => $message]);
    }
}