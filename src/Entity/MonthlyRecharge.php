<?php

namespace App\Entity;

use App\Repository\MonthlyRechargeRepository;
use App\Trait\CreatedOnlyTimeTrackableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthlyRechargeRepository::class)]
class MonthlyRecharge
{
    use CreatedOnlyTimeTrackableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $targetMonth = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $rechargeFilePath = null;

    #[ORM\Column(nullable: true)]
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTargetMonth(): ?string
    {
        return $this->targetMonth;
    }

    public function setTargetMonth(?string $targetMonth): static
    {
        $this->targetMonth = $targetMonth;

        return $this;
    }

    public function getRechargeFilePath(): ?string
    {
        return $this->rechargeFilePath;
    }

    public function setRechargeFilePath(?string $rechargeFilePath): static
    {
        $this->rechargeFilePath = $rechargeFilePath;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;

        return $this;
    }
}
