<?php

namespace App\Tests;

use App\Entity\User;
use App\Event\StudentRegisteredEvent;
use App\EventListener\StudentRegisteredListener;
use PHPUnit\Framework\TestCase;
use App\Entity\Student;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class StudentListenerTest extends TestCase
{
    public function testStudentRegistred():void{

        $userEmail='test@exemple.com';
        $userFirstname='Jean';
        $plainPassword = '123456789';

        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn($userEmail);
        $student = $this->createMock(Student::class);
        $student->method('getUser')->willReturn($user);
        $student->method('getFirstname')->willReturn($userFirstname);
         

        $mailer = $this->createMock(MailerInterface::class);
        
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')
        ->with($this->callback(function (Email $email) use ($userEmail,$userFirstname, $plainPassword):bool{
            return (
                    $email->getTo()[0]->getAddress()===$userEmail &&
                    str_contains($email->getTextBody(), $userFirstname) &&
                    str_contains($email->getTextBody(), $plainPassword)
            );
        }));


        $event = new StudentRegisteredEvent($student,$plainPassword);
        $eventListener = new StudentRegisteredListener($mailer);
        $eventListener->onStudentRegistered($event);
    }

}
