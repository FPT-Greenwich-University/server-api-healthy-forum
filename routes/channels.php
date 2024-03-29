<?php

use App\Broadcasting\ChatChannel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

// Broadcast for new chat room created
Broadcast::channel('chat.{chatRoomId}', ChatChannel::class);

// Broadcast for new messages send
Broadcast::channel('chat-room.{targetId}', function ($user, int $targetId) {
    return Auth::check();
});

Broadcast::channel('posts.notification', function () {
    return Auth::check();
});

