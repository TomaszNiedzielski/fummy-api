<?php

namespace App\Interfaces;

use App\Http\Requests\OfferRequest;
use Illuminate\Http\Request;

interface OfferInterface
{
    /**
     * Create or update offers
     * 
     * @method  POST  api/offers
     */
    public function saveOffers(OfferRequest $request);

    /**
     * Get offers
     * 
     * @method  GET  api/offers
     */
    public function getOffers(string $userNick);
}