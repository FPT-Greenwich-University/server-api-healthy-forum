<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoriteDoctorRequest;
use App\Repositories\Interfaces\IFavoriteRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;

class DoctorFavoriteController extends Controller
{
    private readonly IFavoriteRepository $favoriteRepository;
    private readonly IUserRepository $userRepository;
    private $demo;

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
    public function index(int $userId): JsonResponse
    {
        $perPage = 5;
        return response()->json($this->favoriteRepository->getListFavoritesDoctors($userId, $perPage));
    }

    /**
     * Add post to the favorite list of user
     *
     * @param StoreFavoriteDoctorRequest $request
     * @return JsonResponse
     */
    public function addFavoriteItem(StoreFavoriteDoctorRequest $request): JsonResponse
    {
        $user = $request->user(); // Retrive the current user authenticated

        $doctorId = intval($request->input('doctor_id')); // Retrive doctor id from http request

        // The user can't not add themself to themeself favorite list
        if ($doctorId === $user->id) return response()->json("Bad request", 400); // Return bad request if the doctor's id equal user's id

        if ($this->checkIsDoctorFavoriteExist($user->id, $doctorId) === false) { // check if post have exits in user's favorite post
            $doctor = $this->userRepository->findById($doctorId); // Get the current user

            if (is_null($doctor)) return response()->json("Not Found", 404);

            $this->favoriteRepository->create(['user_id' => $user->id,'favoriteable_id' => $doctorId, 'favoriteable_type' => "App\Models\User" ]); // Add doctor to favorite list

            return response()->json('Add to favorite list successfully', 201); // Add success
        }

        return response()->json("", 204); // Return http no content if the user is have exits in favorite list
    }

    /**
     * Check doctor have existed in the favorite doctor list
     *
     * @param integer $userId
     * @param integer $doctorId
     * @return bool true if existed
     * otherwise false
     */
    public function checkIsDoctorFavoriteExist(int $userId, int $doctorId): bool
    {
        $favoriteExisted = $this->favoriteRepository->checkFavoriteExisted($userId, $doctorId, "App\Models\User");

        if (is_null($favoriteExisted)) return false;

        return true;
    }

    /**
     * Check if doctor exits in user favorite list
     *
     * @param integer $userId
     * @param integer $doctorId
     * @return JsonResponse
     */
    public function checkUserFollow(int $userId, int $doctorId): JsonResponse
    {
        if ($this->checkIsDoctorFavoriteExist($userId, $doctorId)) return response()->json(true);

        return response()->json(false);
    }

    /**
     * Remove a doctor get out user's favorite post
     *
     * @param integer $userId
     * @param integer $doctorId
     * @return JsonResponse
     */
    public function removeFavoriteItem(int $userId, int $doctorId): JsonResponse
    {
        $favorite = $this->favoriteRepository->getDetailFavorite($userId, $doctorId);

        if (is_null($favorite)) return response()->json("Doctor favorite item not found", 404);

        $this->favoriteRepository->removeFavorite($userId, $doctorId);
        return response()->json("", 204);
    }
}