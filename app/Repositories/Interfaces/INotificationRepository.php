<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface INotificationRepository extends IEloquentRepository
{
    /**
     * @param int $perPage The number of item in one page
     */
    public function getAllNotifications(int $perPage);
}
