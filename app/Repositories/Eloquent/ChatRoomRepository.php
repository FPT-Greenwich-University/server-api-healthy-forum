<?php

namespace App\Repositories\Eloquent;

use App\Models\ChatRoom;
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


    final public function createNewRoom(): ChatRoom
    {
        return $this->model::create(['name' => Str::random(20) . time()]);
    }

    final public function getChatRooms(int $sourceId): Collection
    {
        return $this->model->whereHas('messages', function (Builder $query) use ($sourceId) {
                $query->where('source_id', '=', $sourceId)
                    ->orWhere('target_id', $sourceId);
            })->get();
    }

    final public function getRoomByUserId(int $sourceId, int $targetId): ChatRoom|null
    {
        return $this->model->whereHas('messages', function (Builder $query) use ($sourceId, $targetId) {
            $query->whereRaw('source_id = ? AND target_id = ?', [$sourceId, $targetId])
                ->orWhereRaw('source_id = ? AND target_id = ?', [$targetId, $sourceId]);
        })->first();
    }
}
