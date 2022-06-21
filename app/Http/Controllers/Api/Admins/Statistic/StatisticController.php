<?php

namespace App\Http\Controllers\Api\Admins\Statistic;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    private IPostLikeRepository $postLikeRepos;
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
        $perPage = 5;
        $result = $this->postLikeRepos->handleGetPostsMostLiked($perPage);

        return response()->json($result);
    }
}
