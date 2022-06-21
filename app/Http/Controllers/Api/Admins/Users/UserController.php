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
        $roles = $this->roleRepos->handleGetExceptRoleByName(['admin']);
        return response()->json($roles);
    }

    /**
     * Admin get all the users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $roleName = User::CUSTOMER_ROLE; // set default role name

        if ($request->query('role_id')) { // if request url have query string role_id
            $roleName = $this->roleRepos->getRoleNameById($request->query('role_id'));
        }

        $listAdminId = $this->userRepos->getListIdByRoleName(User::ADMIN_ROLE);

        $users = $this->userRepos->getUsersWithoutAdmin($roleName, $listAdminId);
        return response()->json($users);
    }

    /**
     * Get the detail user with role
     *
     * @param $userID
     * @return JsonResponse
     */
    public function getUserRoles($userID): JsonResponse
    {

        $result = $this->userRepos->getUserWithRolePermission($userID);

        if ($result === false) return response()->json("User not found", 404);

        return response()->json($result);
    }

    /**
     * Get all permission by role
     *
     * @param FetchPermissionsRequest $request
     * @return JsonResponse
     */
    public function getPermissionsByRole(FetchPermissionsRequest $request): JsonResponse
    {
        $roleId = $request->input('role_id'); // list role id
        return response()->json($this->roleRepos->getPermissionByRoleId($roleId));
    }

    /**
     * Admin update list permission of user
     *
     * @param UpdatePermissionRequest $request
     * @param $userID
     * @return JsonResponse
     */
    public function updatePermission(UpdatePermissionRequest $request, $userID)
    {
            // Get list permission from request of user
            $permissions = $request->input('permissions');

            $result = $this->userRepos->syncPermissions($userID, $permissions);

            if($result === false) return response()->json("User not found", 404);

            return response()->json("", 204);
    }
}
