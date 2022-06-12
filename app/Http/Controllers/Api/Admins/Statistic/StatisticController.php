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
        try {
            return response()->json($this->postLikeRepos->handleGetPostsMostLiked(10));
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
