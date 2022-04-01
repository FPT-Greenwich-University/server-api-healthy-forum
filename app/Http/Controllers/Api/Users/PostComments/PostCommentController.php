<?php

namespace App\Http\Controllers\Api\Users\PostComments;

use App\Http\Controllers\Api\Users\Post\PostController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\Comment\CreateChildPostCommentRequest;
use App\Http\Requests\Api\Post\Comment\CreatePostCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class PostCommentController extends Controller
{
    /**
     * Create a new comment into the post
     *
     * @param CreatePostCommentRequest $request
     * @param $postID
     * @return JsonResponse
     */
    public function storePostComment(CreatePostCommentRequest $request, $postID): JsonResponse
    {
        try {
            $post = Post::findOrFail($postID); // if post not found then return 404 error
            $post->comments()->create([ // insert using relationship
                'content' => $request->input('content'),
                'user_id' => $request->user()->id,
                'post_id' => $postID,
                'parent_comment_id' => $request->input('parent_comment_id')
            ]);
            return response()->json('Create new comment success');

        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Create a new child comment into the post
     *
     * @param CreateChildPostCommentRequest $request
     * @param $postID
     * @return JsonResponse
     */
    public function storeChildPostComment(CreateChildPostCommentRequest $request, $postID): JsonResponse
    {
        try {
            $post = PostController::findOrFail($postID); // if post not found then return 404 error json

            if ($this->checkCommentExist($request->input('parent_comment_id')) === true) { // check if comment parent is exist
                $post->comments()->create([
                    'content' => $request->input('content'),
                    'user_id' => $request->user()->id,
                    'post_id' => $postID,
                    'parent_comment_id' => $request->input('parent_comment_id')
                ]);

                return response()->json('Create new comment success');

            } else {
                return response()->json('The post not found', 404);
            }

        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Check the comment exist
     * @param $commentID
     * @return bool --true if the comment exist, otherwise false
     */
    public static function checkCommentExist($commentID): bool
    {
        $comment = Comment::find($commentID);
        if (is_null($comment)) {
            return false;
        }
        return true;
    }

}
