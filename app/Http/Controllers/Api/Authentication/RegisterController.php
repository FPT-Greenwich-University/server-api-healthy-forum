<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\RegisterDoctorRole;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    final public function getListRegisterDoctorRoles(): JsonResponse
    {
        try {
            $users = RegisterDoctorRole::with(['user'])
                ->where('is_accept', 'false')
                ->paginate(5);
            return response()->json($users);
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
    final public function registerWithRoleDoctor(Request $request): JsonResponse
    {
        try {
            User::findOrFail($request->user()->id);

            if (is_null(RegisterDoctorRole::where('user_id', $request->user()->id)->first())) {
                DB::table('register_doctor_role_drafts')->insert([
                    'user_id' => $request->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return response()->json('Register success');
            }

            return response()->json('You have already register before');
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }

    /**
     * Admin accept request register doctor role from normal user
     *
     * @param $registeredId
     * @return JsonResponse
     */
    final public function acceptRegisterDoctorRole(int $registeredId): JsonResponse
    {
        try {
            $registerUser = DB::table('register_doctor_role_drafts')
                ->where('user_id', $registeredId)
                ->where('is_accept', false)
                ->first();

            if (!is_null($registerUser)) {  // If exist user then
                DB::table('register_doctor_role_drafts')
                    ->where('user_id', $registeredId)
                    ->update(['is_accept' => true]);
                $user = User::findOrFail($registeredId);
                $user->assignRole('doctor'); // Assign doctor role
                $user->givePermissionTo('create a post', 'update a post', 'delete a post'); // Give permission of doctor role
                return response()->json('Accept success');
            }

            return response()->json('User not found or not have request register doctor role!', 404); // Return response 404
        } catch (ModelNotFoundException) {
            return response()->json('User not found', 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }
}
