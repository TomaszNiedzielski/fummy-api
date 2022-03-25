<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Http\Requests\OrderRequest;
use App\Models\{Offer, Order};
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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

        // save session id
        Order::find($orderId)->update(['session_id' => $session->id]);

        return $session->url;
    }

    public function verifyPurchaseStatus(string $purchaseKey) {
        $order = Order::where('purchase_key', $purchaseKey)->first();
        
        if(!$order->id) {
            return (object) ['code' => 500];
        }

        return (object) ['data' => (object) [
            'status' => $order->is_paid ? 'paid' : 'unpaid',
            'deadline' => $order->offer->user->is_24_hours_delivery_on ? '1 day' : '7 days'
        ], 'code' => 200];
    }

    public function completeOrderWithWebhook(Request $request) {
        $endpointSecret = \Config::get('constans.stripe_webhook_key');
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload.
            return (object) ['code' => 400];
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid Signature.
            return (object) ['code' => 400];
        }

        if($event->type === 'account.application.deauthorized') {
            $application = $event->data->object;
            $connectedAccountId = $event->account;
            $this->handleDeauthorization($connectedAccountId, $application);
        }

        if($event->type === 'checkout.session.completed') {
            $order = Order::where('session_id', $event->data->object->id)->first();

            $deadlineIn = $order->offer->user->is_24_hours_delivery_on ? '+1 day' : '+7 days';

            Order::where('session_id', $event->data->object->id)
                ->update([
                    'is_paid' => true,
                    'deadline' => date('Y-m-d H:i:s', strtotime($deadlineIn))
                ]);

            return (object) ['code' => 200, 'data' => (object) [
                'talentEmail' => $order->offer->user->email,
                'purchaserEmail' => $order->purchaser_email,
                'deadline' => $order->offer->user->is_24_hours_delivery_on ? '1 day' : '7 days'
            ]];
        }
    }

    private function handleDeauthorization($connectedAccountId, $application) {
        // Clean up account state.
        Log::info('Connected account ID: ' . $connectedAccountId);
        Log::info($application);
    }

    public static function makeWelcomeOrder() {
        // create welcome offer for this user
        $offer = Offer::create([
            'user_id' => auth()->user()->id,
            'title' => 'Video na przywitanie.',
            'description' => '',
            'price' => 100,
            'currency' => 'PLN',
            'is_removed' => 1
        ]);
        
        Order::create([
            'offer_id' => $offer->id,
            'purchaser_name' => 'Fummy',
            'purchaser_email' => env('MAIL_FROM_ADDRESS'),
            'instructions' => 'Nagraj video powitalne, na którym użyjesz nazwy serwisu i zaprosisz swoich fanów do zakupów.',
            'is_private' => 0,
            'is_paid' => 1,
            'purchase_key' => Str::random(60),
            'deadline' => date('Y-m-d H:i:s', strtotime('+7 days'))
        ]);
    }
}