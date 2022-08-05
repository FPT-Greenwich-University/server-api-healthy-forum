<?php

namespace App\Http\Controllers\Api\Comments;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private readonly ICommentRepository $commentRepository;
    private readonly IPostRepository $postRepository;

    public function __construct(ICommentRepository $commentRepository, IPostRepository $postRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Get the parent comment of the post
     *
     * @param integer $postId
     * @return JsonResponse
     */
    public function index(int $postId): JsonResponse
    {
        $post = $this->postRepository->findById($postId); // Get the post

        if (is_null($post)) return response()->json("Post not found", 404);

        $result = $this->commentRepository->getAllComments(postId: $postId, perPage: 5);

        return response()->json($result);
    }

    /**
     * Get the reply comment
     *
     * @param integer $postId
     * @param integer $commentId
     * @return JsonResponse
     */
    public function getReplyComments(int $postId, int $commentId): JsonResponse
    {
        $post = $this->postRepository->findById($postId); // Get the post
        $rootComment = $this->commentRepository->findById($commentId); // Get the comment

        if (is_null($post)) return response()->json("Post not found", 404);

        if (is_null($rootComment)) return response()->json("Root comment not found", 404);

        $result = $this->commentRepository->getReplyComments($postId, $commentId); // Get the reply comment base on the comment id

        return response()->json($result);
    }
}