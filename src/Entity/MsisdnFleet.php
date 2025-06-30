<?php

namespace App\Entity;

use App\Repository\MsisdnFleetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MsisdnFleetRepository::class)]
class MsisdnFleet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $msisdn = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\Column]
    private ?float $rechargeMainAccount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMsisdn(): ?string
    {
        return $this->msisdn;
    }

    public function setMsisdn(string $msisdn): static
    {
        $this->msisdn = $msisdn;

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

    public function getRechargeMainAccount(): ?float
    {
        return $this->rechargeMainAccount;
    }

    public function setRechargeMainAccount(float $rechargeMainAccount): static
    {
        $this->rechargeMainAccount = $rechargeMainAccount;

        return $this;
    }
}
