<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;


class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'description',
        'user_id',
        'category_id',
        'published_at',
        'is_published',
    ];

    /**
     * Get the user that owns the post
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the post
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the image belong to the post
     *
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Get all comment of the post
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all the users' favorite list
     * @return MorphMany
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    /**
     * Get all the post's liked from the users
     *
     * @return HasMany
     */
    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    /**
     * The tags that belong to the post
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }


    /**
     * Scope a query to only include publish posts
     *
     * @param $query
     * @return void
     */
    public function scopeIsPublished($query)
    {
        $query->where('posts.is_published', 1);
    }

    /**
     * Scope a query to only include posts not published
     *
     * @param $query
     * @return void
     */
    public function scopeIsNotPublished($query)
    {
        $query->where('posts.is_published', 0);
    }

    /**
     * Scope a query to get only the posts with tag name
     *
     * @param $query
     * @param $tagID -- The id of tags tables
     * @return void
     */
    public function scopeTag($query, $tagID)
    {
        $query->join('post_tag', function ($join) {
            $join->on('posts.id', '=', 'post_tag.post_id');
        })->join('tags', function ($join) use ($tagID) {
            $join->on('post_tag.tag_id', '=', 'tags.id')
                ->where('tags.id', $tagID);
        });
    }
}
