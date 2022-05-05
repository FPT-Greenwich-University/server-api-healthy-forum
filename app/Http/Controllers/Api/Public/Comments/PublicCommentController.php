<?php

namespace App\Http\Controllers\Api\Public\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Exception;
use Illuminate\Http\JsonResponse;

class PublicCommentController extends Controller
{
    /**
     * Get the parent comment of the post
     *
     * @param $postID
     * @return JsonResponse
     */
    public function index($postID): JsonResponse
    {
        try {
            return response()->json(Comment::with(['user.image'])
                ->whereNull('parent_comment_id')
                ->where('post_id', $postID)
                ->orderBy('id', 'desc')
                ->paginate(5)
            );
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    public function getReplyComments($postID, $commentID): JsonResponse
    {
        try {
            return response()->json(
                Comment::where('post_id', '=', $postID)
                    ->where('parent_comment_id', '=', $commentID)
                    ->get());
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
