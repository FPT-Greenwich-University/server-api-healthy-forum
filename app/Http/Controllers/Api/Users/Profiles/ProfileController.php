<?php

namespace App\Http\Controllers\Api\Users\Profiles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

class ProfileController extends Controller
{
    /**
     * Get Authenticated user information.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        try {
            // Get user id
            $userId = $request->user()->id;
            // Get user with user id
//            Bad option
//            $user = DB::table('users')
//                ->join('profiles', function ($join) {
//                    $join->on('users.id', '=', 'profiles.user_id');
//                })
//                ->where('users.id', $userId)
//                ->select('users.id', 'users.email', 'users.name', 'profiles.*')
//                ->first();
            $user = User::with(['profile'])->findOrFail($request->user()->id);
            return response()->json($user, ResponseStatus::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Update authenticated user information.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            // Get user id
            $userId = $request->user()->id;
//            $user = User::select('id')->findOrFail($userId);
            $userProfile = DB::table('profiles')
                ->where('user_id', $userId)
                ->first();

            $data = $request->only(['phone', 'description', 'age', 'gender', 'city', 'district', 'ward', 'street']);
            $data['user_id'] = $userId;

            if (is_null($userProfile)) {
                // Create new profile
                Profile::create($data);
            } else {
                // Update current profile
                Profile::where('user_id', $userId)->update($data);
            }
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
        return response()->json('Update success', ResponseStatus::HTTP_ACCEPTED);
    }
}
