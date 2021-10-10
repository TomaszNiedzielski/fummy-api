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
        $this->orderInterface = $orderInterface;
    }

    public function create(OrderRequest $request) {
        $response = $this->orderInterface->create($request);

        $talentId = Offer::find(25)->user_id;
        $talentEmail = User::find($talentId)->email;

        Mail::to($talentEmail)->send(new OrderNotificationMail());

        return $this->success($response);
    }

    public function load() {
        $response = $this->orderInterface->load();

        return $this->success($response);
    }
}
