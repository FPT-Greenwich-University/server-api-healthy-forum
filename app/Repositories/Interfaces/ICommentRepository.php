<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface ICommentRepository extends IEloquentRepository
{
    public function getAllComments(int $postId, int $perPage);
    public function getReplyComments(int $postId, int $rootCommentId);
}
