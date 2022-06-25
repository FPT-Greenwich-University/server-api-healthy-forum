<?php

namespace App\Listeners\ResetPassword;

use App\Events\ResetPassword;
use App\Notifications\ForgotPassword\SendLinkResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyResetPassword implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ResetPassword $event
     * @return void
     */
    public function handle(ResetPassword $event)
    {
        $event->user->notify(new SendLinkResetPassword($event->data));
    }
}
