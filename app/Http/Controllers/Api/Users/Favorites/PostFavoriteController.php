<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoritePostRequest;
use App\Models\Favorite;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostFavoriteController extends Controller
{

    /**
     * User get own list favorite posts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userID = $request->user()->id;
            $favoritePosts = Favorite::where('user_id', $userID)
                                     ->where('favoriteable_type', 'App\Models\Post')
                                     ->paginate(10);
            return response()->json($favoritePosts);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Add post to the favorite list of user
     *
     * @param StoreFavoritePostRequest $request
     * @return JsonResponse
     */
    public function store(StoreFavoritePostRequest $request): JsonResponse
    {
        try {
            $userID = $request->user()->id;
            $postID = $request->input('post_id');

            if ($this->checkIsPostFavoriteExist($userID, $postID) === false) { // check if post have exits in user's favorite post
                $post = Post::findOrFail($request->input('post_id')); // check the post is exits, return 404 if post not found
                $post->favorites()->create(['user_id' => $request->user()->id]);

                return response()->json('Add post to the favorite successfully');
            }

            // Default
            return response()->json('The post have existed in favorite list', 202);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Check post have existed in the favorite list
     *
     * @param $userID --User id
     * @param $postID --Post id
     * @return bool true if existed
     * otherwise false
     */
    public function checkIsPostFavoriteExist($userID, $postID): bool
    {
        $favoritePost = Favorite::where('user_id', $userID)
            ->where('favoriteable_id', $postID)
            ->where('favoriteable_type', 'App\Models\Post')
            ->first();

        if (is_null($favoritePost)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Remove a post get out user's favorite post
     *
     * @param $favoritePostID
     * @return JsonResponse
     */
    public function destroy($favoritePostID): JsonResponse
    {
        try {
            Favorite::findOrFail($favoritePostID); // if not found favorite item then return 404 json error
            Favorite::destroy($favoritePostID);
            return response()->json('Remove the post get out favorite list successfully');
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
