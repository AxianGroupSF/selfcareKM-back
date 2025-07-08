<?php
namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class UserInputDto
{
    #[Assert\NotBlank]
    #[ApiProperty(example: "john.doe")]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ApiProperty(example: "john@example.com")]
    public string $email;

    #[Assert\NotBlank]
    #[ApiProperty(example: "0341234567")]
    public string $phone;

    #[Assert\Type('bool')]
    #[ApiProperty(example: true)]
    public bool $status;

    #[ApiProperty(example: "secret123")]
    public ?string $password = null;

    #[ApiProperty(example: false)]
    public ?bool $isLdapUser = false;

    /**
     * Exemple : [{"id": 1, "rights": [2, 3]}, {"id": 2, "rights": [4]}]
     * 
     * @var list<array{id: int, rights: list<int>}>
     */
    #[Assert\Count(min: 1)]
    #[ApiProperty(example: [
        ['id' => 1, 'rights' => [2, 3]],
        ['id' => 2, 'rights' => [4]],
    ])]
    public array $roles = [];
}
