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
     * @param $postID --The post's id
     * @return JsonResponse
     */
    public function likeThePost(Request $request, int $postID): JsonResponse
    {
        $result = $this->checkLikeIsExist($request, $postID);

        if ($result === true) return response()->json("", 204);

        $this->postLikeRepository->create([
            'post_id' => $postID,
            'user_id' => $request->user()->id
        ]);

        return response()->json("Like post success");
    }

    /**
     * Check if the like of user was had existed
     *
     * @param Request $request
     * @param int $postID
     * @return bool true if result is null, false
     * otherwise.
     */
    public function checkLikeIsExist(Request $request, int $postID): bool
    {
        $user = $request->user();
        $result = $this->postLikeRepository->checkIsUserLikePost($postID, $user->id);

        if (is_null($result)) return false;

        return true;
    }

    /**
     * User unlike the post
     *
     * @param Request $request
     * @param int $postID
     * @return JsonResponse
     */
    public function unlikeThePost(Request $request, int $postID): JsonResponse
    {
        $user = $request->user();
        $result = $this->checkLikeIsExist($request, $postID);

        if ($result === false) return response()->json("Not found", 404);

        $this->postLikeRepository->deleteLike($user->id, $postID);
        return response()->json("Unlike post successful");
    }

    /**
     * Get like status, true if user has like otherwise false
     *
     * @param Request $request
     * @param $postID
     * @return JsonResponse
     */
    public function checkUserLikePost(Request $request, $postID): JsonResponse
    {
        $result = $this->checkLikeIsExist($request, $postID);
        if ($result === true) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
}
