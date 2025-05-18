<?php

namespace App\EventListener;

use App\Event\PasswordChangedEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PasswordChangedListener
{
    public function __construct(private MailerInterface $mailer) {}

    public function onPasswordChanged(PasswordChangedEvent $event):void 
    {
        $user = $event->getUser();
        $email = $user->getEmail();

        $mail = (new Email())
            ->from('no-reply@formation-app.com')
            ->to($email)
            ->subject('Votre mot de passe a été modifié avec succès')
            ->text("Bonjour,\n\nVotre mot de passe vient d’être modifié.\n\nCordialement,\nL’équipe FormationApp");

        $this->mailer->send($mail);

    }

}
