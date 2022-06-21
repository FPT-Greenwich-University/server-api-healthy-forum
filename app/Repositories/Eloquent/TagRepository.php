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


    public function getPostTags(int $postId)
    {
        try {
            return $this->model->join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
                ->join('posts', function ($join) use ($postId) {
                    $join->on('post_tag.post_id', '=', 'posts.id')
                        ->where('posts.id', '=', $postId);
                })
                ->select('tags.*')
                ->get();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }
}
