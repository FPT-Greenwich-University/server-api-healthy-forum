<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegisterDoctorRole extends Model
{
    use HasFactory;

    protected $table = 'register_doctor_role_drafts';
    protected $fillable = ['user_id', 'is_accept'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
