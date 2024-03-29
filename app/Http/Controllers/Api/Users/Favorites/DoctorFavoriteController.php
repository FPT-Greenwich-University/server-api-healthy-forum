<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoriteDoctorRequest;
use App\Repositories\Interfaces\IFavoriteRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class DoctorFavoriteController extends Controller
{
    private readonly IFavoriteRepository $favoriteRepository;
    private readonly IUserRepository $userRepository;

    public function __construct(IFavoriteRepository $favoriteRepository, IUserRepository $userRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * User get own list favorite doctors
     *
     * @param integer $userId
     * @return JsonResponse
     */
    final public function index(int $userId): JsonResponse
    {
        return response()->json($this->favoriteRepository->getListFavoritesDoctors(userId: $userId, perPage: 5));
    }

    /**
     * Add post to the favorite list of user
     *
     * @param StoreFavoriteDoctorRequest $request
     * @return JsonResponse
     */
    final public function addFavoriteItem(StoreFavoriteDoctorRequest $request): JsonResponse
    {
        $user = $request->user(); // Retrieve the current user authenticated

        $doctorId = (int)($request->input('doctor_id')); // Retrieve doctor id from http request

        // The user can't add themselves to themselves favorite list
        if ($doctorId === $user->id) {
            return response()->json("Bad request", 400);
        }

        if ($this->checkIsDoctorFavoriteExist($user->id, $doctorId) === false) { // check if post have exits in user's favorite post
            if (is_null($this->userRepository->findById($doctorId))) {
                return response()->json("Not Found", 404);
            }

            $this->favoriteRepository->create(['user_id' => $user->id, 'favoriteable_id' => $doctorId, 'favoriteable_type' => User::class]); // Add doctor to favorite list

            return response()->json('Add to favorite list successfully', 201); // Add success
        }

        return response()->json("", 204); // Return http no content if the user is had exits in favorite list
    }

    /**
     * Check doctor have existed in the favorite doctor list
     *
     * @param integer $userId
     * @param integer $doctorId
     * @return bool true if existed
     * otherwise false
     */
    final public function checkIsDoctorFavoriteExist(int $userId, int $doctorId): bool
    {
        if (is_null($this->favoriteRepository->checkFavoriteExisted($userId, $doctorId, User::class))) {
            return false;
        }

        return true;
    }

    /**
     * Check if doctor exits in user favorite list
     *
     * @param integer $userId
     * @param integer $doctorId
     * @return JsonResponse
     */
    final public function checkUserFollow(int $userId, int $doctorId): JsonResponse
    {
        if ($this->checkIsDoctorFavoriteExist($userId, $doctorId)) {
            return response()->json(true);
        }

        return response()->json(false);
    }

    /**
     * Remove a doctor get out user's favorite post
     *
     * @param integer $userId
     * @param integer $doctorId
     * @return JsonResponse
     */
    final public function removeFavoriteItem(int $userId, int $doctorId): JsonResponse
    {
        if (is_null($this->favoriteRepository->getDetailFavorite($userId, $doctorId))) {
            return response()->json("Doctor favorite item not found", 404);
        }

        $this->favoriteRepository->removeFavorite($userId, $doctorId);
        return response()->json("", 204);
    }
}
