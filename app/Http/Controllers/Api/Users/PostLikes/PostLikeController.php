<?php

namespace App\Http\Controllers\Api\Users\PostLikes;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostLikeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    private IPostLikeRepository $postLikeRepository;

    public function __construct(IPostLikeRepository $postLikeRepository)
    {
        $this->postLikeRepository = $postLikeRepository;
    }

    /**
     * User like the post
     *
     * @param Request $request
     * @param int $postId
     * @return JsonResponse
     */
    final public function likeThePost(Request $request, int $postId): JsonResponse
    {
        $userId = $request->user()->id;

        // Check if the like existed
        if ($this->checkLikeIsExist($userId, $postId) === true) {
            return response()->json("", 204);
        }

        $this->postLikeRepository->create(['post_id' => $postId, 'user_id' => $userId]); // Handle like the post

        return response()->json("Like Success", 201);
    }

    /**
     * Check if the like of user was had existed
     *
     * @param integer $userId
     * @param integer $postId
     * @return bool true if result is null, false
     * otherwise.
     */
    private function checkLikeIsExist(int $userId, int $postId): bool
    {
        // Check the like is exited
        // The like not existed in the post by user
        return !is_null($this->postLikeRepository->checkIsUserLikePost(postId: $postId, userId: $userId));
    }

    /**
     * User unlike the post
     *
     * @param Request $request
     * @param int $postId
     * @return JsonResponse
     */
    final public function unlikeThePost(Request $request, int $postId): JsonResponse
    {
        $user = $request->user(); // Get the auth current user
        // Check if user have like the post

        if ($this->checkLikeIsExist(userId: $user->id, postId: $postId) === false) {
            return response()->json("Not found", 404);
        }

        $this->postLikeRepository->deleteLike($user->id, $postId); // Unlike the post
        return response()->json("", 204);
    }

    /**
     * Get like status, true if user has like otherwise false
     *
     * @param Request $request
     * @param int $postId
     * @return JsonResponse
     */
    final public function checkUserLikePost(Request $request, int $postId): JsonResponse
    {
        return $this->checkLikeIsExist(userId: $request->user()->id, postId: $postId) === true ? response()->json(true) : response()->json(false);
    }
}
