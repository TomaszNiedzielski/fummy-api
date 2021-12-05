<?php

namespace App\Interfaces;

interface AccountBalanceInterface
{
    /**
     * Get account balance
     * 
     * @method  api/account-balance/get POST
     */
    public function getAccountBalance(): float;

    /**
     * Get income
     */
    public function getIncome(): float;

    /**
     * Get payouts
     */
    public function getPayouts(): float;
}