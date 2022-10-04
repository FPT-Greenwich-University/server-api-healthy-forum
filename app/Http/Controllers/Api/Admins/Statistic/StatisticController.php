<?php

namespace App\Http\Controllers\Api\Admins\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use Illuminate\Http\JsonResponse;

class StatisticController extends Controller
{
    private readonly IPostLikeRepository $postLikeRepos;

    public function __construct(IPostLikeRepository $postLikeRepository)
    {
        $this->postLikeRepos = $postLikeRepository;
    }

    /**
     * Get post by most like
     *
     * @return JsonResponse
     */
    final public function getPostsMostLiked(): JsonResponse
    {
        // Get the posts have most liked, total 5 item in one page
        return response()->json($this->postLikeRepos->handleGetPostsMostLiked(perPage: 5));
    }
}
