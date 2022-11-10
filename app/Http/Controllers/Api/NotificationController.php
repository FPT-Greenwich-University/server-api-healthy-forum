<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\INotificationRepository;

class NotificationController extends Controller
{
    private readonly INotificationRepository $notificationRepository;

    public function __construct(INotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function index()
    {
        return $this->notificationRepository->getAllNotifications(perPage: 5);
    }
}
