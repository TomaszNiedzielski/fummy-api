<?php

namespace App\Interfaces;

use App\Http\Requests\OfferRequest;

interface OfferInterface
{
    /**
     * Update offer
     * 
     * @method  api/offer/create  POST
     * @access  public
     */
    public function update(OfferRequest $request);

    /**
     * Load offers per user
     * 
     * @method  api/offer/load  POST
     * @access  public
     */
    public function load(string $nick);
}