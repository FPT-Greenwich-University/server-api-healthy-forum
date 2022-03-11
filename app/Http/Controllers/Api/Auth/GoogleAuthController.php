<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $googleUser = User::where('email', '=', $request->email)->first();

            if (!$googleUser) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'image_url' => $request->image_url,
                    'provider_id' => $request->provider_id,
                    'email_verified_at' => now()
                ]);
            } else {
                User::where('email', $request->email)->update([
                    'name' => $request->name,
                    'image_url' => $request->image_url,
                    'email_verified_at' => now()
                ]);
                $user = User::where('email', $request->email)->first();
            }
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
