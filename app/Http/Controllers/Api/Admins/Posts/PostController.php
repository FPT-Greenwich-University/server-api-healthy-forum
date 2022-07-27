<?php

namespace App\Http\Controllers\Api\Admins\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;
use App\Events\NotifyNewPost;

class PostController extends Controller
{
    private readonly IPostRepository $postResponse;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postResponse = $postRepository;
    }

    /**
     * Get the posts not published
     *
     * @return JsonResponse
     */
    public function getPostsIsNotPublished(): JsonResponse
    {
        $perPage = 5; // the total post item in one page
        $posts = $this->postResponse->getPostsNotPublish($perPage); // Get the posts have not publihed yet
        return response()->json($posts);
    }

    /**
     * Detail post by post id
     * @param int $postId
     * @return JsonResponse
     */
    public function show(int $postId): JsonResponse
    {
        $post = $this->postResponse->getDetailPost($postId); // find the post

        if (is_null($post)) return response()->json("Product not found", 404);

        return response()->json($post); // return post detail information
    }

    /**
     * Admin accept post of doctor, update status published to true
     *
     * @param int $postId Post's id need publish
     * @return JsonResponse
     */
    public function acceptPublishPost(int $postId): JsonResponse
    {
        $post = $this->postResponse->findById($postId); // find the post

        if (is_null($post)) return response()->json("Post not found", 404); // return http status not found

        $this->postResponse->update($postId, ['is_published' => true, 'published_at' => now()]); // update publlished status

        event(new NotifyNewPost($post)); // throw event for notifacation new post to all user via email

        return response()->json("", 204);
    }
}
