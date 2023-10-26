<?php

namespace DicodingDev\LaravelDicodingAuth;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class LaravelDicodingAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerSocialiteProvider();
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
