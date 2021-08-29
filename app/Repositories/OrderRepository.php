<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Http\Requests\OrderRequest;
use DB;

class OrderRepository implements OrderInterface
{
    public function create(OrderRequest $request) {
        DB::table('orders')
            ->insert([
                'offer_id' => $request->offerId,
                'purchaser_name' => $request->name,
                'purchaser_email' => $request->email,
                'instructions' => $request->instructions,
                'is_private' => $request->isPrivate,
                'created_at' => date('Y-m-d H:i:s'),
                'deadline' => date('Y-m-d H:i:s', strtotime('+7 days'))
            ]);

        return 'ok';
    }

    public function load() {
        $orders = DB::table('offers')
            ->where('offers.user_id', auth()->user()->id)
            ->join('orders', 'orders.offer_id', '=', 'offers.id')
            ->leftJoin('videos', 'videos.order_id', '=', 'orders.id')
            ->select('orders.id', 'offers.title', 'orders.instructions', 'videos.name as videoName', 'orders.deadline', 'orders.purchaser_name as purchaser', 'offers.price', 'offers.currency')
            ->groupBy('orders.id', 'offers.title', 'orders.instructions', 'videos.name', 'orders.deadline', 'orders.purchaser_name', 'offers.price', 'offers.currency')
            ->get();

        $unrealized = array();
        foreach($orders as $order) {
            if(empty($order->videoName)) {
                array_push($unrealized, $order);
            }
        }

        return $unrealized;
    }
}