<?php

namespace App\Repositories\Interfaces;

use App\Models\ChatRoom;
use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Collection;

interface IChatRoomRepository extends IEloquentRepository
{
    /**
     * Create new chat room in resources
     * <p>Return <b>String</b> room's name</p>
     *
     * @return ChatRoom
     */
    public function createNewRoom(): ChatRoom;

    public function getChatRooms(int $sourceId): Collection;

    public function getRoomByUserId(int $sourceId, int $targetId): ChatRoom|null;
}
