<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\PasswordGenerator;

class PasswordGeneratorTest extends TestCase
{

    public function testGenerate():void{

        $pwdGenerator = new PasswordGenerator();
        $password = $pwdGenerator->generate(12);
        $this->assertIsString($password);
        $this->assertSame(12, strlen($password));
    }
}
