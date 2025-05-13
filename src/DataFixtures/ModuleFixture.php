<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Module;


class ModuleFixture extends Fixture 
{
    public const  MODULES = [
            'PHP',
            'Javascript',
            'MySql',
            'Python',
            'Java',
            'Oracle',
            'Unix',
            'Cloud',
            'Système',
            'Réseau'
        ];
    public function load(ObjectManager $manager): void
    {

        for($i=0;$i<count(self::MODULES);$i++){
            $module = new Module();
            $module->setName(self::MODULES[$i]);
       

            $manager->persist($module);

            $this->addReference('module_' . $i, $module);
        }
       

        $manager->flush();
    }

    public static function getNbModules():int
    {
        return count(self::MODULES);
    }

}
