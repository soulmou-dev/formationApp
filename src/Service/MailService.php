<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class MailService {

    public function __construct(private MailerInterface $mailer) {}

    public function sendWelcomeEmail(string $to){
        $mail=(new Email()) 
        ->from('no-reply@formation-app.com')
        ->to($to)
        ->subject('Bienvenue sur FormationApp!')
        ->text("Merci de vous etre inscrit.");

        $this->mailer->send($mail);
        
    }
}