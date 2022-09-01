<?php

namespace App\Broadcasting;

use App\Models\User;

class ChatChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @param int $chatRoomId
     * @return bool
     */
    public function join(User $user, int $chatRoomId)
    {
        // Retrieve permission name
        $permissionName = 'chat-room.' . $chatRoomId;

        // Broadcasting if this user belong to this chat room with permission
        return $user->hasPermissionTo($permissionName, 'web');
    }
}
