<?php

namespace App\Controller;

use App\Event\PasswordChangedEvent;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        $errorMessage = null;
        if ($error) {
            $errorMessage = 'Email ou mot de passe incorrect.';
        }   
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $errorMessage]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/change-password', name: 'app_change_password')]
    public function changePassword(Request $request,
                                    UserPasswordHasherInterface $passwordHasher,
                                    EntityManagerInterface $em,
                                    Security $security,
                                    EventDispatcherInterface $dispatcher): Response
    {

        /** @var User $user */
        $user = $security->getUser();
    
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('plainPassword')->getData();

            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $user->setMustChangePassword(false);
            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            
            $dispatcher->dispatch(new PasswordChangedEvent($user), PasswordChangedEvent::NAME);

            return $this->redirectToRoute('home_index');
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}