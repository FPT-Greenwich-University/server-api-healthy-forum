<?php

namespace App\Repositories\Eloquent;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IChatRoomRepository;
use Illuminate\Support\Str;

class ChatRoomRepository extends BaseRepository implements IChatRoomRepository
{
    public function __construct(ChatRoom $model)
    {
        parent::__construct($model);
    }


    public function createNewRoom(): ChatRoom
    {
        $name = Str::random(20) . time();
        return $this->model::create(['name' => $name]);
    }

    public function getChatRoomId(int $sourceId, int $targetId): int
    {
        $messages = Message::where('source_id', $sourceId)->where('target_id', $targetId)->first();

        return intval($messages->chat_room_id);
    }
}
