<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Password\UpdatePasswordRequest;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\AuthenticationServices\AuthenticationInterface;
use Illuminate\Http\JsonResponse;

class ChangePasswordController extends Controller
{
    private readonly IUserRepository $userRepository;
    private readonly AuthenticationInterface $authentication;

    public function __construct(IUserRepository $userRepository, AuthenticationInterface $authentication)
    {
        $this->userRepository = $userRepository;
        $this->authentication = $authentication;
    }

    public function updatePassword(UpdatePasswordRequest $request, int $userId): JsonResponse
    {
        $user = $this->userRepository->findById($userId); // Find user

        if (is_null($user)) return response()->json("User not found", 404); // Return http 404

        // Check current password input from user is correct
        if (!$this->authentication->checkValidPassword($userId, $request->input("current_password"))) return response()->json("Bad request", 400);

        $this->userRepository->updatePassword($userId, $request->input("password")); // Update new password

        return response()->json("", 204); // Success
    }
}
