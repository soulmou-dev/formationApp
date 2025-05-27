<?php

namespace App\Tests;

use Symfony\Component\Form\Test\TypeTestCase;
use \App\Form\ChangePasswordType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
class ChangePasswordTypeTest extends TypeTestCase
{

     protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testChangeValidPassword():void{
     
        $formData = ["plainPassword" => "123456789"];

        $form = $this->factory->create(ChangePasswordType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($formData, $form->getData());

    }

    public function testChangeInvalidPasswordTooShort():void{
     
        $formData = ["plainPassword" => "1234"];

        $form = $this->factory->create(ChangePasswordType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

    }

    public function testChangeInvalidPasswordEmpty():void{
     
        $formData = ["plainPassword" => ""];

        $form = $this->factory->create(ChangePasswordType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

    }
}
