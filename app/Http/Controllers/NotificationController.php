<?php

namespace App\Http\Controllers;

use App\Interfaces\NotificationInterface;
use App\Traits\ResponseAPI;

class NotificationController extends Controller
{
    use ResponseAPI;

    protected $notificationInterface;

    public function __construct(NotificationInterface $notificationInterface) {
        $this->notificationInterface = $notificationInterface;
    }

    public function getNotifications() {
        $response = $this->notificationInterface->getNotifications();

        return $this->success($response);
    }

    public function markAsRead() {
        $this->notificationInterface->markAsRead();
    }
}
