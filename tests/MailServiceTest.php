<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\MailService;

class MailServiceTest extends  TestCase
{

    public function testEmailsSent():void{

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')
        ->with($this->callback(function (Email $email):bool{
            return ($email->getTo()[0]->getAddress()==='user@exemple.com' && $email->getSubject()==="Bienvenue sur FormationApp!");
        }));
    

        $service= new MailService($mailer);
        $service->sendWelcomeEmail('user@exemple.com');
    }
}

