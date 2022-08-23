<?php

namespace App\Repositories\Interfaces;

use App\Models\Message;
use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Collection;

interface IMessageRepository extends IEloquentRepository
{
    public function createNewMessage(array $attributes): Message;

    /**
     * Get messages include User information by chat room id
     * @param int $chatRoomId
     * @return Collection|null
     */
    public function getMessagesByChatRoom(int $chatRoomId): Collection|null;

    public function getDetailMessage(int $sourceId, int $targetId): Collection|null;

    public function getTheFirstMessage(int $chatRoomId): Message|null;
}
