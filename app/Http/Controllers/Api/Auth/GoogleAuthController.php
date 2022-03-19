<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /**
     * Login via google account
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $googleUser = User::where('email', '=', $request->email)->first();

            // if user not existed
            if (!$googleUser) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'image_url' => $request->image_url,
                    'provider_id' => $request->provider_id,
                    'email_verified_at' => now()
                ]);
                $user->assignRole('customer'); // Assign customer role
                $user->givePermissionTo('view all posts', 'view a post');
            } else { // Update info if existed user
                User::where('email', $request->email)->update([
                    'name' => $request->name,
                    'image_url' => $request->image_url,
                    'email_verified_at' => now()
                ]);
                $user = User::where('email', $request->email)->first();
            }
            $token = $user->createToken('auth-token')->plainTextToken; // give a token for user to access backend
            return response()->json(['token' => $token] );
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
