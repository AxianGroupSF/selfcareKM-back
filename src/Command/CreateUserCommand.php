<?php
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'selfcare:create:user',
    description: 'Create an user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new user')
            ->addArgument('login', InputArgument::REQUIRED, 'User login (username)')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('phone', InputArgument::REQUIRED, 'User phone')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Collecting inputs
        $login    = $input->getArgument('login');
        $email    = $input->getArgument('email');
        $phone    = $input->getArgument('phone');
        $password = $input->getArgument('password');

        // Creating and populating the User entity
        $user           = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword)
            ->setStatus(1)
            ->setLogin($login)
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN'])
            ->setPhone($phone)
            ->setLdapUser(false)
            ->setCreatedAt(new \DateTimeImmutable());

        // Validate the User entity
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $io->error('Validation failed:');
            foreach ($errors as $error) {
                $io->writeln(sprintf('Field "%s": %s', $error->getPropertyPath(), $error->getMessage()));
            }
            return Command::FAILURE;
        }

        // Persisting the User entity
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('User created successfully.');

        return Command::SUCCESS;
    }
}
