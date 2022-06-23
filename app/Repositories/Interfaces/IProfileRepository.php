<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IProfileRepository extends IEloquentRepository
{
    public function getUserProfile(int $userId);
    public function updateProfileUser(int $userId, array $attributes);
}
