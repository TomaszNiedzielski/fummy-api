<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\OfferInterface;
use App\Http\Requests\OfferRequest;
use App\Traits\ResponseAPI;

class OfferController extends Controller
{
    use ResponseAPI;

    protected $offerInterface;

    public function __construct(OfferInterface $offerInterface) {
        $this->offerInterface = $offerInterface;
    }

    public function update(OfferRequest $request) {
        $response = $this->offerInterface->update($request);

        return $this->success($response);
    }

    public function load(string $nick) {
        $response = $this->offerInterface->load($nick);

        return $this->success($response);
    }
}