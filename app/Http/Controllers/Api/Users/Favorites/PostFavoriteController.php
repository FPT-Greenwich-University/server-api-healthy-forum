<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoritePostRequest;
use App\Repositories\Interfaces\IFavoriteRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

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
     * @return JsonResponse
     */
    public function index(int $userId): JsonResponse
    {
        return response()->json($this->favoriteRepository->getListFavoritesPosts(userId: $userId, perPage: 5));
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
     * @param $userId
     * @param $postId
     * @return bool true if existed
     * otherwise false
     */
    public function checkIsPostFavoriteExist(int $userId, int $postId): bool
    {
        $favorite = $this->favoriteRepository->checkFavoriteExisted($userId, $postId, "App\Models\Post");

        if (is_null($favorite)) return false;

        return true;
    }

    /**
     * Check if the post exits in user favorite list
     * @param $userId
     * @param $doctorId
     * @return JsonResponse
     */
    public function checkUserFollow(int $userId, int $postId): JsonResponse
    {
        if ($this->checkIsPostFavoriteExist($userId, $postId) === true) return response()->json(true);

        return response()->json(false);
    }

    /**
     * Remove a post get out user's favorite post
     *
     * @param $favoriteID
     * @return JsonResponse
     */
    public function destroy(int $userId, int $postId): JsonResponse
    {
        $favorite = $this->favoriteRepository->getDetailFavorite($userId, $postId);

        if (is_null($favorite)) return response()->json("Resource not found", 404);

        $this->favoriteRepository->removeFavorite($userId, $postId);
        return response()->json("", 204);
    }
}