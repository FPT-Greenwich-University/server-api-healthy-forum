<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Get the list of register user who request to become doctor role
     *
     * @return JsonResponse
     */
    public function getListRegisterDoctorRoles(): JsonResponse
    {
        try {
            $registerUsers = DB::table('register_doctor_role_drafts')
                ->where('is_accept', 'false')
                ->paginate('10');
            return response()->json($registerUsers);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Register doctor role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerWithRoleDoctor(Request $request): JsonResponse
    {
        try {
            $user = User::findOrFail($request->user()->id);
            DB::table('register_doctor_role_drafts')->insert([
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json('Register success');
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Admin accept request register doctor role from normal user
     *
     * @param $registerUserID
     * @return JsonResponse
     */
    public function acceptRegisterDoctorRole($registerUserID): JsonResponse
    {
        try {
            $registerUser = DB::table('register_doctor_role_drafts')
                ->where('user_id', $registerUserID)
                ->where('is_accept', false)
                ->first();

            if (!is_null($registerUser)) {  // If exist user then
                DB::table('register_doctor_role_drafts')
                    ->where('user_id', $registerUserID)
                    ->update(['is_accept' => true]);
                $user = User::findOrFail($registerUserID);
                $user->assignRole('doctor'); // Assign doctor role
                $user->givePermissionTo('create a post', 'update a post', 'delete a post'); // Give permission of doctor role
                return response()->json('Accept success');
            } else {
                return response()->json('User not found or not have request register doctor role!', 404); // Return response 404
            }
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
