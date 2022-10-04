<?php

namespace App\Listeners\VerifyAccount;

use App\Events\UserVerifyAccount;
use App\Notifications\Register\SendEmailVerifyAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailToUserVerifyAccount implements ShouldQueue
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
     * @param UserVerifyAccount $event
     * @return void
     */
    public function handle(UserVerifyAccount $event)
    {
        $token = Str::random(20); // Initial new token

        /**
         * Insert token and user email to the verify accounts for
         * validate the request update status email verified
         */
        DB::table('verify_accounts')->insert([
            'email' => $event->user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Set the url include token in VueJs client side
        $data = [
            'client_url' => env('CLIENT_APP_URL') . "/verify-account?token=$token",
            'userName' => $event->user->name
        ];

        // Send the notification to the user via email
        $event->user->notify(new SendEmailVerifyAccount($data));
    }
}