<?php

namespace App\Http\Controllers\Api\Users\Profiles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Get Authenticated user information.
     *
     * @param $userID
     * @return JsonResponse
     */
    public function show($userID): JsonResponse
    {
        try {
            return response()->json(User::with(['profile'])->findOrFail($userID));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
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
            $userId = $request->user()->id; // Get user id
            $userProfile = DB::table('profiles')
                ->where('user_id', $request->user()->id)
                ->first();
            $data = $request->only(['phone', 'description', 'age', 'gender', 'city', 'district', 'ward', 'street']);
            $data['user_id'] = $userId;

            if (is_null($userProfile)) {
                Profile::create($data);  // Create new profile
            } else {
              $result =   Profile::where('user_id', $userId)->update($data);  // Update current profile
               dd($result);
            }

            return response()->json('Update success', 201);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
