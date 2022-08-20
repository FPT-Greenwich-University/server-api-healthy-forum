<?php

namespace App\Http\Controllers\Api;

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
    final public function index(int $postId): JsonResponse
    {
        if (is_null($this->postRepository->findById($postId))) {
            return response()->json("Post not found", 404);
        }

        return response()->json($this->commentRepository->getAllComments(postId: $postId, perPage: 5));
    }

    /**
     * Get the reply comment
     *
     * @param integer $postId
     * @param integer $commentId
     * @return JsonResponse
     */
    final public function getReplyComments(int $postId, int $commentId): JsonResponse
    {
        if (is_null($this->postRepository->findById($postId))) {
            return response()->json("Post not found", 404);
        }

        if (is_null($this->commentRepository->findById($commentId))) {
            return response()->json("Root comment not found", 404);
        }

        // Get the reply comment base on the comment id
        return response()->json($this->commentRepository->getReplyComments($postId, $commentId));
    }
}
