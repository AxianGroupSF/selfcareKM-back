<?php
namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class UserInputDto
{
    #[Assert\NotBlank]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $phone;

    #[Assert\Type('bool')]
    public bool $status;

    public ?string $password = null;

    public ?bool $isLdapUser = false;

    /**
     * @var list<array{id: int, rights: list<int>}>
     */
    #[Assert\Count(min: 1)]
    public array $roles = [];
}
