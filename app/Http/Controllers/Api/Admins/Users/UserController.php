<?php

namespace App\Http\Controllers\Api\Admins\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Users\Permissions\FetchPermissionsRequest;
use App\Http\Requests\Api\Admins\Users\Permissions\UpdatePermissionRequest;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    /**
     * Get the user with role
     *
     * @param $userID
     * @return JsonResponse
     */
    public function getUserRoles($userID): JsonResponse
    {
        try {
            return response()->json(User::with(['roles', 'permissions'])->findOrFail($userID));
        } catch (ModelNotFoundException $exception) {
            return response()->json('User not found', 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Get all permission by role
     *
     * @param $roleID
     * @return JsonResponse
     */
    public function getPermissionsByRole(FetchPermissionsRequest $request): JsonResponse
    {
        try {
            return response()->json(DB::table('roles')
                ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->whereIn('roles.id', $request->input('role_id'))
                ->select('permissions.*')
                ->get());
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Admin update list permission of user
     *
     * @param Request $request
     * @param $userID
     * @return JsonResponse|void
     */
    public function updatePermission(UpdatePermissionRequest $request, $userID)
    {
        try {
            // Get list permission from request of user
            $permissions = $request->input('permissions');
            // if fail return response 404
            User::findOrFail($userID)->syncPermissions($permissions); // keep array permission from request
            return response()->json('Update permission success');
        } catch (ModelNotFoundException) {
            return response()->json('User not found', 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
