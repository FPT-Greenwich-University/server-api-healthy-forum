<?php

namespace App\Providers;

use App\Services\FileServices\FileServicesContract;
use App\Services\FileServices\HandleFile;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileServicesContract::class, HandleFile::class);
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
