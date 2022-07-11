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
     * @param $postId
     * @return JsonResponse
     */
    public function likeThePost(Request $request, int $postId): JsonResponse
    {
        $userId = $request->user()->id;

        $result = $this->checkLikeIsExist($userId, $postId); // Check if the like exitsed

        if ($result === true) return response()->json("", 204); // return no conntent if the like exitsed

        $this->postLikeRepository->create(['post_id' => $postId, 'user_id' => $userId]); // like the post

        return response()->json("Success", 201);
    }

    /**
     * Check if the like of user was had existed
     *
     * @param Request $request
     * @param integer $userId
     * @param integer $postId
     * @return bool true if result is null, false
     * otherwise.
     */
    private function checkLikeIsExist(int $userId, int $postId): bool
    {
        $result = $this->postLikeRepository->checkIsUserLikePost($postId, $userId); // Check the like is exitsed

        if (is_null($result)) return false; // The like not exitsed in the post by user

        return true;
    }

    /**
     * User unlike the post
     *
     * @param Request $request
     * @param int $postId
     * @return JsonResponse
     */
    public function unlikeThePost(Request $request, int $postId): JsonResponse
    {
        $user = $request->user(); // Get the auth current user
        $result = $this->checkLikeIsExist($user->id, $postId); // Check if user have like the post

        if ($result === false) return response()->json("Not found", 404);

        $this->postLikeRepository->deleteLike($user->id, $postId); // Unlike the post
        return response()->json("", 204);
    }

    /**
     * Get like status, true if user has like otherwise false
     *
     * @param Request $request
     * @param $postId
     * @return JsonResponse
     */
    public function checkUserLikePost(Request $request, $postId): JsonResponse
    {
        $userId = $request->user()->id; // Current user's id was authenticated
        $result = $this->checkLikeIsExist($userId, $postId);

        if ($result === true) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
}
