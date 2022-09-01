<?php

namespace App\Events;

use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Message;

class MessageSent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     *
     * @var User
     */
    public $user;

    /**
     * Message details
     *
     * @var Message
     */
    public $message;

    public $chatRoom;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Message $message, ChatRoom $chatRoom)
    {
        $this->user = $user;
        $this->message = $message;
        $this->chatRoom = $chatRoom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chatRoom.' . $this->chatRoom->id);
    }
}
