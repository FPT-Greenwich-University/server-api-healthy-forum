<?php

namespace App\Http\Controllers\Api\Users\PostComments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\Comment\CreatePostCommentRequest;
use App\Http\Requests\Api\Post\Comment\EditCommentRequest;
use App\Http\Requests\Api\Post\Comment\ReplyPostCommentRequest;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PostCommentController extends Controller
{
    private IPostRepository $postRepository;
    private ICommentRepository $commentRepository;

    public function __construct(IPostRepository $postRepository, ICommentRepository $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Create a new child comment into the post
     *
     * @param int $postId
     * @param int $commentId
     * @param ReplyPostCommentRequest $request
     * @return JsonResponse
     */
    public function replyPostComment(int $postId, int $commentId, ReplyPostCommentRequest $request): JsonResponse
    {
        // If not found then return 404 HTTP
        if ($this->checkExistedComment($postId, $commentId) === false) {
            return response()->json("Not found", 404);
        }

        $attributes = [
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
            'post_id' => $postId,
            'parent_comment_id' => $commentId // root comment id
        ];

        $this->postRepository->storePostComment($postId, $attributes);
        return response()->json("Success", 201);
    }

    /**
     * <p>Check the comment of the post is exist</p>
     *
     * @param int $postId
     * @param int $commentId
     * @return bool Returns <b>TRUE</b> if comment is exits
     * Otherwise <b>false</b></p>
     */
    private function checkExistedComment(int $postId, int $commentId): bool
    {
        $existedPost = $this->postRepository->findById($postId); // Find the post
        $existedComment = $this->commentRepository->findById($commentId); // root comment of reply comment

        if (is_null($existedPost) || is_null($existedComment)) { // If one of them not found then return false
            return false;
        }

        return true;
    }

    /**
     * Create a new comment into the post
     *
     * @param CreatePostCommentRequest $request
     * @param int $postId
     * @return JsonResponse
     */
    public function storePostComment(CreatePostCommentRequest $request, int $postId): JsonResponse
    {
        $post = $this->postRepository->findById($postId);

        if (is_null($post)) return response()->json("Post not found", 404);

        $attributes = [
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
            'post_id' => $postId,
        ];

        $this->postRepository->storePostComment($postId, $attributes); // Store new comment into the post
        return response()->json('Success', 201);
    }

    /**
     * <p>Update the comment content</p>
     *
     * @param int $postId
     * @param int $commentId
     * @param EditCommentRequest $request
     * @return JsonResponse
     */
    public function updateComment(int $postId, int $commentId, EditCommentRequest $request): JsonResponse
    {
        $currentUser = $request->user();

        // If not found then return 404 HTTP
        if ($this->checkExistedComment($postId, $commentId) === false) {
            return response()->json("Not found", 404);
        }

        // If user not own comment then return 400 HTTP
        if ($this->checkIsOwnComment($currentUser->id, $commentId) === false) {
            return response()->json("Bad request", 400);
        }

        $attributes = [
            'content' => $request->input('content'),
            'post_id' => $postId,
        ];

        $this->commentRepository->updateComment($postId, $commentId, $attributes); // Handle update comment content
        return response()->json("", 204);
    }

    /**
     * Check the current user is owner the comment?
     *
     * @param int $userId
     * @param int $commentId
     * @return bool <p>Return <b>True</b> if comment belong to user.</p>
     * <p>Otherwise <b>False</b></p>
     */
    private function checkIsOwnComment(int $userId, int $commentId): bool
    {
        $existedComment = $this->commentRepository->findById($commentId); // Find the comment

        if ($userId !== $existedComment->user_id) {
            return false;
        }

        return true;
    }
}
