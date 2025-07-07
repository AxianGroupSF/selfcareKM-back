<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'selfcare:test:ldap',
    description: 'Test ldap connection',
)]
class TestLdapConnectionCommand extends Command
{
    public function __construct(
        private Ldap $ldap,
        private ParameterBagInterface $parameterBag,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Test Ldap connection')
            ->addArgument('username', InputArgument::REQUIRED, 'nom d\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'mot de passe d\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');

        try {
            $this->ldap->bind($this->parameterBag->get('ldap_search_dn'), $this->parameterBag->get('ldap_search_password'));
            $output->writeln('<info>Connection au serveur ldap en success !</info>');

            $query = $this->ldap->query($this->parameterBag->get('ldap_base_dn'), '(sAMAccountName=' . $username . ')');
            $results = $query->execute();
            if ($results->count() <= 0) {
                throw new NotFoundHttpException('Utilisateur introuvable !');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
