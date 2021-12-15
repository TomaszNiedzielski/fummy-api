<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface PayoutInterface
{
    /**
     * Create payout request
     * 
     * @method  POST  api/payouts/request
     */
    public function createRequest();

    /**
     * Check if request was sent
     * 
     * @method  GET  api/payouts/request/status
     */
    public function isRequestSent(): bool;

    /**
     * Get all payouts history
     * 
     * @method  GET  api/payouts/history
     */
    public function getPayoutsHistory(): Collection;
}