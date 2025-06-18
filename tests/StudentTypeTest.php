<?php

namespace App\Tests;

use App\Entity\Classroom;
use App\Entity\Student;
use \App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
;

class StudentTypeTest extends KernelTestCase
{

    private EntityManagerInterface $em;
    private Classroom $classroom;

    private  FormFactoryInterface $formFactory;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->formFactory = $container->get(FormFactoryInterface::class);
        $this->em = $container->get(EntityManagerInterface::class);
        $this->classroom = $this->em->getRepository(Classroom::class)->findOneBy([]);

        parent::setUp();
    }

     public function testSubmitValidData():void
    {
        $classroom = new Classroom();
        $classroom->setName('classe1');
        $formData = [
            'lastname' => 'Dupont',
            'firstname' => 'Jean',
            'dateOfBirth' => '2000-01-01',
            'classroom' => $this->classroom->getId(),
            'email' => 'jean.dupont@example.com',
        ];

        $model  = new Student();
        
        $form = $this->formFactory->create(StudentType::class, $model, [
            'csrf_protection' => false,
            ]);
        
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $expected = new Student();
        $expected->setLastname('Dupont');
        $expected->setFirstname('Jean');
        $expected->setDateOfBirth(new \DateTime('2000-01-01'));
        $expected->setClassroom($this->classroom);

        $this->assertEquals($expected->getLastname(), $model->getLastname());
        $this->assertEquals($expected->getFirstname(), $model->getFirstname());
        $this->assertEquals($expected->getDateOfBirth(), $model->getDateOfBirth());
        $this->assertSame($expected->getClassroom(), $this->classroom);
    
    }

}
