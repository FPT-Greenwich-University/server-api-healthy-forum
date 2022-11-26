<?php

namespace App\Http\Controllers\Api\Admins\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Users\Permissions\FetchPermissionsRequest;
use App\Http\Requests\Api\Admins\Users\Permissions\UpdatePermissionRequest;
use App\Models\User;
use App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private readonly IRoleRepository $roleRepos;
    private readonly IUserRepository $userRepos;


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
    final public function getRoles(): JsonResponse
    {
        return response()->json($this->roleRepos->handleGetExceptRoleByName(['admin']));
    }

    /**
     * Admin get all the users
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $roleName = User::CUSTOMER_ROLE; // set default role name

        if ($request->query('role_id')) { // if request url have query string role_id
            $roleName = $this->roleRepos->getRoleNameById($request->query('role_id'));
        }

        return response()->json($this->userRepos->getUsersWithoutAdmin(roleName: $roleName, listIds: $this->userRepos->getListIdByRoleName(roleName: User::ADMIN_ROLE)));
    }

    /**
     * Get the detail user include roles and permissions
     *
     * @param int $userId
     * @return JsonResponse
     */
    final public function getUserRoles(int $userId): JsonResponse
    {
        if (is_null($this->userRepos->findById($userId))) {
            return response()->json("User not found", 404);
        }

        return response()->json($this->userRepos->getUserWithRolePermission($userId));
    }

    /**
     * Get all permission by role
     *
     * @param FetchPermissionsRequest $request
     * @return JsonResponse
     */
    final public function getPermissionsByRole(FetchPermissionsRequest $request): JsonResponse
    {
        $roleIds = $request->input('role_id'); // get list role id

        return response()->json($this->roleRepos->getPermissionByRoleId($roleIds)); // Get list permissions by role's id
    }

    /**
     * Admin update list permission of user
     *
     * @param UpdatePermissionRequest $request
     * @param int $userId
     * @return JsonResponse
     */
    public function updatePermission(UpdatePermissionRequest $request, int $userId): JsonResponse
    {
        $permissions = $request->input('permissions'); // Get list permission from request of user

        // Sync permission based on list permission
        if ($this->userRepos->syncPermissions($userId, $permissions) === false) {
            return response()->json("User not found", 404);
        }

        return response()->json("", 204); // Update success, return HTTP 204 No Content
    }
}
