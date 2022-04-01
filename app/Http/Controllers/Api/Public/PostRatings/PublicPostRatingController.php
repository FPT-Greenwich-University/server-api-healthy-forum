<?php

namespace App\Http\Controllers\Api\Public\PostRatings;

use App\Http\Controllers\Controller;
use App\Models\PostRating;
use Exception;
use Illuminate\Http\JsonResponse;

class PublicPostRatingController extends Controller
{
    /**
     * Get average of the ratings of the post
     *
     * @param $postID post's id of the post table
     * @return JsonResponse
     */
    public function getAveragePostRating($postID): JsonResponse
    {
        try {
            $avgRatings = PostRating::where('post_id', $postID)->avg('point');
            return response()->json(['avgRatingPoint' => $avgRatings]);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
