<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PostLikeController extends Controller
{
    private readonly IPostRepository $postRepository;
    private readonly IPostLikeRepository $postLikeRepository;

    public function __construct(IPostLikeRepository $postLikeRepository, IPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->postLikeRepository = $postLikeRepository;
    }

    /**
     * Get the total like of the post
     *
     * @param integer $postId
     * @return JsonResponse
     */
    final public function getTotalLike(int $postId): JsonResponse
    {
        if (is_null($this->postRepository->findById($postId))) {
            return response()->json("Post not found", 404); // If post is not exits return not found http
        }

        // Get the total like of the post
        return response()->json($this->postLikeRepository->getTotalLike($postId));
    }
}
