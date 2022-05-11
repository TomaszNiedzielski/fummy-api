<?php

namespace App\Interfaces;

interface AccountBalanceInterface
{
    /**
     * Get account balance
     * 
     * @method  api/account-balance/get POST
     */
    public function getAccountBalance(): string;

    /**
     * Get income
     */
    public function getIncome(): string;

    /**
     * Get payouts
     */
    public function getPayouts(): string;
}