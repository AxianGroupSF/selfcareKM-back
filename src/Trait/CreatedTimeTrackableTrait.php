<?php
namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait CreatedTimeTrackableTrait
{
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?object $createdBy = null;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): ?object
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?object $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }
}
