<?php

namespace App\DataFixtures;

use App\Entity\Staff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasherInterface;

    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new Staff();
        $user->setEmail("admin@admin.com");
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "admin"
            )
        );
        $user->setRoles(["ROLE_ADMIN"]);

        $manager->persist($user);
        $manager->flush();
    }
}
