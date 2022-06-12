<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IRoleRepository;
use Spatie\Permission\Models\Role;
class RoleRepository extends BaseRepository implements IRoleRepository
{

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function handleGetExceptRoleByName(array $name)
    {
        return $this->model->whereNotIn('name', $name)->get();
    }

    public function getRoleNameById(int $roleId)
    {
        return $this->model->find($roleId)->name;
    }
}
