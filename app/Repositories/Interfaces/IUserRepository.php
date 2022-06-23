<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Model;

interface IUserRepository extends IEloquentRepository
{
    public function getListIdByRoleName(string $roleName);

    /**
     * @param string $roleName
     * @param array $list_id list id user not include in return result
     * @return mixed
     */
    public function getUsersWithoutAdmin(string $roleName, array $list_id);

    /**
     * @param int $userId
     * @return Model
     */
    public function getUserWithRolePermission(int $userId);

    public function syncPermissions(int $userId, array $permissions);
    public function getUserWithProfile(int $userId);
}
