<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'age',
        'gender',
        'city',
        'district',
        'ward',
        'street',
        'description',
        'user_id'
    ];

    // Define relationships

    /**
     * Get the profile associated with the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
