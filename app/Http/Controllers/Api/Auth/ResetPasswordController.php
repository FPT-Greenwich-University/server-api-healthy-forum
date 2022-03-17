<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\ResetPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Send email reset password
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->input('email');
        try {
            $user = User::where('email', '=', $email)->first();// Check if a user exists in systems
            if (is_null($user)) {
                return response()->json('Email not found!', 404);
            }

            $token = Str::random(20); // Else generate token
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);
            $data = [
                'client_url' => env('CLIENT_APP_URL') . '/reset-password?token=' . $token,
            ];
            // Send link reset password vie email
            event(new ResetPassword($user, $data));
            return response()->json('send mail success');
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Handle reset password
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $token = $request->input('token');
        $passwordReset = DB::table('password_resets')->where('token', '=', $token)->first();

        if (!$passwordReset) {
            return response()->json('Invalid token!', 403);
        }
        // Check user is existed
        $user = User::where('email', '=', $passwordReset->email)->first();
        if (!$user) {
            return response()->json([
                'message' => "User doesn't exist!"
            ], 404);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'Reset password success',
        ]);
    }
}
