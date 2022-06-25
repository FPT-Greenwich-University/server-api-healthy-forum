<?php

namespace App\Http\Controllers\Api\Users\Profiles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Repositories\Interfaces\IProfileRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    private IUserRepository $userRepository;
    private IProfileRepository $profileRepository;

    public function __construct(IUserRepository $userRepository, IProfileRepository $profileRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Get Authenticated user information.
     *
     * @param $userID
     * @return JsonResponse
     */
    public function show($userID): JsonResponse
    {
        $user = $this->userRepository->getUserWithProfile($userID);

        if ($user === null) return response()->json("User Not found", 404);

        return response()->json($user);
    }

    /**
     * Update authenticated user information.
     *
     * @param $userID
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update($userID, UpdateProfileRequest $request): JsonResponse
    {
        $userId = $request->user()->id; // Get user id
        $user = $this->userRepository->findById($userID);
        $attributes = $request->only(['phone', 'description', 'age', 'gender', 'city', 'district', 'ward', 'street']);
        $attributes['user_id'] = $userId;

        if ($userId != $userID || is_null($user)) return response()->json("User not found", 404);

        $userProfile = $this->profileRepository->getUserProfile($userId);

        if (is_null($userProfile)) {
            $this->profileRepository->create($attributes);
        } else {
            $this->profileRepository->updateProfileUser($userId, $attributes);
        }

        return response()->json("", 204);
    }
}
