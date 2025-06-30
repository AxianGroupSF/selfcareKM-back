<?php

namespace App\Entity;

use App\Repository\RightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RightRepository::class)]
#[ORM\Table(name: '`right`')]
class Right
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
