<?php

namespace DicodingDev\LaravelDicodingAuth;

use Laravel\Socialite\Two\User;

class DicodingUser extends User
{
    public bool $isVerified;

    public function __construct(array $user)
    {
        $this->setRaw($user);
    }
}
