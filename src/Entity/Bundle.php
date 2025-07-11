<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Constante\SelfcareConst;
use App\Repository\BundleRepository;
use App\Trait\CreatedOnlyTimeTrackableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity('name', message: 'Ce nom est déjà utilisé.')]
#[ApiResource(
    normalizationContext: ['groups' => [SelfcareConst::BUNDLE_READ]],
    denormalizationContext: ['groups' => [SelfcareConst::BUNDLE_WRITE]],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
    ],
    inputFormats: ['json' => ['application/json']],
    outputFormats: ['jsonld' => ['application/ld+json'], 'json' => ['application/json']],
)]
#[ORM\Entity(repositoryClass: BundleRepository::class)]
#[ORM\HasLifecycleCallbacks] 
class Bundle
{
    use CreatedOnlyTimeTrackableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups([SelfcareConst::BUNDLE_WRITE])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

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

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
