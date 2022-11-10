<?php

namespace App\Repositories\Eloquent;

use App\Models\Notification;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\INotificationRepository;
use Exception;

class NotificationRepository extends BaseRepository implements INotificationRepository
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function getAllNotifications(int $perPage)
    {
        try {
            return $this->model->paginate($perPage);
        } catch (Exception $exception) {
            return $exception;
        }
    }
}
