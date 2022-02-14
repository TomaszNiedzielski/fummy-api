<?php

namespace App\Interfaces;

interface NotificationInterface
{
    /**
     * Get notifications
     * 
     * @method  GET  api/notifications
     */
    public function getNotifications();

    /**
     * Mark notifications as read
     * 
     * @method  POST  api/notifications/mark-as-read
     */
    public function markAsRead();
}