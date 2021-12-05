<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface PayoutInterface
{
    /**
     * Create payout request
     * 
     * @method  api/payout/create-request  POST
     */
    public function createRequest();

    /**
     * Check if request was sent
     * 
     * @method  api/payout/is-request-sent  POST
     */
    public function isRequestSent(): bool;

    /**
     * Get all payouts history
     * 
     * @method  api/payout/get-history  POST
     */
    public function getPayoutsHistory(): Collection;
}