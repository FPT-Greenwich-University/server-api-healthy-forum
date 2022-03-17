<?php

namespace App\Http\Controllers\Api\Public\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Exception;
use Illuminate\Http\JsonResponse;

class PublicCommentController extends Controller
{
    /**
     * @param $postID
     * @return JsonResponse
     */
    public function index($postID): JsonResponse
    {
        try {
            $comments = Comment::where('post_id', $postID)->paginate(10);
            return response()->json($comments);
        } catch (Exception $exception) {
            return response()->json($exception, 500);
        }
    }
}
