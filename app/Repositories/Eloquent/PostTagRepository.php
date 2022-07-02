<?php

namespace App\Repositories\Eloquent;

use App\Models\PostTag;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPostTagRepository;
use Exception;

class PostTagRepository extends BaseRepository implements IPostTagRepository
{
    public function __construct(PostTag $model)
    {
        parent::__construct($model);
    }

    public function deletePostTags(int $postId)
    {
        try {
            $this->model->where("post_id", "=", $postId)->delete();
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
