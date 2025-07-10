<?php
namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use App\Constante\SelfcareConst;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class CompanyAssignmentDto
{
    #[Assert\All([
            new Assert\Type("int"),
        ])]
    #[Groups([SelfcareConst::USER_COMPANY_WRITE])]
    #[ApiProperty(example: [1, 2, 3])]
    public array $companyIds = [];
}
