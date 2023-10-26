<?php

namespace DicodingDev\LaravelDicodingAuth;

use Laravel\Socialite\Two\AbstractProvider;

class DicodingSocialiteProvider extends AbstractProvider
{
    public function getDicodingUrl(): string
    {
        return config('services.dicoding.base_uri');
    }

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase($this->getDicodingUrl() . '/oauth/authorize', $state);
    }

    protected function getTokenUrl(): string
    {
        return $this->getDicodingUrl() . '/api/v1/oauth/access_token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getDicodingUrl() . '/api/v1/oauth/me', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user): DicodingUser
    {
        return (new DicodingUser($user['data']))->map([
            'id' => $user['data']['id'],
            'email' => $user['data']['email'],
            'name' => $user['data']['full_name'],
            'nickname' => $user['data']['username'],
            'isVerified' => (bool) $user['data']['is_verified_user'],
            'avatar' => $user['data']['avatar_url'],
        ]);
    }
}
