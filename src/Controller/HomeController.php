<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name:"home_index")]
    public function index():Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/mail-test')]
    public function mailTest(MailerInterface $mailer)
    {
        try {   
            $email = (new \Symfony\Component\Mime\Email())
                ->from('from@example.org')
                ->to('salim@example.com')
                ->subject('Test Mail')
                ->text('Hello world!');

            $mailer->send($email);

            return new Response('Mail envoyé');
        } catch (\Exception $e) {
        return new Response('Erreur lors de l’envoi du mail : ' . $e->getMessage());
        }
    }
}