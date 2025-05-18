<?php

namespace App\DataFixtures;

use App\Entity\Student;
use App\Entity\Classroom;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StudentFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){

    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i=1;$i<=5;$i++){

            $classroom = $this->getReference('classroom_' . $i, Classroom::class);

            $nbStudent = rand(15,20);
            
            for($j=0; $j<$nbStudent;$j++){
                $student = new Student();
                $student->setFirstname( $faker->firstName());
                $student->setLastname( $faker->lastName());
                $dateBirth = DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('-60 years', '-18 years')
                );
                $student->setDateOfBirth  ($dateBirth);
                $student->setClassroom($classroom);



                $user = new User();
                $user->setEmail(strtolower($student->getFirstname() . '.' . $student->getLastname() . $i . $j . '@example.com'));
                $user->setRoles(['ROLE_STUDENT']);
                $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));

                // Liaison
                $student->setUser($user);
                $user->setStudent($student);

                $manager->persist($student);

            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClassroomFixture::class
        ];
    }
}
