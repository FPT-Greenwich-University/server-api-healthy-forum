<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Model;

interface IUserRepository extends IEloquentRepository
{
    public function getListIdByRoleName(string $roleName): array|string;

    /**
     * @param string $roleName
     * @param array $listIds list id user not include in return result
     * @return mixed
     */
    public function getUsersWithoutAdmin(string $roleName, array $listIds);

    /**
     * @param int $userId
     * @return User
     */
    public function getUserWithRolePermission(int $userId): User|string;

    /**
     * Give permission to the user
     *
     * @param int $userId
     * @param string $permissionName
     * @return void
     */
    public function setDirectPermission(int $userId, string $permissionName): void;

    /**
     * Sync permissions of the user
     *
     * @param integer $userId
     * @param array $permissions
     * @return boolean|string
     */
    public function syncPermissions(int $userId, array $permissions): bool|string;

    /**
     * Get the user with profile
     *
     * @param integer $userId
     * @return User
     */
    public function getUserWithProfile(int $userId): User|string;

    public function updatePassword(int $userId, string $password);

    public function searchUser(string $query, int $perPage);

    /**
     * Check the user email is existed
     * Return User if existed, otherwise NULL
     *
     * @param string $email
     * @return User|null
     */
    public function checkEmailExists(string $email): User|null;

    public function createNewAccount(array $attributes): User;
}