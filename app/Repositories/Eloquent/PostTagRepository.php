<?php

namespace App\Repositories\Eloquent;

use App\Models\PostTag;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPostTagRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PostTagRepository extends BaseRepository implements IPostTagRepository
{
    public function __construct(PostTag $model)
    {
        parent::__construct($model);
    }

    public function deletePostTags(int $postId)
    {
        try {
            DB::beginTransaction();

            $this->model->where('post_id', $postId)->delete();

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
