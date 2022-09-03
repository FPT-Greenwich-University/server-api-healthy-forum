<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'chat_room_id',
        'user_id',
        'target_user_id',
    ];
}
