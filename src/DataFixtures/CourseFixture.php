<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Module;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CourseFixture extends Fixture  implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i=0;$i<ModuleFixture::getNbModules();$i++){

            $module = $this->getReference('module_' . $i, Module::class);

            $nbCourse = rand(3,5);
            
            for($j=0; $j<$nbCourse;$j++){
                $course = new Course();
                $course->setName($faker->sentence(rand(2,5)));
                $course->setFile($faker->slug() . '.pdf');
                $course->setModule($module);

                $manager->persist($course);
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
