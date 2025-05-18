<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class MustChangePasswordListener
{
    public function __construct(
        private Security $security,
        private RouterInterface $router,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Ne rien faire si c’est une requête vers la route de changement de mot de passe ou logout
        $route = $request->attributes->get('_route');
        
        if (in_array($route, ['app_change_password', 'app_logout'])) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user || !method_exists($user, 'isMustChangePassword')) {
            return;
        }

        if ($user->isMustChangePassword()) {
            // Redirection vers la page de changement de mot de passe
            $event->setResponse(new RedirectResponse(
                $this->router->generate('app_change_password')
            ));
        }
    }
}
