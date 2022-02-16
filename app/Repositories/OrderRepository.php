<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Http\Requests\OrderRequest;
use App\Models\{Order};
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OrderRepository implements OrderInterface
{
    public function makeOrders(OrderRequest $request): object {
        $order = Order::create([
            'offer_id' => $request->offerId,
            'purchaser_name' => $request->name,
            'purchaser_email' => $request->email,
            'instructions' => $request->instructions,
            'is_private' => $request->isPrivate,
            'purchase_key' => Str::random(60),
        ]);

        $paymentLink = $this->getPaymentLink($order->id);

        return (object) ['code' => 200, 'message' => 'Zamówienie zostało złożone.', 'data' => (object) ['paymentLink' => $paymentLink]];
    }

    public function getOrders() {
        $orders = DB::table('offers')
            ->where([
                'offers.user_id' => auth()->user()->id,
                'orders.is_paid' => true
            ])
            ->join('orders', 'orders.offer_id', '=', 'offers.id')
            ->leftJoin('videos', 'videos.order_id', '=', 'orders.id')
            ->select('orders.id', 'offers.title', 'offers.description', 'orders.instructions', 'videos.name as videoName', 'videos.thumbnail', 'videos.processing_complete as processingComplete', 'orders.deadline', 'orders.purchaser_name as purchaser', 'offers.price', 'offers.currency', 'videos.created_at as videoCreatedAt')
            ->groupBy('orders.id', 'offers.title', 'offers.description', 'orders.instructions', 'videos.name', 'videos.thumbnail', 'videos.processing_complete', 'orders.deadline', 'orders.purchaser_name', 'offers.price', 'offers.currency', 'videos.created_at')
            ->orderBy('videos.created_at', 'desc')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $current = array();
        $completed = array();
        $unrealized = array();

        foreach($orders as $order) {
            if(empty($order->videoName) || !$order->processingComplete) {
                if($order->deadline < date('Y-m-d H:i:s')) {
                    array_push($unrealized, $order);
                } else {
                    array_push($current, $order);
                }
            } else {
                array_push($completed, $order);
            }
        }

        return (object) ['current' => $current, 'completed' => $completed, 'unrealized' => $unrealized];
    }

    protected function getPaymentLink(int $orderId): string {
        $orderInfo = DB::table('orders')
            ->where('orders.id', $orderId)
            ->join('offers', 'offers.id', '=', 'orders.offer_id')
            ->join('users', 'users.id', '=', 'offers.user_id')
            ->select('orders.purchase_key as purchaseKey',
                'orders.purchaser_email as purchaserEmail',
                'offers.price',
                'offers.id as offerId',
                'offers.currency',
                'offers.title',
                'offers.description',
                'users.nick',
                'users.avatar'
            )->first();

        $stripeSecretKey = \Config::get('constans.stripe_secret');
        $stripe = new \Stripe\StripeClient($stripeSecretKey);

        $userProfileLink = \Config::get('constans.frontend_url') . '/u/'.$orderInfo->nick;

        $session = $stripe->checkout->sessions->create([
            'success_url' => $userProfileLink.'/booked?purchase_key='.$orderInfo->purchaseKey,
            'cancel_url' => $userProfileLink.'/booking?id='.$orderInfo->offerId,
            'customer_email' => $orderInfo->purchaserEmail,
            'line_items' => [[
                'price_data' => [
                    'currency' => $orderInfo->currency,
                    'product_data' => [
                        'name' => $orderInfo->title,
                        'description' => $orderInfo->description,
                        'images' => [\Config::get('constans.api_url').'/storage/avatars/'.$orderInfo->avatar]
                    ],
                    'unit_amount' => $orderInfo->price*100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
        ]);

        return $session->url;
    }

    public function verifyPurchaseStatus(string $purchaseKey) {
        $order = Order::where('purchase_key', $purchaseKey)->first();
        
        if($order->id && !$order->is_paid) {
            $deadlineIn = $order->offer->user->is_24_hours_delivery_on ? '+1 day' : '+7 days';

            $order->is_paid = true;
            $order->deadline = date('Y-m-d H:i:s', strtotime($deadlineIn));
            $order->save();

            /* Payment has been completed. Notify user about new order. */
            $sendNotificationMail = true;
        } else {
            $sendNotificationMail = false;
        }

        return (object) ['data' => (object) [
            'status' => $order->is_paid ? 'paid' : 'unpaid',
            'sendNotificationMail' => $sendNotificationMail,
            'talentEmail' => $order->offer->user->email,
            'purchaserEmail' => $order->purchaser_email,
            'deadline' => $order->offer->user->is_24_hours_delivery_on ? '1 day' : '7 days'
        ]];
    }
}