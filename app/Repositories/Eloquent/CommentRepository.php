<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\ICommentRepository;
use App\Models\Comment;
use Exception;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function getAllComments(int $postId, int $perPage)
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

    public function getReplyComments(int $postId, int $rootcommentId)
    {
        try {
            return $this->model->with(['user.image'])
                ->where('post_id', '=', $postId)
                ->where('parent_comment_id', '=', $rootcommentId)
                ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updateComment(int $postId, int $commentId, array $attributes)
    {
        try {
           $this->model->where('post_id', $postId)
                       ->where('id', $commentId)
                       ->update($attributes);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
