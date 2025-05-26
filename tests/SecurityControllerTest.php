<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    public function testLoginPage():void{

        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

    }

     public function testLogoutPage():void{

        $client = static::createClient();
        $client->request('GET', '/logout');

         $this->assertResponseRedirects('/login');
        
    }

}
