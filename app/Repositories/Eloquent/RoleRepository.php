<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IRoleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements IRoleRepository
{

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function handleGetExceptRoleByName(array $name)
    {
        try {
            return $this->model->whereNotIn('name', $name)->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getRoleNameById(int $roleId)
    {
        return $this->model->find($roleId)->name;
    }

    public function getPermissionByRoleId(array $roleId)
    {
        try {
            return $this->model->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->whereIn('roles.id', $roleId)
                ->select('permissions.*')
                ->get();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
