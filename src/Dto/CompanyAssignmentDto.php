<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CompanyAssignmentDto
{
    #[Assert\NotBlank]
    #[Assert\All([
            new Assert\Type("int"),
            new Assert\NotNull(),
        ])]
    public array $companyIds = [];
}
