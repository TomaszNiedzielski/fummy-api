<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DonateRequest;
use App\Interfaces\DonateInterface;
use App\Traits\ResponseAPI;

class DonateController extends Controller
{
    use ResponseAPI;

    protected $donateInterface;

    public function __construct(DonateInterface $donateInterface) {
        $this->donateInterface = $donateInterface;
    }

    public function donate(DonateRequest $request) {
        $response = $this->donateInterface->donate($request);

        return $this->success($response);
    }

    public function loadDonatesData(Request $request) {
        $response = $this->donateInterface->loadDonatesData($request->challengeId);

        return $this->success($response);
    }

    public function countMoneyFromDonates(Request $request) {
        $response = $this->donateInterface->countMoneyFromDonates();

        return $this->success($response);
    }
}