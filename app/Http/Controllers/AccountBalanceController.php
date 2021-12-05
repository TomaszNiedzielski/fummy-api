<?php

namespace App\Http\Controllers;

use App\Traits\ResponseAPI;
use App\Interfaces\AccountBalanceInterface;

class AccountBalanceController extends Controller
{
    use ResponseAPI;
    
    protected $accountBalanceInterface;

    public function __construct(AccountBalanceInterface $accountBalanceInterface) {
        $this->middleware('auth:api');

        $this->accountBalanceInterface = $accountBalanceInterface;
    }

    public function getAccountBalance() {
        $response = $this->accountBalanceInterface->getAccountBalance();

        return $this->success($response);
    }
}
