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
     * @param $userId
     * @return JsonResponse
     */
    public function show($userId): JsonResponse
    {
        $user = $this->userRepository->getUserWithProfile($userId);

        if ($user === null) return response()->json("Not found", 404);

        return response()->json($user);
    }

    /**
     * Update authenticated user information.
     *
     * @param $userId
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update($userId, UpdateProfileRequest $request): JsonResponse
    {
        $userId = $request->user()->id; // Get user's id
        $user = $this->userRepository->findById($userId); // Get the current user

        $attributes = $request->only(['phone', 'description', 'age', 'gender', 'city', 'district', 'ward', 'street']); // Get body field from http request
        $attributes['user_id'] = $userId; // Set

        if ($userId != $userId || is_null($user)) return response()->json("User not found", 404); // Check existed user?

        $userProfile = $this->profileRepository->getUserProfile($userId);  // Get the current user's profile

        if (is_null($userProfile)) {
            $this->profileRepository->create($attributes);
        } else {
            $this->profileRepository->updateProfileUser($userId, $attributes);
        }

        return response()->json("", 204);
    }
}
