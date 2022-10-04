<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IPostTagRepository extends IEloquentRepository
{
    /**
     * Delete post tags
     *
     * @param int $postId
     * @return mixed
     */
    public function deletePostTags(int $postId);
}