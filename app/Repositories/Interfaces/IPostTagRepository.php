<?php

namespace App\Repositories\Interfaces;

interface IPostTagRepository
{
    public function deletePostTags(int $postId);
}