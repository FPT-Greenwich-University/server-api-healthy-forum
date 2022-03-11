<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\UserVerifyAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\VerifyAccount\VerifyAccountRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    /**
     * Login to the system with account
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // check email
        try {
            $user = User::where('email', $request->input('email'))->first();

            // check password
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json('Email or password is not correct!', 401);
            }
            // Check email is verified?
            if (is_null($user->email_verified_at)) {
                event(new UserVerifyAccount($user));
                return response()->json('Your account not verify', 403);
            }

            $token = $user->createToken('auth-token')->plainTextToken;
            $response = [
                'token' => $token,
                'user' => $user,
            ];
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    /**
     * Register account in system
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $fields = $request->only(['name', 'email', 'password']); // Get input from form data
            $fields['password'] = bcrypt($fields['password']); // Encryption password field
            $user = User::create($fields);

            // send link verify account
            event(new UserVerifyAccount($user));
            return response()->json("Register successfully", 201);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Logout system
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json('Logout success');
    }
}
