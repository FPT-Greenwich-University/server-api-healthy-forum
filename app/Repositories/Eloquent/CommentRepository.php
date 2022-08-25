<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\ICommentRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    final public function getAllComments(int $postId, int $perPage)
    {
        try {
            return Comment::with(['user.image'])
                ->whereNull('parent_comment_id')
                ->where('post_id', $postId)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function getReplyComments(int $postId, int $rootCommentId): Collection|string|array
    {
        try {
            return $this->model->with(['user.image'])
                ->where('post_id', '=', $postId)
                ->where('parent_comment_id', '=', $rootCommentId)
                ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function updateComment(int $postId, int $commentId, array $attributes)
    {
        try {
            $this->model->where('post_id', '=', $postId)
                ->where('id', $commentId)->update($attributes);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function getDetail(int $postId, int $commentId): Comment|string
    {
        try {
            return $this->model->where('post_id', '=', $postId)
                ->where('id', $commentId)
                ->first();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function deleteComment(int $postId, int $commentId)
    {
        try {
            // Delete comment
            $this->model->where('post_id', $postId)
                ->where('id', $commentId)
                ->delete();

            // Delete reply comment
            $this->model->where('parent_comment_id', $commentId)
                ->delete();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
