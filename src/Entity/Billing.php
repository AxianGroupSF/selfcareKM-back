<?php

namespace App\Entity;

use App\Repository\BillingRepository;
use App\Trait\CreatedOnlyTimeTrackableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BillingRepository::class)]
class Billing
{
    use CreatedOnlyTimeTrackableTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalMsisdn = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $invoiceNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalMsisdn(): ?int
    {
        return $this->totalMsisdn;
    }

    public function setTotalMsisdn(?int $totalMsisdn): static
    {
        $this->totalMsisdn = $totalMsisdn;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): static
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}
