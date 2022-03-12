<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyAccount extends Model
{
    use HasFactory;

    protected $table = 'verify_accounts';
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
