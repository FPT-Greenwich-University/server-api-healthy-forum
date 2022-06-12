<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPostLikeRepository;
use App\Models\PostLike;
use Illuminate\Support\Facades\DB;

class PostLikeRepository extends BaseRepository implements IPostLikeRepository
{
    public function __construct(PostLike $model)
    {
        parent::__construct($model);
    }

    public function handleGetPostsMostLiked(int $per_page): mixed
    {
        return DB::table('post_likes')->join("posts", "post_likes.post_id", "=", "posts.id")
            ->select(DB::raw("count(post_likes.post_id) as total_liked, posts.*"))
            ->groupBy("post_likes.post_id")
            ->orderBy("total_liked", "desc")
            ->paginate($per_page);
    }
}
