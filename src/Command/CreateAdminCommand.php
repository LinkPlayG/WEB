<?php

namespace App\Command;

use App\Entity\Administrateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the admin user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the admin user')
            ->addArgument('nom', InputArgument::REQUIRED, 'The last name of the admin user')
            ->addArgument('prenom', InputArgument::REQUIRED, 'The first name of the admin user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');

        $admin = new Administrateur();
        $admin->setEmail($email);
        $admin->setNomAdmin($nom);
        $admin->setPrenomAdmin($prenom);
        
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $password
        );
        $admin->setPassword($hashedPassword);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success("Admin user created with email: {$email}");

        return Command::SUCCESS;
    }
}
