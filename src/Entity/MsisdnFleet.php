<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Constante\SelfcareConst;
use App\Dto\MsisdnFleetAssignmentDto;
use App\Entity\Bundle;
use App\Repository\MsisdnFleetRepository;
use App\State\MsisdnFleetAssignmentProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MsisdnFleetRepository::class)]
#[UniqueEntity('msisdn', message: 'Ce msisdn est déjà utilisé.')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(
            name: 'update_msisdnfleet',
            uriTemplate: '/msisdnfleet/{id}/bundles',
            input: MsisdnFleetAssignmentDto::class,
            processor: MsisdnFleetAssignmentProcessor::class,
            denormalizationContext: ['groups' => [SelfcareConst::MSISDN_FLEET_WRITE]],
        ),
    ],
    inputFormats: ['json' => ['application/json']],
    outputFormats: ['jsonld' => ['application/ld+json'], 'json' => ['application/json']],
)]
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

    /**
     * @var Collection<int, Bundle>
     */
    #[ORM\ManyToMany(targetEntity: Bundle::class, inversedBy: 'msisdnFleets')]
    private Collection $bundles;

    public function __construct()
    {
        $this->bundles = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Bundle>
     */
    public function getBundles(): Collection
    {
        return $this->bundles;
    }

    public function addBundle(Bundle $bundle): static
    {
        if (! $this->bundles->contains($bundle)) {
            $this->bundles->add($bundle);
        }

        return $this;
    }

    public function removeBundle(Bundle $bundle): static
    {
        $this->bundles->removeElement($bundle);

        return $this;
    }
}
