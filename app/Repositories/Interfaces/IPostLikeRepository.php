<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IPostLikeRepository extends IEloquentRepository
{
    /**
     * @param int $per_page
     * @return mixed
     */
    public function handleGetPostsMostLiked(int $per_page): mixed;
    public function getTotalLike(int $postId);
    public function checkIsUserLikePost(int $postId, int $userId);
    public function deleteLike(int $userId, int $postId);
}
