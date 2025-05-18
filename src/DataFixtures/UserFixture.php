<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){

    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));

        $user2 = new User();
        $user2->setEmail('superadmin@example.com');
        $user2->setRoles(['ROLE_SUPER_ADMIN']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password123'));

        $manager->persist($user);
        $manager->persist($user2);
        
        $manager->flush();
    }
}
