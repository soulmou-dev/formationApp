<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;
use App\Entity\Module;

class ClassroomFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<=5;$i++){
            $classroom = new Classroom();
            $classroom->setName('classe_'.$i);
            $classroom->setYear((int)date('Y'));

            // Associe aléatoirement entre 2 et 4 modules
            $usedIndexes = [];
            $moduleCount = random_int(2, 4);

            for ($j = 0; $j < $moduleCount; $j++) {
                do {
                    $moduleIndex = random_int(0, ModuleFixture::getNbModules() - 1);
                } while (in_array($moduleIndex, $usedIndexes));
                
                $usedIndexes[] = $moduleIndex;
                $classroom->addModule($this->getReference("module_$moduleIndex", Module::class));
            }



            $manager->persist($classroom);




            $this->addReference('classroom_'.$i, $classroom);
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
