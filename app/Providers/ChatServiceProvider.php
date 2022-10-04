<?php

namespace App\Providers;

use App\Services\Chat\ChatServiceContracts;
use App\Services\Chat\ChatServices;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChatServiceContracts::class, ChatServices::class);
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
