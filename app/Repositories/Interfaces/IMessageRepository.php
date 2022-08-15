<?php

namespace App\Repositories\Interfaces;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface IMessageRepository
{
    public function createNewMessage(array $attributes): Message;

    public function getDetailMessage(int $sourceId, int $targetId): Collection|null;

    public function getTheFirstMessage(int $chatRoomId): Message|null;
}
