<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccountRequest;
use App\Interfaces\BankAccountInterface;
use App\Traits\ResponseAPI;

class BankAccountController extends Controller
{
    use ResponseAPI;

    protected $bankAccountInterface;

    public function __construct(BankAccountInterface $bankAccountInterface) {
        $this->middleware('auth:api');

        $this->bankAccountInterface = $bankAccountInterface;
    }

    public function update(BankAccountRequest $request) {
        $this->bankAccountInterface->update($request);

        return $this->success();
    }

    public function get() {
        $response = $this->bankAccountInterface->get();

        return $this->success($response);
    }
}
