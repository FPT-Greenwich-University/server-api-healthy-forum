<?php

namespace App\Http\Controllers\Api\Public\PostLikes;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PublicPostLikeController extends Controller
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
    public function getTotalLike(int $postId): JsonResponse
    {
        $post = $this->postRepository->findById($postId); // Get the post

        if (is_null($post)) return response()->json("Post not found", 404); // If post is not exits return not found http

        $totalLikes = $this->postLikeRepository->getTotalLike($postId); // Get the total like of the post

        return response()->json($totalLikes);
    }
}
