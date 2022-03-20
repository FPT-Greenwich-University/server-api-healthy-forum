<?php

namespace App\Http\Controllers\Api\Users\PostRatings;

use App\Http\Controllers\Controller;
use App\Models\PostRating;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostRatingController extends Controller
{
    /**
     * User like the post
     *
     * @param Request $request
     * @param $postID --The post's id
     * @return JsonResponse
     */
    public function ratingThePost(Request $request, int $postID): JsonResponse
    {

        try {
            $result = $this->checkRatingIsExist($request, $postID);

            if ($result === false) { // if user not have rating this post
                PostRating::create([
                    'point' => $request->input('point'),
                    'user_id' => $request->user()->id,
                    'post_id' => $postID
                ]);
                return response()->json('Rating successful');
            } else {
                return response()->json("The user have rating post before", 202);
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
     * Check if the rating of user was had existed
     *
     * @param Request $request
     * @param int $postID
     * @return bool true if result is null, false
     * otherwise.
     */
    public function checkRatingIsExist(Request $request, int $postID): bool
    {
        $result = PostRating::where('post_id', $postID)->where('user_id', $request->user()->id)->first();

        if (!is_null($result)) {
            return true;
        }
        return false; // default
    }

    /**
     * Update rating the post of the user
     * @param Request $request
     * @param $postID
     * @return JsonResponse
     */
    public function updateRatingThePost(Request $request, $postID): JsonResponse
    {
        try {
            PostRating::where('user_id', $request->user()->id)
                ->where('post_id', $postID)
                ->update([
                    'point' => $request->input('point'),
                    'user_id' => $request->user()->id,
                    'post_id' => $postID
                ]);
            return response()->json("Update successful");
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
