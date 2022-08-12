<?php

namespace App\Repositories\Interfaces;

use App\Models\ChatRoom;
use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IChatRoomRepository extends IEloquentRepository
{
    /**
     * Create new chat room in resources
     * <p>Return <b>String</b> room's name</p>
     *
     * @return ChatRoom
     */
    public function createNewRoom(): ChatRoom;

    public function getChatRoomId(int $sourceId, int $targetId): int;
}
