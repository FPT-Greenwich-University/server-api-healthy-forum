<?php

namespace App\Http\Controllers\Api\Auth;

use App\Events\UserVerifyAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\VerifyAccount\VerifyAccountRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyAccountController extends Controller
{
    /**
     * Send mail for verify account
     *
     * @param VerifyAccountRequest $request
     * @return JsonResponse
     */
    public function verifyEmail(VerifyAccountRequest $request): JsonResponse
    {
        try {
            $plainTextToken = $request->input('token'); // get plain text token

            $accountToken = DB::table('verify_accounts')->where('token', $plainTextToken)->first();
            if (is_null($accountToken)) {
                return response()->json('Invalid token', 403);
            }
            // Check user is existed
            $user = User::where('email', $accountToken->email)->first();
            if (!$user) {
                return response()->json("User doesn't exist!", 404);
            } else {
                $user->update(['email_verified_at' => now()]); // Verified email
                return response()->json(['message' => 'Verify account successful']);
            }
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Handle update verify of account
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resendVerifyEmail(Request $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            event(new UserVerifyAccount($user));
            return response()->json(['message' => 'Send email verify success']);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
