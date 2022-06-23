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
    public function index($userID): JsonResponse
    {
        try {
            $posts = Favorite::where('favorites.user_id', '=', $userID)
                ->where('favoriteable_type', 'App\Models\Post')
                ->join('posts', 'favorites.favoriteable_id', 'posts.id')
                ->join('users', 'posts.user_id', 'users.id')
                ->orderBy('favorites.id', 'desc')
                ->select('posts.id', 'posts.title', 'users.email as userEmail', 'users.id as userId', 'posts.description')
                ->paginate(2);
            return response()->json($posts);
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
     * Check if the post exits in user favorite list
     * @param $userID
     * @param $doctorID
     * @return JsonResponse
     */
    public function checkUserFollow($userID, $postID): JsonResponse
    {
        try {
            if ($this->checkIsPostFavoriteExist($userID, $postID) === true) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }

        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Remove a post get out user's favorite post
     *
     * @param $favoriteID
     * @return JsonResponse
     */
    public function destroy($userID, $postID): JsonResponse
    {
        try {
            $favorite = Favorite::where('user_id', '=', $userID)
                ->where('favoriteable_id', '=', $postID)
                ->first();

            if (!is_null($favorite)) {
                $favorite->delete();
            } else {
                throw new ModelNotFoundException('The post not found in system');
            }

            return response()->json('Remove doctor from favorite list success');
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
