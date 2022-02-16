<?php

namespace App\Repositories;

use App\Interfaces\NotificationInterface;
use DB;

class NotificationRepository implements NotificationInterface
{
    public function getNotifications() {
        return (object) ['notifications' => (object) [
            'orders' => (object) ['number' => $this->countUnreadOrders()]
        ]];
    }

    protected function countUnreadOrders() {
        return DB::table('orders')
            ->join('offers', 'offers.id', '=', 'orders.offer_id')
            ->where([
                'offers.user_id' => auth()->user()->id,
                'orders.is_read' => 0,
                'orders.is_paid' => 1
            ])
            ->count();
    }

    public function markAsRead() {
        DB::table('orders')
            ->join('offers', 'offers.id', '=', 'orders.offer_id')
            ->where([
                'offers.user_id' => auth()->user()->id,
                'orders.is_read' => 0
            ])
            ->update(['orders.is_read' => 1]);
    }
}