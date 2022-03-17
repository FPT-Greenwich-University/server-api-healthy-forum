<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'favoriteable_id',
        'favoriteable_type'
    ];

    /**
     * Get the parent favoriteable model (user or post)
     *
     * @return MorphTo
     */
    public function favoriteable(): MorphTo
    {
        return $this->morphTo();
    }
}
