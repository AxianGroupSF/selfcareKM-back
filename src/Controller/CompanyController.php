<?php
namespace App\Controller;

use App\Entity\Company;
use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyController extends AbstractController
{
    public function __construct(private CompanyService $companyService)
    {

    }

    public function toggleStatus(string $action, Company $company): JsonResponse
    {
        $message = $this->companyService->toggleStatus($action, $company);

        return new JsonResponse(['message' => $message]);
    }
}
