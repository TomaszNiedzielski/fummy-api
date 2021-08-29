<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderInterface;
use App\Http\Requests\OrderRequest;
use App\Traits\ResponseAPI;

class OrderController extends Controller
{
    use ResponseAPI;

    protected $orderInterface;

    public function __construct(OrderInterface $orderInterface) {
        $this->orderInterface = $orderInterface;
    }

    public function create(OrderRequest $request) {
        $response = $this->orderInterface->create($request);

        return $this->success($response);
    }

    public function load() {
        $response = $this->orderInterface->load();

        return $this->success($response);
    }
}
