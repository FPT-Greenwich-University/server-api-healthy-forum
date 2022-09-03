<?php

namespace App\Repositories\Interfaces;

use App\Models\ChatRoom;
use App\Models\User;
use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Collection;

interface IChatRoomDetailRepository extends IEloquentRepository
{
    /**
     * Get all chat rooms by user
     *
     * @param int $userId
     * @return Collection
     */
    public function getChatRooms(int $userId): Collection;

    /**
     * Check the chat room is existed by current user and target user
     *
     * @param int $currentUserId
     * @param int $targetUserId
     * @return bool <p>Return <b>TRUE</b> if room is existed, otherwise <b>FALSE</b></p>
     */
    public function checkExistedRoom(int $currentUserId, int $targetUserId): bool;

    /**
     * Create chat room details for both of two users
     *
     * @param ChatRoom $chatRoom
     * @param User $currentUser
     * @param User $targetUser
     * @return void
     */
    public function createChatRoomDetails(ChatRoom $chatRoom, User $currentUser, User $targetUser): void;
}
