<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getListIdByRoleName(string $roleName): array
    {
        return $this->model->role($roleName)->pluck('id')->toArray();
    }

    public function getUsersWithoutAdmin(string $roleName, array $list_id)
    {
        return $this->model->role($roleName)
            ->whereNotIn('id', $list_id)
            ->where('email_verified_at', '!=', null)
            ->paginate(10)
            ->withQueryString();
    }

    public function getUserWithRolePermission(int $userId): Model
    {
        return $this->model->with(['roles', 'permissions'])->findOrFail($userId);
    }
}
