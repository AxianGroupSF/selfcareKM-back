<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\RoleRepository;
use App\Trait\CodeLabelTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[UniqueEntity('code', message: 'Ce code est déjà utilisé.')]
#[UniqueEntity('label', message: 'Ce libellé  est déjà utilisé.')]
#[ApiResource(
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
     * @var Collection<int, right>
     */
    #[ORM\ManyToMany(targetEntity: right::class, inversedBy: 'roles')]
    private Collection $rights;

    public function __construct()
    {
        $this->rights = new ArrayCollection();
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

    public function addRight(right $right): static
    {
        if (!$this->rights->contains($right)) {
            $this->rights->add($right);
        }

        return $this;
    }

    public function removeRight(right $right): static
    {
        $this->rights->removeElement($right);

        return $this;
    }
}
