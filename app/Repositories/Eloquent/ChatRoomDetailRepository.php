<?php

namespace App\Repositories\Eloquent;

use App\Models\ChatRoom;
use App\Models\ChatRoomDetail;
use App\Models\User;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IChatRoomDetailRepository;
use Illuminate\Database\Eloquent\Collection;

class ChatRoomDetailRepository extends BaseRepository implements IChatRoomDetailRepository
{
    public function __construct(ChatRoomDetail $model)
    {
        parent::__construct($model);
    }

    public function getChatRooms(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function checkExistedRoom(int $currentUserId, int $targetUserId): bool
    {
        $existedRoom = $this->model->where('user_id', $currentUserId)
            ->where('target_user_id', $targetUserId)
            ->first();

        if (is_null($existedRoom)) {
            return false;
        }

        return true;
    }

    public function createChatRoomDetails(ChatRoom $chatRoom, User $currentUser, User $targetUser): void
    {
        // Create chat room detail for current user
        $this->model->create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $currentUser->id,
            'target_user_id' => $targetUser->id,
            'name' => $targetUser->name,
        ]);


        // Create chat room detail for target user
        $this->model->create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $targetUser->id,
            'target_user_id' => $currentUser->id,
            'name' => $currentUser->name,
        ]);
    }
}
