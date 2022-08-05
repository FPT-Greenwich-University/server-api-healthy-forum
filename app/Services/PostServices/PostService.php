<?php

namespace App\Services\PostServices;

use App\Models\Post;
use App\Models\PostTag;
use App\Repositories\Interfaces\IPostRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostService implements PostServiceInterface
{
    private readonly IPostRepository $postRepository;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param string $filePath file path of the image
     * @param Request $request
     * @return bool|string
     */
    public function createNewPost(string $filePath, Request $request)
    {
        try {
            $attributes = $request->only(['title', 'body', 'category_id', 'description']);
            $attributes['user_id'] = $request->user()->id; // assign user_id to array

            DB::beginTransaction();
            // Store a post
            $post = $this->postRepository->create($attributes);

            // Assign tag to post
            $this->postRepository->assignPostTags($post->id, $request->input('tags'));

            // Store path of image
            $this->postRepository->createPostImage($post->id, $filePath);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function updatePost(int $postId, Request $request)
    {
        try {
            $attributes = $request->only(['title', 'body', 'category_id', 'description']);

            DB::beginTransaction();
            $this->postRepository->updatePost($postId, $attributes); // Update post information
            $this->postRepository->updatePostTags($postId, $request->input('tags')); // Update post tags
            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * @param int $userId
     * @param int $postId
     * @param Request $request
     * @return bool|string
     */
    public function deletePost(int $userId, int $postId, Request $request)
    {
        try {
            $post = Post::find($postId);
            $currentUser = $request->user(); // get current user
            if (($userId != $currentUser->id)) return false;

            if ($currentUser->id == $post->user_id || $currentUser->hasRole('admin')) {
                DB::beginTransaction();
                PostTag::where("post_id", $postId)->delete();
                $post->comments()->delete();
                $post->favorites()->delete();
                $post->postLikes()->delete();
                $post->image()->delete(); // Delete image thumbnail
                $post->delete();
                DB::commit();
                return true;
            }

            return false;
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function updatePostView(int $postId)
    {
        return Post::where("id", $postId)->increment("total_view");
    }
}
