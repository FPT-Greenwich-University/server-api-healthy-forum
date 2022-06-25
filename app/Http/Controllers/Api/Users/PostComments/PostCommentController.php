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
     * @param $postID
     * @return JsonResponse
     */
    public function storePostComment(CreatePostCommentRequest $request, $postID): JsonResponse
    {
        $post = $this->postRepository->findById($postID);

        if (is_null($post)) return response()->json("Post not found", 404);

        $attributes = [
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
            'post_id' => $postID,
        ];
        $this->postRepository->storePostComment($postID, $attributes);
        return response()->json('Comment success');
    }

    /**
     * Create a new child comment into the post
     *
     * @param $postID
     * @param $commentID
     * @param ReplyPostCommentRequest $request
     * @return JsonResponse
     */
    public function replyPostComment($postID, $commentID, ReplyPostCommentRequest $request): JsonResponse
    {
        $post = $this->postRepository->findById($postID);
        $rootComment = $this->commentRepository->findById($commentID); // root comment of reply comment

        if (is_null($post) || is_null($rootComment)) return response()->json("Not found", 404);

        $attributes = [
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
            'post_id' => $postID,
            'parent_comment_id' => $request->input('parent_comment_id') // root comment id
        ];

        $this->postRepository->storePostComment($postID, $attributes);
        return response()->json("Create reply comment success", 201);
    }
}
