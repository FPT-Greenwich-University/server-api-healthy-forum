<?php

namespace App\Http\Controllers\Api\Admins\Statistic;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    /**
     * Get post by most like
     *
     * @return JsonResponse
     */
    public function getPostsMostLiked(): JsonResponse
    {
        try {
            $posts = DB::table('post_likes')
                ->join('posts', 'post_likes.post_id', '=', 'posts.id')
                ->selectRaw('count(post_likes.id as total_like, posts.*')
                ->groupBy('post_likes.post_id')
                ->orderBy('total_like')
                ->paginate(10);
            return response()->json($posts);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
