<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IRoleRepository
{
    public function handleGetExceptRoleByName(array $name);
    public function getRoleNameById(int $roleId);
}
