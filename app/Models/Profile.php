<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    /**
     * Constant
     */
    const MALE_GENDER = 0;
    const FEMALE_GENDER = 1;

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

    /**
     * Get the profile associated with the user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
