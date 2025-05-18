<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use App\Entity\Module;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TeacherFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){

    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i=0;$i<ModuleFixture::getNbModules();$i++){

            $module = $this->getReference('module_' . $i, Module::class);

            $nbTeacher = rand(2,3);
            
            for($j=0; $j<$nbTeacher;$j++){
                $teacher = new Teacher();
                $teacher->setFirstname( $faker->firstName());
                $teacher->setLastname( $faker->lastName());
                $dateBirth = DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-60 years', '-18 years')
                );
                $teacher->setDateOfBirth  ($dateBirth);
                $teacher->setModule($module);

                $user = new User();
                $user->setEmail(strtolower($teacher->getFirstname() . '.' . $teacher->getLastname() . $i . $j . '@example.com'));
                $user->setRoles(['ROLE_TEACHER']);
                $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));

                // Liaison
                $teacher->setUser($user);
                $user->setTeacher($teacher);

                $manager->persist($teacher);
            }   
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ModuleFixture::class
        ];
    }
}
