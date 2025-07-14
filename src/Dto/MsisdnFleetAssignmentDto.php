<?php
namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use App\Constante\SelfcareConst;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class MsisdnFleetAssignmentDto
{
    #[Assert\All([
            new Assert\Type("int"),
        ])]
    #[Groups([SelfcareConst::MSISDN_FLEET_WRITE])]
    #[ApiProperty(example: [1, 2, 3])]
    public array $bundleIds = [];
}
