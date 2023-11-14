<?php

namespace DicodingDev\LaravelDicodingAuth;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;

trait OAuthCallbackHandler
{
    public function handle(Request $request)
    {
        try {
            $this->setUserAsAuthed(Socialite::driver('dicoding')->user());

            return $this->handleSuccessfulAuth();
        } catch (Exception $exception) {
            if ($request->query('error') === 'access_denied') {
                return $this->handleAccessDenied();
            }

            return $this->handleFailedAuth($exception);
        }
    }

    abstract protected function handleSuccessfulAuth();
    abstract protected function handleAccessDenied();
    abstract protected function handleFailedAuth(Exception $exception);

    private function setUserAsAuthed(User $dicodingUser): void
    {
        $authenticatedUser = AuthenticatedUser::fromOauthUser($dicodingUser);

        Cache::put(
            AuthenticatedUser::cacheKey($dicodingUser->getId()),
            $authenticatedUser,
            $authenticatedUser->tokenExpiredAt
        );

        Auth::guard('dicoding')->login($authenticatedUser);
    }
}
