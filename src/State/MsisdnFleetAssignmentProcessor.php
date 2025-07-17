<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Bundle;
use App\Entity\MsisdnFleet;
use App\Exception\AccessDeniedHttpException;
use App\Service\PeriodValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MsisdnFleetAssignmentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): MsisdnFleet
    {
        $msisdnFleetId = $uriVariables['id'] ?? null;
        if (! $msisdnFleetId) {
            throw new NotFoundHttpException('L\'ID msisdnfleet est requis');
        }

        /** @var MsisdnFleet|null $msisdnFleet */
        $msisdnFleet = $this->em->getRepository(MsisdnFleet::class)->find($msisdnFleetId);
        if (! $msisdnFleet) {
            throw new NotFoundHttpException("Bundle introuvable.");
        }

        // On vide les bundles actuelles
        foreach ($msisdnFleet->getBundles() as $bundle) {
            $msisdnFleet->removeBundle($bundle);
        }

        // Réattribution avec la nouvelle liste
        foreach ($data->bundleIds as $bundleId) {
            $bundle = $this->em->getRepository(Bundle::class)->find($bundleId);
            if (! $bundle) {
                throw new NotFoundHttpException("Bundle ID {$bundleId} introuvable.");
            }

            $msisdnFleet->addBundle($bundle);
        }

        $this->em->flush();

        return $msisdnFleet;
    }
}
