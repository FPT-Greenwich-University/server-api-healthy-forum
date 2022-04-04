<?php

namespace App\Http\Controllers\Api\Admins\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Admin get list customer and doctor role
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        try {
           $all_roles_except_admin = Role::whereNotIn('name', ['admin'])->get();
           return response()->json($all_roles_except_admin);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Admin get all the users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $roleName = User::CUSTOMER; // set default role name
            $listAdminIDs = User::role(User::ADMIN_ROLE)->pluck('id'); // get all admin id

            if ($request->query('role_id')) { // if request url have query string role_id
                $roleName = Role::find($request->query('role_id'))->name;
            }

            // Get list user where role equal role name and not include admin role
            $user = User::role($roleName)
                ->whereNotIn('id', $listAdminIDs)
                ->where('email_verified_at', '!=', null)
                ->paginate(10)
                ->withQueryString();
            return response()->json($user);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
