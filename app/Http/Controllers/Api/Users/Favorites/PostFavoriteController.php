<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoritePostRequest;
use App\Models\Favorite;
use App\Repositories\Interfaces\IFavoriteRepository;
use App\Repositories\Interfaces\IPostRepository;
use Exception;
use GuzzleHttp\Promise\Is;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostFavoriteController extends Controller
{
    private IFavoriteRepository $favoriteRepository;
    private IPostRepository $postRepository;
    public function __construct(IFavoriteRepository $favoriteRepository, IPostRepository $postRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
        $this->postRepository = $postRepository;
    }
    /**
     * User get own list favorite posts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index($userID): JsonResponse
    {
        $perPage = 5;
        return response()->json($this->favoriteRepository->getListFavoritesPosts($userID, $perPage));
    }

    /**
     * Add post to the favorite list of user
     *
     * @param StoreFavoritePostRequest $request
     * @return JsonResponse
     */
    public function store(StoreFavoritePostRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $postId = $request->input('post_id');

        if ($this->checkIsPostFavoriteExist($userId, $postId) === false) { // check if post have exits in user's favorite post
            $post = $this->postRepository->findById($postId);
            if (is_null($post)) return response()->json("Post not found", 404);

            $this->postRepository->addFavoritePost($post->id, $userId);
            return response()->json('Add post to the favorite successfully', 201);
        }

        return response()->json('', 204);
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
        $favorite = $this->favoriteRepository->checkFavoriteExisted($userID, $postID, "App\Models\Post");

        if (is_null($favorite)) return false;

        return true;
    }

    /**
     * Check if the post exits in user favorite list
     * @param $userID
     * @param $doctorID
     * @return JsonResponse
     */
    public function checkUserFollow($userID, $postID): JsonResponse
    {
        if ($this->checkIsPostFavoriteExist($userID, $postID) === true) return response()->json(true);

        return response()->json(false);
    }

    /**
     * Remove a post get out user's favorite post
     *
     * @param $favoriteID
     * @return JsonResponse
     */
    public function destroy($userID, $postID): JsonResponse
    {
        $favorite = $this->favoriteRepository->getDetailFavorite($userID, $postID);

        if (is_null($favorite)) return response()->json("Resource not found", 404);

        $this->favoriteRepository->removeFavorite($userID, $postID);
        return response()->json("", 204);
    }
}
