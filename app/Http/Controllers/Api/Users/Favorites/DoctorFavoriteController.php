<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoriteDoctorRequest;
use App\Repositories\Interfaces\IFavoriteRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;

class DoctorFavoriteController extends Controller
{
    private IFavoriteRepository $favoriteRepository;
    private IUserRepository $userRepository;


    public function __construct(IFavoriteRepository $favoriteRepository, IUserRepository $userRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * User get own list favorite doctors
     *
     * @param $userID
     * @return JsonResponse
     */
    public function index($userID): JsonResponse
    {
        $perPage = 5;
        return response()->json($this->favoriteRepository->getListFavoritesDoctor($userID, $perPage));
    }

    /**
     * Add post to the favorite list of user
     *
     * @param StoreFavoriteDoctorRequest $request
     * @return JsonResponse
     */
    public function addFavoriteItem(StoreFavoriteDoctorRequest $request): JsonResponse
    {
        $user = $request->user();

        $doctorID = intval($request->input('doctor_id'));

        if ($doctorID === $user->id) return response()->json("Bad request", 400);

        if ($this->checkIsDoctorFavoriteExist($user->id, $doctorID) === false) { // check if post have exits in user's favorite post
            $doctor = $this->userRepository->findById($doctorID); // check the doctor is exits in system, return 404 if post not found
            if (is_null($doctor)) return response()->json("Not Found", 404);

            // Add doctor to favorite list
            $this->favoriteRepository->create([
                'user_id' => $user->id,
                'favoriteable_id' => $doctorID,
                'favoriteable_type' => "App\Models\User"
            ]);
            return response()->json('Add to favorite list successfully', 201);
        }
        // Default
        return response()->json("", 204);
    }

    /**
     * Check doctor have existed in the favorite doctor list
     *
     * @param $userID --User id
     * @param $doctorID
     * @return bool true if existed
     * otherwise false
     */
    public function checkIsDoctorFavoriteExist($userID, $doctorID): bool
    {
        $favoriteExisted = $this->favoriteRepository->checkFavoriteExisted($userID, $doctorID, "App\Models\User");

        if (is_null($favoriteExisted)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if doctor exits in user favorite list
     *
     * @param $userID
     * @param $doctorID
     * @return JsonResponse
     */
    public function checkUserFollow($userID, $doctorID): JsonResponse
    {
        if ($this->checkIsDoctorFavoriteExist($userID, $doctorID) === true) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Remove a doctor get out user's favorite post
     *
     * @param $userID
     * @param $doctorID
     * @return JsonResponse
     */
    public function removeFavoriteItem($userID, $doctorID): JsonResponse
    {
        $favorite = $this->favoriteRepository->getDetailFavorite($userID, $doctorID);

        if (is_null($favorite)) return response()->json("Doctor favorite item not found", 404);

        $this->favoriteRepository->removeFavorite($userID, $doctorID);
        return response()->json("", 204);
    }
}
