<?php
namespace App\Entity;

use App\Dto\UserInputDto;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Dto\UserInputUpdateDto;
use App\Constante\SelfcareConst;
use App\State\UserPostProcessor;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\CompanyAssignmentDto;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Trait\CreatedTimeTrackableTrait;
use Doctrine\Common\Collections\Collection;
use App\State\UserCompanyAssignmentProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message: 'Cet email est déjà utilisé.', groups: ['create'])]
#[UniqueEntity('login', message: 'Ce login est déjà utilisé.',  groups: ['create'])]
#[ApiResource(
    normalizationContext: ['groups' => [SelfcareConst::USER_READ]],
    denormalizationContext: ['groups' => [SelfcareConst::USER_WRITE]],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            input: UserInputDto::class,
            processor: UserPostProcessor::class
        ),
        new Patch(
            input: UserInputUpdateDto::class,
            processor: UserPostProcessor::class,
            read: true
        ),
        new Patch(
            name: 'update_companies',
            uriTemplate: '/users/{id}/companies',
            input: CompanyAssignmentDto::class,
            processor: UserCompanyAssignmentProcessor::class,
            denormalizationContext: ['groups' => [SelfcareConst::USER_COMPANY_WRITE]],
        )
    ],
    inputFormats: ['json' => ['application/json']],
    outputFormats: ['jsonld' => ['application/ld+json'], 'json' => ['application/json']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedTimeTrackableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups([SelfcareConst::USER_READ, SelfcareConst::USER_WRITE])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $login = null;

    #[Groups([SelfcareConst::USER_READ, SelfcareConst::USER_WRITE])]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[Groups([SelfcareConst::USER_READ, SelfcareConst::USER_WRITE])]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[Groups([SelfcareConst::USER_READ, SelfcareConst::USER_WRITE])]
    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column(nullable: true)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Groups([SelfcareConst::USER_WRITE])]
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[Groups([SelfcareConst::USER_READ, SelfcareConst::USER_WRITE])]
    #[ORM\Column(nullable: true)]
    private ?bool $isLdapUser = true;

    /**
     * @var Collection<int, role>
     */
    #[Groups([SelfcareConst::USER_READ])]
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $userRole;

    /**
     * @var Collection<int, Company>
     */
    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'users')]
    private Collection $company;

    public function __construct()
    {
        $this->userRole = new ArrayCollection();
        $this->company = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $rolesUser = $this->roles;
        // guarantee every user at least has ROLE_USER : $rolesUser[] = 'ROLE_USER';
        return array_unique($rolesUser);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        // Si c'est un utilisateur LDAP, pas de mot de passe stocké en base
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function isLdapUser(): ?bool
    {
        return $this->isLdapUser;
    }

    public function setLdapUser(?bool $isLdapUser): static
    {
        $this->isLdapUser = $isLdapUser;

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getUserRole(): Collection
    {
        return $this->userRole;
    }

    public function addUserRole(Role $userRole): static
    {
        if (! $this->userRole->contains($userRole)) {
            $this->userRole->add($userRole);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): static
    {
        $this->userRole->removeElement($userRole);

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompany(): Collection
    {
        return $this->company;
    }

    public function addCompany(Company $company): static
    {
        if (!$this->company->contains($company)) {
            $this->company->add($company);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        $this->company->removeElement($company);

        return $this;
    }
}
