<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
class HomeControllerTest  extends WebTestCase
{

    public function testIndexIsSuccessful():void{

       

        /**
         * @var KernelBrowser $client
         */
        $client = static::createClient();

        $user  = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneByRole('ROLE_ADMIN');
        $this->assertNotNull($user, 'L\'utilisateur avec ROLE_ADMIN doit exister');
        
        $client->loginUser($user);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');

    }

}
