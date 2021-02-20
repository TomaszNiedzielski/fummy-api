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
}
