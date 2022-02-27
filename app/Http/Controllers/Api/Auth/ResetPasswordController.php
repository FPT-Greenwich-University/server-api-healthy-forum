<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ForgotPassword\SendLinkResetPassword;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');
        try {
            // Check if a user exists in systems
            $user = User::where('email', '=', $email)->first();
            if (is_null($user)) {
                return response()->json('Email not found!', Response::HTTP_NOT_FOUND);
            }
            // Else
            // Generate token
            $token = Str::random(20);
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => bcrypt($token),
                'created_at' => now()
            ]);
            $data = [
                'client_url' => env('CLIENT_APP_URL') . '/reset-password?token=' . $token,
            ];
//            dd($data);
            $user->notify(new SendLinkResetPassword($data));

        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json('send mail success', Response::HTTP_OK);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $token = $request->input('token');
        $passwordReset = DB::table('password_resets')->where('token', '=', bcrypt($token))->first();

        if (!$passwordReset) {
            return response()->json('Invalid token!');
        }
        if (!$user = User::where('email', '=', $passwordReset->email)->first()) {
            return reponse()->json([
                'message' => 'User doesn\'t exist!'
            ]);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'Reset password success',
        ]);
    }
}
