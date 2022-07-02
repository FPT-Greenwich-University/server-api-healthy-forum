<?php

namespace App\Repositories\Eloquent;

use App\Models\Profile;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IProfileRepository;

class ProfileRepository extends BaseRepository implements IProfileRepository
{
    public function __construct(Profile $model)
    {
        parent::__construct($model);
    }

    public function getUserProfile(int $userId)
    {
        try {
            return $this->model->where("user_id", "=", $userId)->first();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updateProfileUser(int $userId, array $attributes)
    {
        try {
            $this->model->where("user_id", "=", $userId)
                ->update($attributes);
            return true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
