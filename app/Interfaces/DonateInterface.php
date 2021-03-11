<?php

namespace App\Interfaces;

use App\Http\Requests\DonateRequest;

interface DonateInterface
{
    /**
     * Send money on specific challenge
     * 
     * @method  api/donate  POST
     * @access  public
     */
    public function donate(DonateRequest $request);

    /**
     * Load donates for specific challenge
     * 
     * @method  api/donates/load    POST
     * @access  public
     */
    public function loadDonatesData(int $challengeId);
}