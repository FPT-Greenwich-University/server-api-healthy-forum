<?php

namespace App\Http\Controllers\Api\Admins\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;
use App\Events\NotifyNewPost;

class PostController extends Controller
{
    private IPostRepository $postRespos;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postRespos = $postRepository;
    }

    /**
     * Get the posts not published
     *
     * @return JsonResponse
     */
    public function getPostsIsNotPublished(): JsonResponse
    {
        $perPage = 5;
        $posts = $this->postRespos->getPostsNotPublish($perPage);
        return response()->json($posts);
    }

    /**
     * Detail post by post id
     * @param $postID
     * @return JsonResponse
     */
    public function show($postID): JsonResponse
    {
        $post = $this->postRespos->getDetailPost($postID);

        if (is_null($post)) {
            return response()->json("Product not found", 404);
        }

        return response()->json($post);
    }

    /**
     * Admin accept post of doctor, update status published to true
     *
     * @param $postID Post's id need publish
     * @return JsonResponse
     */
    public function acceptPublishPost($postID): JsonResponse
    {
        $post = $this->postRespos->findById($postID);

        if ($post === null) {
            return response()->json("Post not found", 404);
        }

        $this->postRespos->update($postID, ['is_published' => true, 'published_at' => now()]);
        event(new NotifyNewPost($post));

        return response()->json("", 204);
    }
}
