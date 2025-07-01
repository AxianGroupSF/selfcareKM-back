<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends AbstractController
{
    public function statusToggle(string $action, Company $company)
    {
        dd($action, $company);
    }
}