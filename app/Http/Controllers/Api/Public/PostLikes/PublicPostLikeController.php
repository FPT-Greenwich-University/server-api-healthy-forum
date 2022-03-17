<?php

namespace App\Http\Controllers\Api\Public\PostLikes;

use App\Http\Controllers\Controller;
use App\Models\PostLike;
use Exception;

class PublicPostLikeController extends Controller
{
    public function index($postID)
    {
        try {
            $likes = PostLike::where('post_id', $postID)->count();
            return response()->json(['total_likes' => $likes]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
