<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderInterface;
use App\Http\Requests\OrderRequest;
use App\Traits\ResponseAPI;
use Illuminate\Support\Facades\Mail;
use App\Mail\{OrderNotificationMail, OrderConfirmationMail};
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseAPI;

    protected $orderInterface;

    public function __construct(OrderInterface $orderInterface) {
        $this->middleware('auth:api', ['except' => ['makeOrders', 'verifyPurchaseStatus']]);

        $this->orderInterface = $orderInterface;
    }

    public function makeOrders(OrderRequest $request) {
        $response = $this->orderInterface->makeOrders($request);

        return $this->success($response->data, $response->message);
    }

    public function getOrders() {
        $response = $this->orderInterface->getOrders();

        return $this->success($response);
    }

    public function verifyPurchaseStatus(Request $request) {
        $purchaseKey = $request->query('purchase_key');
        $response = $this->orderInterface->verifyPurchaseStatus($purchaseKey);

        if($response->data->sendNotificationMail === true) {
            Mail::to($response->data->talentEmail)->send(new OrderNotificationMail($response->data->deadline));
            Mail::to($response->data->purchaserEmail)->send(new OrderConfirmationMail());
        }

        return $this->success($response->data);
    }
}
