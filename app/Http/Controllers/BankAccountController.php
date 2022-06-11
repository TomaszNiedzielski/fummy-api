<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccountRequest;
use App\Interfaces\BankAccountInterface;
use App\Traits\ResponseAPI;

class BankAccountController extends Controller
{
    use ResponseAPI;

    protected $bankAccountInterface;

    public function __construct(BankAccountInterface $bankAccountInterface)
    {
        $this->middleware('auth:api');

        $this->bankAccountInterface = $bankAccountInterface;
    }

    public function saveBankAccount(BankAccountRequest $request)
    {
        $this->bankAccountInterface->saveBankAccount($request);

        return $this->success();
    }

    public function getBankAccount()
    {
        $response = $this->bankAccountInterface->getBankAccount();

        return $this->success($response);
    }
}
