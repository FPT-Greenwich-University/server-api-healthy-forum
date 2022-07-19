<?php

namespace App\Providers;

use App\Services\AuthenticationServices\Authentication;
use App\Services\AuthenticationServices\AuthenticationInterface;
use Illuminate\Support\ServiceProvider;

class CustomAuthServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthenticationInterface::class, Authentication::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
