<?php
namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserCompanyAssignmentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        $userId = $uriVariables['id'] ?? null;
        if (! $userId) {
            throw new NotFoundHttpException('L\'ID utilisateur est requis');
        }

        /** @var User|null $user */
        $user = $this->em->getRepository(User::class)->find($userId);
        if (! $user) {
            throw new NotFoundHttpException("Utilisateur introuvable.");
        }

        // On vide les sociétés actuelles
        foreach ($user->getCompany() as $company) {
            $user->removeCompany($company);
        }

        // Réattribution avec la nouvelle liste
        foreach ($data->companyIds as $companyId) {
            $company = $this->em->getRepository(Company::class)->find($companyId);
            if (! $company) {
                throw new NotFoundHttpException("Company ID {$companyId} introuvable.");
            }

            $user->addCompany($company);
        }

        $this->em->flush();

        return $user;
    }
}
