<?php

namespace App\Listeners;

use App\Events\NotifyNewPost;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\SendMailForNewPost;

class SendEmailNotifyNewPost implements ShouldQueue
{
    protected IUserRepository $userRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotifyNewPost  $event
     * @return void
     */
    public function handle(NotifyNewPost $event)
    {
        $users = $this->userRepository->getAll();
        $users->each->notify(new SendMailForNewPost($event->post));
    }
}
