<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderInterface;
use App\Http\Requests\OrderRequest;
use App\Traits\ResponseAPI;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderNotificationMail;
use App\Models\{Offer, User};

class OrderController extends Controller
{
    use ResponseAPI;

    protected $orderInterface;

    public function __construct(OrderInterface $orderInterface) {
        $this->middleware('auth:api', ['except' => ['makeOrders']]);

        $this->orderInterface = $orderInterface;
    }

    public function makeOrders(OrderRequest $request) {
        $response = $this->orderInterface->makeOrders($request);

        $talentId = Offer::find($request->offerId)->user_id;
        $talentEmail = User::find($talentId)->email;

        Mail::to($talentEmail)->send(new OrderNotificationMail());

        return $this->success(null, $response->message);
    }

    public function getOrders() {
        $response = $this->orderInterface->getOrders();

        return $this->success($response);
    }
}
