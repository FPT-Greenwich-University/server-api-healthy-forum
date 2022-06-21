<?php

namespace App\Http\Controllers\Api\Public\Comments;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PublicCommentController extends Controller
{
    private ICommentRepository $commentRepository;
    private IPostRepository $postRepository;

    public function __construct(ICommentRepository $commentRepository, IPostRepository $postRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Get the parent comment of the post
     *
     * @param $postID
     * @return JsonResponse
     */
    public function index($postID): JsonResponse
    {
        $post = $this->postRepository->findById($postID);

        if (is_null($post)) return response()->json("Post not found", 404);


        $perPage = 5; // Number item in once page
        $result = $this->commentRepository->getAllComments($postID, $perPage);

        return response()->json($result);

    }

    public function getReplyComments($postID, $commentID): JsonResponse
    {
        $post = $this->postRepository->findById($postID);
        $rootComment = $this->commentRepository->findById($commentID);

        if (is_null($post)) return response()->json("Post not found", 404);

        if (is_null($rootComment)) return response()->json("Root comment not found", 404);

        $result = $this->commentRepository->getReplyComments($postID, $commentID);

        return response()->json($result);
    }
}
