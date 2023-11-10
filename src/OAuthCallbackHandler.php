<?php

namespace DicodingDev\LaravelDicodingAuth;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;

trait OAuthCallbackHandler
{
    public function handle()
    {
        try {
            $this->setUserAsAuthed(Socialite::driver('dicoding')->user());

            return $this->handleSuccessfulAuth();
        } catch (Exception $exception) {
            return $this->handleFailedAuth($exception);
        }
    }

    abstract protected function handleSuccessfulAuth();
    abstract protected function handleFailedAuth(Exception $exception);

    private function setUserAsAuthed(User $dicodingUser): void
    {
        Cache::put(
            "user:{$dicodingUser->getId()}",
            $dicodingUser,
            Carbon::now()->addSeconds($dicodingUser->expiresIn)
        );

        Auth::guard('dicoding')->login(AuthenticatedUser::fromOauthUser($dicodingUser));
    }
}
