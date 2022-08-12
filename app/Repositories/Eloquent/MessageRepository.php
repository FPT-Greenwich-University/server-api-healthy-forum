<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IMessageRepository;

class MessageRepository extends BaseRepository implements IMessageRepository
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    public function createNewMessage(array $attributes): Message
    {
        return $this->model->create($attributes);
    }

    public function getDetailMessage(int $sourceId, int $targetId): Message
    {
        return $this->model->whereRaw('source_id = ? AND target_id = ?', [$sourceId, $targetId])
            ->orWhereRaw('source_id = ? AND target_id = ?', [$targetId, $sourceId])
            ->first();
    }
}
