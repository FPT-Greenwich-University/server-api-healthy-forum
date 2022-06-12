<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\ITagRepository;

class TagRepository extends BaseRepository implements ITagRepository
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }
}
