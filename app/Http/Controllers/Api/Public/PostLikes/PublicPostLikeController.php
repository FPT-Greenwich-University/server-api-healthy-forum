<?php

namespace App\Http\Controllers\Api\Public\PostLikes;

use App\Http\Controllers\Controller;
use App\Models\PostLike;
use Exception;
use Illuminate\Http\JsonResponse;

class PublicPostLikeController extends Controller
{
    /**
     * Get the total like of the post
     *
     * @param $postID
     * @return JsonResponse
     */
    public function getTotalLike($postID): JsonResponse
    {
        try {
            $total_likes = PostLike::where('post_id', $postID)->count();
            return response()->json(['total_likes' => $total_likes]);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
