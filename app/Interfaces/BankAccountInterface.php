<?php

namespace App\Interfaces;

use App\Http\Requests\BankAccountRequest;

interface BankAccountInterface
{
    /**
     * Update all bank account details
     * 
     * @method  api/bank-account/update  POST
     * @access  public
     */
    public function update(BankAccountRequest $request);

    /**
     * Get bank account details
     * 
     * @method  api/bank-account/get    POST
     * @access  public
     */
    public function get();
}