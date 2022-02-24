<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
                return response()->json('Email or password is not correct!', Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('auth-token')->plainTextToken;
            $response = [
                'token' => $token,
                'user' => $user,
            ];
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

        return response()->json($response, Response::HTTP_OK);
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
            $fields = $request->only(['name', 'email', 'password']);
            $fields['password'] = bcrypt($fields['password']);

            User::create($fields);

            // All good
            return response()->json("Register successfully", Response::HTTP_CREATED);
        } catch (Exception $e) {
            // Something went wrong!
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

        return response()->json('Logout success', 200);
    }
}
