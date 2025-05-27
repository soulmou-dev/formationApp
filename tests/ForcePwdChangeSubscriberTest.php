<?php

namespace App\Tests;

use App\Entity\User;
use App\EventSubscriber\ForcePasswordChangeSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Routing\RouterInterface;

class ForcePwdChangeSubscriberTest extends TestCase
{

    public function testRedirectsToChangePasswordIfRequired():void{


        $mustChangePassword = true;

        $user = $this->createMock(User::class);
        $user->method('isMustChangePassword')->willReturn($mustChangePassword);

        $authentificator= $this->createMock(AuthenticatorInterface::class);

        $passport = $this->createMock(Passport::class);
        $passport->method('getUser')->willReturn($user);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $router = $this->createMock(RouterInterface::class);
        $router->method('generate')->willReturn('/change-password');
        
        $event = new LoginSuccessEvent($authentificator,$passport, $token,new Request(),null, 'main');
        $eventListener = new ForcePasswordChangeSubscriber($router);
        $eventListener->onLoginSuccess($event);

        $response = $event->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($response->getTargetUrl(),'/change-password');
    }

    public function testDoesNothingIfPasswordChangeNotRequired():void{


        $mustChangePassword = false;

        $user = $this->createMock(User::class);
        $user->method('isMustChangePassword')->willReturn($mustChangePassword);

        $authentificator= $this->createMock(AuthenticatorInterface::class);

        $passport = $this->createMock(Passport::class);
        $passport->method('getUser')->willReturn($user);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        $router = $this->createMock(RouterInterface::class);
        $router->method('generate')->willReturn('/change-password');
        
        $event = new LoginSuccessEvent($authentificator,$passport, $token,new Request(),null, 'main');
        $eventListener = new ForcePasswordChangeSubscriber($router);
        $eventListener->onLoginSuccess($event);

        $this->assertNull($event->getResponse());
    }
}
