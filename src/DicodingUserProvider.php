<?php

namespace DicodingDev\LaravelDicodingAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class DicodingUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return Cache::get("user:$identifier");
    }

    public function retrieveByToken($identifier, $token)
    {
        throw new RuntimeException('Method is not implemented.');
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new RuntimeException('Method is not implemented.');
    }

    public function retrieveByCredentials(array $credentials)
    {
        throw new RuntimeException('Method is not implemented.');
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return Cache::has('user:' . $user->getAuthIdentifier());
    }
}
