<?php

namespace App\Repositories\Interfaces;

interface IPostLikeRepository
{
    /**
     * @param int $per_page
     * @return mixed
     */
    public function handleGetPostsMostLiked(int $per_page): mixed;

    public function getTotalLike(int $postId);
}
