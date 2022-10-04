<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Model;

interface IRoleRepository extends IEloquentRepository
{
    /**
     * Get roles where input is except role
     * @param array $name
     * @return mixed
     */
    public function handleGetExceptRoleByName(array $name);

    public function getRoleNameById(int $roleId);

    public function getPermissionByRoleId(array $roleId);
}