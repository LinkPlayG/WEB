<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface
{
    private ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $passwordHasher = $this->container->get('security.password_hasher');

        // Création de l'administrateur
        $admin = new User();
        $admin->setNom('admin');
        $admin->setPrenom('admin');
        $admin->setEmail('admin@interned.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $passwordHasher->hashPassword(
                $admin,
                'Admin123!'
            )
        );

        $manager->persist($admin);
        $manager->flush();
    }
} 