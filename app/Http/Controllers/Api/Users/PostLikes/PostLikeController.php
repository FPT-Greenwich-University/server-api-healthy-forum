<?php

namespace App\Http\Controllers\Api\Users\PostLikes;

use App\Http\Controllers\Controller;
use App\Models\PostLike;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    /**
     * User like the post
     *
     * @param Request $request
     * @param $postID --The post's id
     * @return JsonResponse
     */
    public function likeThePost(Request $request, int $postID): JsonResponse
    {
        try {
            $result = $this->checkLikeIsExist($request, $postID);

            if ($result === false) { // if user not have like this post
                PostLike::create([
                    'post_id' => $postID,
                    'user_id' => $request->user()->id,
                ]);
                return response()->json("Like post successful");
            } else {
                return response()->json("The user have like post before", 202);
            }
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'Code' => $exception->getCode(),
                'File' => $exception->getFile(),
                'Trace' => $exception->getTrace()
            ], 500);
        }
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
        $result = PostLike::where('post_id', $postID)->where('user_id', $request->user()->id)->first();

        if (!is_null($result)) {
            return true;
        }
        return false; // default
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
        try {
            $result = $this->checkLikeIsExist($request, $postID);

            if ($result === true) { // if user had liked this post
                PostLike::where('user_id', $request->user()->id)->where('post_id', $postID)->delete();
                return response()->json("Unlike post successful");
            } else {
                return response()->json("The user not have like this post before", 202);
            }
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'Code' => $exception->getCode(),
                'File' => $exception->getFile(),
                'Trace' => $exception->getTrace()
            ], 500);
        }
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
        try {
            $result = $this->checkLikeIsExist($request, $postID);
            if ($result === true) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'Code' => $exception->getCode(),
                'File' => $exception->getFile(),
                'Trace' => $exception->getTrace()
            ], 500);
        }
    }
}
