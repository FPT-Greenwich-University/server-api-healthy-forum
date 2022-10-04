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
     * @param int $userId
     * @return JsonResponse
     */
    final public function show(int $userId): JsonResponse
    {
        $user = $this->userRepository->getUserWithProfile($userId);

        if (is_null($user)) {
            return response()->json("Not found", 404);
        }

        return response()->json($user);
    }

    /**
     * User update Profile
     *
     * @param int $userId
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    final public function update(int $userId, UpdateProfileRequest $request): JsonResponse
    {
        $authUserId = $request->user()->id; // Get user's id
        $user = $this->userRepository->findById($userId); // Get the current user

        $attributes = $request->only(['phone', 'description', 'age', 'gender', 'city', 'district', 'ward', 'street']); // Get body field from http request
        $attributes['user_id'] = $userId; // Set

        // Check existed user or is owner of profile
        if ($authUserId !== $userId || is_null($user)) {
            return response()->json("User not found", 404);
        }

        // Get the current user's profile
        if (is_null($this->profileRepository->getUserProfile($userId))) {
            $this->profileRepository->create($attributes);
        } else {
            $this->profileRepository->updateProfileUser($userId, $attributes);
        }

        return response()->json("", 204);
    }

    final public function getUserRoles(int $userId): JsonResponse
    {
        // Check the user is existed
        if (is_null($this->userRepository->findById($userId))) {
            return response()->json("User not found", 404);
        }
        // Return the user include roles and permissions
        return response()->json($this->userRepository->getUserWithRolePermission($userId));
    }
}