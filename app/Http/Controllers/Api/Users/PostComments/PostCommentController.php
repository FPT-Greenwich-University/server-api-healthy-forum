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
     * Get detail comment
     *
     * @param int $postId
     * @param int $commentId
     * @return JsonResponse
     */
    final public function getDetailComment(int $postId, int $commentId): JsonResponse
    {
        // If not found then return 404 HTTP
        if ($this->checkExistedComment($postId, $commentId) === false) {
            return response()->json("Not found", 404);
        }

        return response()->json($this->commentRepository->getDetail($postId, $commentId));
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

        // False if post or comment not existed
        return !(is_null($existedPost) || is_null($existedComment));
    }

    /**
     * Create a new child comment into the post
     *
     * @param int $postId
     * @param int $commentId
     * @param ReplyPostCommentRequest $request
     * @return JsonResponse
     */
    final public function replyPostComment(int $postId, int $commentId, ReplyPostCommentRequest $request): JsonResponse
    {
        // If not found then return 404 HTTP
        if ($this->checkExistedComment($postId, $commentId) === false) {
            return response()->json("Not found", 404);
        }

        $this->postRepository->storePostComment(postId: $postId, attributes: ['content' => $request->input('content'), 'user_id' => $request->user()->id, 'post_id' => $postId, 'parent_comment_id' => $commentId]);

        return response()->json("Saving reply comment success", 201);
    }

    /**
     * Create a new comment into the post
     *
     * @param CreatePostCommentRequest $request
     * @param int $postId
     * @return JsonResponse
     */
    final public function storePostComment(CreatePostCommentRequest $request, int $postId): JsonResponse
    {
        if (is_null($this->postRepository->findById($postId))) {
            return response()->json("Post not found", 404);
        }

        $this->postRepository->storePostComment(postId: $postId, attributes: ['content' => $request->input('content'), 'user_id' => $request->user()->id, 'post_id' => $postId,]); // Store new comment into the post

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
    final public function updateComment(int $postId, int $commentId, EditCommentRequest $request): JsonResponse
    {
        // If not found then return 404 HTTP
        if ($this->checkExistedComment($postId, $commentId) === false) {
            return response()->json("Not found", 404);
        }

        // If user not own comment then return 400 HTTP
        if ($this->checkIsOwnComment($request->user()->id, $commentId) === false) {
            return response()->json("Bad request", 400);
        }

        // Handle update comment content
        $this->commentRepository->updateComment(postId: $postId, commentId: $commentId, attributes: ['content' => $request->input('content'), 'post_id' => $postId]);

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

        // Return TRUE if user own comment
        return $userId === $existedComment->user_id;
    }

    /**
     * Delete comment and reply comment
     *
     * @param int $postId
     * @param int $commentId
     * @return JsonResponse
     */
    final public function deleteComment(int $postId, int $commentId): JsonResponse
    {
        if ($this->checkExistedComment($postId, $commentId) === false) {
            return response()->json("Not found", 404);
        }

        $this->commentRepository->deleteComment($postId, $commentId);
        return response()->json("", 204);
    }
}
