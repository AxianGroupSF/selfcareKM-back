<?php
namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CompanyRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Trait\CreatedOnlyTimeTrackableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[UniqueEntity('name', message: 'Ce nom est déjà utilisé.')]
#[UniqueEntity('cugNumber', message: 'Ce code CUG est déjà utilisé.')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch()
    ],
    inputFormats: ['json' => ['application/json']],
    outputFormats: ['jsonld' => ['application/ld+json'], 'json' => ['application/json']],
)]
class Company
{
    use CreatedOnlyTimeTrackableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $cugNumber = null;

    #[ORM\Column]
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCugNumber(): ?string
    {
        return $this->cugNumber;
    }

    public function setCugNumber(string $cugNumber): static
    {
        $this->cugNumber = $cugNumber;

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

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
