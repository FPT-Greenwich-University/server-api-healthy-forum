<?php

namespace App\Http\Controllers\Api\Admins\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
    public function getPostsMostLiked(): JsonResponse
    {
        $perPage = 5; // item post in one page
        $posts = $this->postLikeRepos->handleGetPostsMostLiked($perPage); // Get the posts have most liked

        return response()->json($posts);
    }
}
