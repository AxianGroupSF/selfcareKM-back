<?php
namespace App\Entity;

use App\Entity\Right;
use ApiPlatform\Metadata\Get;
use App\Trait\CodeLabelTrait;
use App\Constante\SelfcareConst;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity('code', message: 'Ce code est déjà utilisé.')]
#[UniqueEntity('label', message: 'Ce libellé  est déjà utilisé.')]
#[ApiResource(
    normalizationContext: ['groups' => [SelfcareConst::ROLE_READ]],
    denormalizationContext: ['groups' => [SelfcareConst::ROLE_WRITE]],
    operations: [
        new Get(),
        new GetCollection(),
    ],
    inputFormats: ['json' => ['application/json']],
    outputFormats: ['jsonld' => ['application/ld+json'], 'json' => ['application/json']],
)]
class Role
{
    use CodeLabelTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Right>
     */
    #[Groups([SelfcareConst::USER_READ, SelfcareConst::ROLE_READ])]
    #[ORM\ManyToMany(targetEntity: Right::class, inversedBy: 'roles')]
    private Collection $rights;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'userRole')]
    private Collection $users;

    public function __construct()
    {
        $this->rights = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, right>
     */
    public function getRights(): Collection
    {
        return $this->rights;
    }

    public function addRight(Right $right): static
    {
        if (!$this->rights->contains($right)) {
            $this->rights->add($right);
        }

        return $this;
    }

    public function removeRight(Right $right): static
    {
        $this->rights->removeElement($right);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUserRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeUserRole($this);
        }

        return $this;
    }
}
