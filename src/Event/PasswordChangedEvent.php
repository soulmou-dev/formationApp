<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class PasswordChangedEvent extends Event
{
    public const NAME = 'user.password_changed';

    public function __construct(private User $user){}

    public function getUser(): User
    {
        return $this->user;
    }

}
