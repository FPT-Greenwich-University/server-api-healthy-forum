<?php

namespace App\Repositories\Eloquent;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IChatRoomRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

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

    public function getChatRooms(int $sourceId): Collection
    {
        return $this->model->whereHas('messages', function (Builder $query) use ($sourceId) {
            $query->where('source_id', '=', $sourceId)
                ->orWhere('target_id', $sourceId);
        })->get();
    }

    public function getRoomByUserId(int $sourceId, int $targetId): ChatRoom|null
    {
        return $this->model->whereHas('messages', function (Builder $query) use ($sourceId, $targetId) {
            $query->whereRaw('source_id = ? AND target_id = ?', [$sourceId, $targetId])
                ->orWhereRaw('source_id = ? AND target_id = ?', [$targetId, $sourceId]);
        })->first();
    }
}
