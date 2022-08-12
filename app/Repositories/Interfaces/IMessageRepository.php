<?php

namespace App\Repositories\Interfaces;

use App\Models\Message;

interface IMessageRepository
{
    public function createNewMessage(array $attributes): Message;

    public function getDetailMessage(int $sourceId, int $targetId): Message;
}
