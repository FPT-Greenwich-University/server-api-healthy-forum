<?php

namespace App\Http\Controllers\Api\Users\PostComments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\Comment\ReplyPostCommentRequest;
use App\Http\Requests\Api\Post\Comment\CreatePostCommentRequest;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PostCommentController extends Controller
{
    private IPostRepository $postRepository;
    private ICommentRepository $commentRepository;

    public function __construct(IPostRepository $postRepository, ICommentRepository $commentRepository)
    {
        $this->postRepository =  $postRepository;
        $this->commentRepository = $commentRepository;
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

        $this->postRepository->storePostComment($postId, $attributes);
        return response()->json('Success', 201);
    }

    /**
     * Create a new child comment into the post
     *
     * @param $postId
     * @param $commentId
     * @param ReplyPostCommentRequest $request
     * @return JsonResponse
     */
    public function replyPostComment(int $postId, int $commentId, ReplyPostCommentRequest $request): JsonResponse
    {
        $post = $this->postRepository->findById($postId);
        $rootComment = $this->commentRepository->findById($commentId); // root comment of reply comment

        if (is_null($post) || is_null($rootComment)) return response()->json("Not found", 404);

        $attributes = [
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
            'post_id' => $postId,
            'parent_comment_id' => $commentId // root comment id
        ];

        $this->postRepository->storePostComment($postId, $attributes);
        return response()->json("Success", 201);
    }
}
