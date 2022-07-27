<?php

namespace App\Http\Controllers\Api\Admins\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Users\Permissions\FetchPermissionsRequest;
use App\Http\Requests\Api\Admins\Users\Permissions\UpdatePermissionRequest;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\IRoleRepository;

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
     * Get the detail user include roles and permissions
     *
     * @param $userId
     * @return JsonResponse
     */
    public function getUserRoles(int $userId): JsonResponse
    {
        $result = $this->userRepos->getUserWithRolePermission($userId); // Get detail user

        if ($result === false) return response()->json("User not found", 404); // return 404 if not found user in resources

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
        $roleId = $request->input('role_id'); // get list role id
        return response()->json($this->roleRepos->getPermissionByRoleId($roleId)); // Get list permissions by role's id
    }

    /**
     * Admin update list permission of user
     *
     * @param UpdatePermissionRequest $request
     * @param $userId
     * @return JsonResponse
     */
    public function updatePermission(UpdatePermissionRequest $request, int $userId)
    {
        $permissions = $request->input('permissions'); // Get list permission from request of user

        $result = $this->userRepos->syncPermissions($userId, $permissions); // sync permission based on list permission

        if ($result === false) return response()->json("User not found", 404);

        return response()->json("", 204);
    }
}
