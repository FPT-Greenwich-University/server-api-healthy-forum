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

    public function getMessagesByChatRoom(int $chatRoomId): Collection|null
    {
        return $this->model->with(['user', 'files'])
            ->where('chat_room_id', $chatRoomId)
            ->get();
    }
}
