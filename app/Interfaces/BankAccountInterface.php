<?php

namespace App\Interfaces;

use App\Http\Requests\BankAccountRequest;

interface BankAccountInterface
{
    /**
     * Create or update bank account details
     * 
     * @method  POST  api/bank-account
     */
    public function saveBankAccount(BankAccountRequest $request);

    /**
     * Get bank account details
     * 
     * @method  GET  api/bank-account
     */
    public function getBankAccount();
}