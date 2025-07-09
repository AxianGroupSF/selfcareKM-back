<?php
namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Constante\SelfcareConst;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'UserInputUpdate',
    description: 'Payload for editing a user',
    deprecated: false,
    denormalizationContext: ['groups' => [SelfcareConst::USER_WRITE]]
)]
final class UserInputUpdateDto
{
    #[Groups([SelfcareConst::USER_WRITE])]
    #[Assert\NotBlank]
    #[ApiProperty(example: "john.doe")]
    public string $login;

    #[Groups([SelfcareConst::USER_WRITE])]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[ApiProperty(example: "john@example.com")]
    public string $email;

    #[Groups([SelfcareConst::USER_WRITE])]
    #[Assert\NotBlank]
    #[ApiProperty(example: "0341234567")]
    public string $phone;

    #[Groups([SelfcareConst::USER_WRITE])]
    #[Assert\Type('bool')]
    #[ApiProperty(example: true)]
    public bool $status;

    #[Groups([SelfcareConst::USER_WRITE])]
    #[ApiProperty(example: "secret123")]
    public ?string $password = null;

    #[Groups([SelfcareConst::USER_WRITE])]
    #[ApiProperty(example: false)]
    public ?bool $isLdapUser = false;

    /**
     * Exemple : [{"id": 1, "rights": [2, 3]}, {"id": 2, "rights": [4]}]
     * 
     * @var list<array{id: int, rights: list<int>}>
     */
    #[Groups([SelfcareConst::USER_WRITE])]
    #[Assert\Count(min: 1)]
    #[ApiProperty(example: [
        ['id' => 1, 'rights' => [2, 3]],
        ['id' => 2, 'rights' => [4]],
    ])]
    public array $roles = [];
}
