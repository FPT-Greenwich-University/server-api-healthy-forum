<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPostRepository;
use Exception;

class PostRepository extends BaseRepository implements IPostRepository
{

    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function getPosts(int $per_page)
    {
        try {
            return $this->model->with(['image', 'category', 'user'])->isPublished()->orderBy('id', 'desc')->paginate($per_page);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getPostsNotPublish(int $per_page)
    {
        try {
            return $this->model->with(['image', 'category', 'user'])->isNotPublished()->orderBy('id', 'desc')->paginate($per_page);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getDetailPost(int $id)
    {
        try {
            return $this->model->with(['image', 'category', 'user'])->isPublished()->find($id);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getPostsByTag(int $tagId, int $perPage)
    {
        try {
            $listPostIds = $this->getListPostIdByTag($tagId);
            return Post::with(['image', 'category', 'user', 'tags'])
                ->whereIn('posts.id', $listPostIds)
                ->isPublished()
                ->orderBy('posts.id', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getListPostIdByTag(int $tagId)
    {
        try {
            return $this->model->tag($tagId)->pluck('posts.id');
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getPostsByCategory(int $categoryId, int $perPage)
    {
        try {
            $listPostIds = $this->getListPostIdByCategory($categoryId);
            return Post::with(['image', 'category', 'user'])
                ->whereIn('posts.id', $listPostIds)
                ->orderBy('posts.id', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getListPostIdByCategory(int $categoryId)
    {
        try {
            return $this->model->where('category_id', '=', $categoryId)->pluck('id');
        } catch (Exception $exception) {
            return $exception->getMessage();
            // logger()->error($exception->getMessage());
        }
    }

    public function updateStatusPost(int $postId, array $attributes)
    {
        try {
            // dd($attributes);
            $post = parent::findById($postId);

            if (is_null($post)) return false;

            $post->update($attributes);
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
            // logger()->error($exception->getMessage());
            // return false;
        }
    }

    public function searchPosts(string $title, int $perPage)
    {
        try {
            return $this->model->with(['image', 'category', 'user'])
                ->isPublished()
                ->where('title', 'like', '%' . $title . '%')
                ->paginate($perPage)
                ->appends(['title' => $title]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getPostsByUser(int $userId)
    {
        try {
            return $this->model->with(['image'])
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->take(3)
                ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getDetailPostByUser(int $userId, int $postId)
    {
        try {
            return $this->model->with(['image', 'category', 'user'])
                ->where('user_id', $userId)
                ->find($postId);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updatePostTags(int $postId, array $tags)
    {
        try {
            return $this->model->find($postId)->tags()->sync($tags);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }


    public function updatePost($postId, array $attributes)
    {
        try {
            return $this->model->find($postId)->update($attributes);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updatePostImage($postId, string $filePath)
    {
        try {
            $post = $this->model->find($postId);

            $post->image()->delete(); // delete old path
            $post->image()->create(['path' => $filePath]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
