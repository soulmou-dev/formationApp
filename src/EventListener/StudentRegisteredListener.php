<?php
namespace App\EventListener;

use App\Event\StudentRegisteredEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class StudentRegisteredListener
{
    public function __construct(private MailerInterface $mailer) {}

    public function onStudentRegistered(StudentRegisteredEvent $event): void
    {  
        $student = $event->getStudent();
        $user = $student->getUser();
        $email = $user->getEmail();

        $plainPassword = $event->getPlainPassword();

        $mail = (new Email())
            ->from('no-reply@formation-app.com')
            ->to($email)
            ->subject('Votre compte étudiant')
            ->text("Bonjour {$student->getFirstname()},\n\nVotre compte a été créé.\nEmail : {$email}\nMot de passe provisoire : {$plainPassword}");

        $this->mailer->send($mail);
    }
}