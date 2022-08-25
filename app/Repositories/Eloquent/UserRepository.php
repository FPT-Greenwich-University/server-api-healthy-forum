<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use Exception;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getListIdByRoleName(string $roleName)
    {
        try {
            return $this->model->role($roleName)->pluck('id')->toArray();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getUsersWithoutAdmin(string $roleName, array $listIds)
    {
        try {
            return $this->model->role($roleName)
                ->whereNotIn('id', $listIds)
                ->where('email_verified_at', '!=', null)
                ->paginate(10)
                ->withQueryString();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getUserWithRolePermission(int $userId)
    {
        try {
            $result = $this->model->with(['roles', 'permissions'])->find($userId);

            if (is_null($result)) {
                return false;
            }

            return $result;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function syncPermissions(int $userId, array $permissions)
    {
        try {
            $user = $this->model->find($userId);

            if (is_null($user)) {
                return false;
            }

            $user->syncPermissions($permissions);

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getUserWithProfile(int $userId)
    {
        try {
            return $this->model->with(["profile", "image"])->find($userId);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updatePassword(int $userId, string $password)
    {
        try {
            return $this->model->where('id', $userId)->update(['password' => bcrypt($password)]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function searchUser(string $query, int $perPage)
    {
        try {
            return $this->model->with(['profile', 'image'])
                ->where('email', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->paginate($perPage)
                ->appends(['query' => $query]);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function setDirectPermission(int $userId, string $permissionName)
    {
        $this->model->find($userId)->givePermissionTo($permissionName);
    }
}
