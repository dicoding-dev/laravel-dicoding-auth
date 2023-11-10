<?php

namespace DicodingDev\LaravelDicodingAuth;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class LaravelDicodingAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerSocialiteProvider();

        Auth::provider(
            'dicoding-user-provider',
            static fn(Container $app) => $app->make(DicodingUserProvider::class)
        );

        $this->app['config']['auth.providers.dicoding-users'] = ['driver' => 'dicoding-user-provider'];
        $this->app['config']['auth.guards.dicoding'] = ['driver' => 'session', 'provider' => 'dicoding-users'];
    }

    private function registerSocialiteProvider(): void
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend(
            'dicoding',
            fn() => $socialite->buildProvider(DicodingSocialiteProvider::class, config('services.dicoding'))
        );
    }
}
