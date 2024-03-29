<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
