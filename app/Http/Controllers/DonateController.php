<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DonateRequest;
use App\Interfaces\DonateInterface;

class DonateController extends Controller
{
    protected $donateInterface;

    public function __construct(DonateInterface $donateInterface) {
        $this->donateInterface = $donateInterface;
    }

    public function donate(DonateRequest $request) {
        $response = $this->donateInterface->donate($request);

        return response()->json($response);
    }
}
