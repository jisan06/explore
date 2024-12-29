<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        'App\Events\UserSaved' => [
            'App\Listeners\SaveUserBackgroundInformation',
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
