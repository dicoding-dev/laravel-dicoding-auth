<?php

namespace DicodingDev\LaravelDicodingAuth;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class ServiceProvider extends LaravelServiceProvider
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
