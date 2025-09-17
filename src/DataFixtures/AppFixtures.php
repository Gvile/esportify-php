<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setEmail("Admin@gmail.com")->setPassword($this->passwordHasher->hashPassword($user, "Azerty12345"))->setRoles(['ROLE_ADMIN'])->setPseudo("Guillaume");

        $manager->persist($user);

        $manager->flush();
    }
}
