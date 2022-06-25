<?php

namespace App\Providers;

use App\Events\NotifyNewPost;
use App\Events\ResetPassword;
use App\Events\UserVerifyAccount;
use App\Listeners\ResetPassword\NotifyResetPassword;
use App\Listeners\SendEmailNotifyNewPost;
use App\Listeners\VerifyAccount\EmailToUserVerifyAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Verify account
        UserVerifyAccount::class => [
            EmailToUserVerifyAccount::class,
        ],

        // Reset password
        ResetPassword::class => [
            NotifyResetPassword::class
        ],

        // Send email notify new post publised
        NotifyNewPost::class => [
            SendEmailNotifyNewPost::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
