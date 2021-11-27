<?php

namespace App\Repositories;

use App\Http\Requests\BankAccountRequest;
use App\Interfaces\BankAccountInterface;
use App\Models\BankAccount;

class BankAccountRepository implements BankAccountInterface
{
    public function update(BankAccountRequest $request) {
        BankAccount::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['number' => $request->number, 'holder_name' => $request->holderName]
        );

        return (object) ['code' => 200];
    }

    public function get() {
        $details = BankAccount::where('user_id', auth()->user()->id)
            ->select('number', 'holder_name as holderName')
            ->first();

        return $details;
    }
}