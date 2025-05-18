<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class ForcePasswordChangeSubscriber implements EventSubscriberInterface
{
     public function __construct(private RouterInterface $router){}

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        
        $user = $event->getUser();

        if (method_exists($user, 'isMustChangePassword') && $user->isMustChangePassword()) {
            $response = new RedirectResponse(
                $this->router->generate('app_change_password')
            );
            $event->setResponse($response);
        }
    }



}


