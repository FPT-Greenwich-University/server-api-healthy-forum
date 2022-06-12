<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;

class PostRepository extends BaseRepository implements IPostRepository
{

    private ITagRepository $tagRepo;
    public function __construct(Post $model, ITagRepository $tagRepository)
    {
        parent::__construct($model);
        $this->tagRepo = $tagRepository;
    }


    public function getPosts(int $per_page)
    {
        return $this->model->with(['image', 'category', 'user'])->isPublished()->orderBy('id', 'desc')->paginate($per_page);
    }
    public function getPostsNotPublish(int $per_page)
    {
        return $this->model->with(['image', 'category', 'user'])->isNotPublished()->orderBy('id', 'desc')->paginate($per_page);
    }

    public function getDetailPost(int $id)
    {
        return $this->model->with(['image', 'category', 'user'])->isPublished()->findOrFail($id);
    }

    public function getPostsByTag(int $tagId)
    {
        $listPostIds = $this->getListPostIdByTag($tagId);
        return Post::with(['image', 'category', 'user', 'tags'])
                   ->whereIn('posts.id', $listPostIds)
                   ->isPublished()
                   ->orderBy('posts.id', 'desc')
                   ->paginate(5);
    }

    public function getListPostIdByTag(int $tagId)
    {
        return $this->model->tag($tagId)->pluck('posts.id');
    }

    public function getPostsByCategory(int $categoryId)
    {
        $listPostIds = $this->getListPostIdByCategory($categoryId);
        return Post::with(['image', 'category', 'user'])
            ->whereIn('posts.id', $listPostIds)
            ->orderBy('posts.id', 'desc')
            ->paginate(5);
    }

    public function getListPostIdByCategory(int $categoryId)
    {
        return $this->model->where('category_id', '=', $categoryId)->pluck('id');
    }

}
