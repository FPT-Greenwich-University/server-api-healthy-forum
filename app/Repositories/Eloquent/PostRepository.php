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

    public function createPostImage(int $postId, string $filePath)
    {
        try {
            $post = $this->model->find($postId);

            $post->image()->create(['path' => $filePath]);
            return true;
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
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function assignPostTags(int $postId, array $tags)
    {
        try {
            return $this->model->find($postId)->tags()->attach($tags);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deletePost(int $postId)
    {
        try {
            $post = $this->model->find($postId);
            $post->comments()->delete();
            $post->postRatings()->delete();
            $post->favorites()->delete();
            $post->tags()->delete();
            $post->postLikes()->delete();
            $post->image()->delete(); // Delete image thumbnail
            $post->delete(); // delete the post
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function storePostComment(int $postId, array $attributes)
    {
        try {
            return $this->model->find($postId)->comments()->create($attributes);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function addFavoritePost(int $postId, int $userId)
    {
        try {
            $post = $this->model->find($postId);
            $post->favorites()->create(['user_id' => $userId]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
