<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IMessageRepository;
use Illuminate\Database\Eloquent\Collection;

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

    public function getDetailMessage(int $sourceId, int $targetId): Collection|null
    {
        return $this->model->whereRaw('source_id = ? AND target_id = ?', [$sourceId, $targetId])
            ->orWhereRaw('source_id = ? AND target_id = ?', [$targetId, $sourceId])
            ->get();
    }

    public function getTheFirstMessage(int $chatRoomId): Message|null
    {
        return $this->model->where('chat_room_id', $chatRoomId)->first();
    }

    public function getMessagesByChatRoom(int $chatRoomId): Collection|null
    {
        return $this->model->with(['user'])->where('chat_room_id', $chatRoomId)->get();
    }
}
