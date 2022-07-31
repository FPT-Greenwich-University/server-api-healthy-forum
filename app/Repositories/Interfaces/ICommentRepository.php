<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface ICommentRepository extends IEloquentRepository
{
    public function getAllComments(int $postId, int $perPage);
    public function getDetail(int $postId, int $commentId);
    public function getReplyComments(int $postId, int $rootCommentId);
    public function updateComment(int $postId, int $commentId, array $attributes);
    public function deleteComment(int $postId, int $commentId);
}
