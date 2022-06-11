<?php

namespace App\Http\Controllers;

use App\Interfaces\PayoutInterface;
use App\Traits\ResponseAPI;

class PayoutController extends Controller
{
    use ResponseAPI;

    protected $payoutInterface;

    public function __construct(PayoutInterface $payoutInterface)
    {
        $this->middleware('auth:api');

        $this->payoutInterface = $payoutInterface;
    }

    public function createRequest()
    {
        $response = $this->payoutInterface->createRequest();

        if ($response->code !== 200) {
            return $this->error($response->message, null, $response->code);
        }

        return $this->success(null, $response->message);
    }

    public function isRequestSent()
    {
        $data = (object) ['isRequestSent' => $this->payoutInterface->isRequestSent()];

        return $this->success($data);
    }

    public function getPayoutsHistory()
    {
        $response = $this->payoutInterface->getPayoutsHistory();

        return $this->success($response);
    }
}
