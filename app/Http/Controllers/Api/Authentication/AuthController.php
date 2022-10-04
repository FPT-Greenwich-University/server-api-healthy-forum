<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Events\UserVerifyAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\IUserRepository;

class AuthController extends Controller
{
    private readonly IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login to the system with normal account
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    final public function login(LoginRequest $request): JsonResponse
    {
        // check email is existed?
        $user = $this->userRepository->checkEmailExists($request->input('email'));


        // check password if wrong?
        if (is_null($user) || $this->checkValidPassword(user: $user, password: $request->input('password')) === FALSE) {
            return response()->json("Email or password not found!", 401);
        }

        // Check email is verified?
        if ($this->checkEmailVerified($user) === FALSE) {
            return response()->json('Your account not verify', 403); //
        }

        // All OK then create a new access token
        $token = $user->createToken('auth-token')->plainTextToken; // give token for user to access backend

        // Return JSON response token and user information
        return response()->json(['token' => $token,]);
    }

    /**
     * <p>Check input password is invalid</p>
     * <p>Return <b>TRUE</b> if password valid, otherwise <b>FALSE</b></p>
     *
     * @param User $user Current user login
     * @param string $password Input password from user
     * @return bool
     */
    private function checkValidPassword(User $user, string $password): bool
    {
        if (!Hash::check($password, $user->password)) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * <p>Check the email have verified yet?</p>
     * <p>Return <b>TRUE</b> if email have verified, otherwise <b>FALSE</b></p>
     *
     * @param User $user
     * @return bool
     */
    private function checkEmailVerified(User $user): bool
    {
        if (is_null($user->email_verified_at)) {
            event(new UserVerifyAccount($user)); // Send email notification verify account
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Register account in system
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    final public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->createNewAccount([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')) // Encryption password field
        ]);

        // send link verify account
        event(new UserVerifyAccount($user));

        return response()->json("Register successfully", 201);
    }

    /**
     * Logout system
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete(); // remove the current token which user are accessing to system
        return response()->json('Logout success');
    }

    final public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['token' => $user->createToken($user->name)->plainTextToken]);
    }
}
