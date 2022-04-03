<?php

namespace App\Http\Controllers\Api\Users\Favorites;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Favorites\StoreFavoriteDoctorRequest;
use App\Models\Favorite;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorFavoriteController extends Controller
{
    /**
     * User get own list favorite doctors
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userID = $request->user()->id;
            $favoritePosts = Favorite::where('user_id', $userID)
                ->where('favoriteable_type', 'App\Models\User')
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
     * @param StoreFavoriteDoctorRequest $request
     * @return JsonResponse
     */
    public function store(StoreFavoriteDoctorRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $doctorID = $request->input('doctor_id');

            if ($this->checkIsDoctorFavoriteExist($user->id, $doctorID) === false) { // check if post have exits in user's favorite post
                User::findOrFail($doctorID); // check the doctor is exits in system, return 404 if post not found
                // Add doctor to favorite list
                Favorite::create([
                    'user_id' => $user->id,
                    'favoriteable_id' => $doctorID,
                    'favoriteable_type' => "App\Models\User"
                ]);
                return response()->json('Add doctor to the favorite successfully');
            }

            // Default
            return response()->json('The doctor have existed in favorite list', 202);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
                'Trace' => $exception->getTrace()
            ], 500);
        }
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
        $favoriteDoctor = Favorite::where('user_id', $userID)
            ->where('favoriteable_id', $doctorID)
            ->where('favoriteable_type', 'App\Models\User')
            ->first();

        if (is_null($favoriteDoctor)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Remove a doctor get out user's favorite post
     *
     * @param $favoriteID
     * @return JsonResponse
     */
    public function destroy($favoriteID): JsonResponse
    {
        try {
            Favorite::findOrFail($favoriteID); // if not found favorite item then return 404 json error
            Favorite::destroy($favoriteID);
            return response()->json('Remove the doctor get out favorite list successfully');
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
