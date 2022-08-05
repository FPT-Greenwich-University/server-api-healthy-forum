<?php

namespace App\Repositories\Eloquent;

use App\Models\PostLike;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPostLikeRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PostLikeRepository extends BaseRepository implements IPostLikeRepository
{
    public function __construct(PostLike $model)
    {
        parent::__construct($model);
    }

    public function handleGetPostsMostLiked(int $perPage): mixed
    {
        try {
            return $this->model->join("posts", "post_likes.post_id", "=", "posts.id")
                ->select(DB::raw("count(post_likes.post_id) as total_liked, posts.*"))
                ->groupBy("post_likes.post_id")
                ->orderBy("total_liked", "desc")
                ->paginate($perPage);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getTotalLike(int $postId)
    {
        try {
            return $this->model->where('post_id', $postId)->count();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function checkIsUserLikePost(int $postId, int $userId)
    {
        try {
            return $this->model->where("user_id", "=", $userId)
                ->where("post_id", "=", $postId)
                ->first();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deleteLike(int $userId, int $postId)
    {
        try {
            $this->model->where("user_id", "=", $userId)
                ->where("post_id", "=", $postId)
                ->delete();
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}