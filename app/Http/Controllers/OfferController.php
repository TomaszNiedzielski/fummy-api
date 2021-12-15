<?php

namespace App\Http\Controllers;

use App\Interfaces\OfferInterface;
use App\Http\Requests\OfferRequest;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use ResponseAPI;

    protected $offerInterface;

    public function __construct(OfferInterface $offerInterface) {
        $this->middleware('auth:api', ['except' => ['getOffers']]);

        $this->offerInterface = $offerInterface;
    }

    public function saveOffers(OfferRequest $request) {
        $response = $this->offerInterface->saveOffers($request);

        return $this->success($response->data);
    }

    public function getOffers(Request $request) {
        $userNick = $request->query('user_nick');
        $response = $this->offerInterface->getOffers($userNick);

        if($response->code !== 200) {
            return $this->error($response->message, null, $response->code);
        }

        return $this->success($response->data);
    }
}