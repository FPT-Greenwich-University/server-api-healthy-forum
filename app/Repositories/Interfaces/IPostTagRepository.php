<?php

namespace App\Repositories\Interfaces;

interface IPostTagRepository
{
    /**
     * Delete post tags
     *
     * @param int $postId
     * @return mixed
     */
    public function deletePostTags(int $postId);
}
