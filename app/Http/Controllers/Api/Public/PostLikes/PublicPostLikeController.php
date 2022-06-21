<?php

namespace App\Http\Controllers\Api\Public\PostLikes;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PublicPostLikeController extends Controller
{
    private IPostRepository $postRepository;
    private IPostLikeRepository $postLikeRepository;

    public function __construct(IPostLikeRepository $postLikeRepository, IPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        $this->postLikeRepository = $postLikeRepository;
    }

    /**
     * Get the total like of the post
     *
     * @param $postID
     * @return JsonResponse
     */
    public function getTotalLike($postID): JsonResponse
    {
        $post = $this->postRepository->findById($postID);

        if (is_null($post)) return response()->json("Post not found", 404);

        $totalLikes = $this->postLikeRepository->getTotalLike($postID);

        return response()->json($totalLikes);
    }
}
