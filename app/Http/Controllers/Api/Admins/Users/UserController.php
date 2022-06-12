<?php

namespace App\Http\Controllers\Api\Admins\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Users\Permissions\FetchPermissionsRequest;
use App\Http\Requests\Api\Admins\Users\Permissions\UpdatePermissionRequest;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Repositories\Interfaces\IRoleRepository;

class UserController extends Controller
{
    private IRoleRepository $roleRepos;
    private IUserRepository $userRepos;


    public function __construct(IUserRepository $userRepository, IRoleRepository $roleRepository)
    {
        $this->userRepos = $userRepository;
        $this->roleRepos = $roleRepository;
    }

    /**
     * Admin get list customer and doctor role
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        try {
            $role = $this->roleRepos->handleGetExceptRoleByName(['admin']);

            return response()->json($role, Response::HTTP_OK);
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
            $roleName = User::CUSTOMER_ROLE; // set default role name

            if ($request->query('role_id')) { // if request url have query string role_id
                $roleName = $this->roleRepos->getRoleNameById($request->query('role_id'));
            }

            $listAdminId = $this->userRepos->getListIdByRoleName(User::ADMIN_ROLE);

            $users = $this->userRepos->getUsersWithoutAdmin($roleName, $listAdminId);
            return response()->json($users);
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
            return response()->json($this->userRepos->getUserWithRolePermission($userID));
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
