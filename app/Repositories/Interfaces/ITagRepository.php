<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface ITagRepository extends IEloquentRepository
{
    public function getPostTags(int $postId);
}
