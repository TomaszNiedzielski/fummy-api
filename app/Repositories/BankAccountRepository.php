<?php

namespace App\Repositories;

use App\Http\Requests\BankAccountRequest;
use App\Interfaces\BankAccountInterface;
use App\Models\BankAccount;
use DB;

class BankAccountRepository implements BankAccountInterface
{
    public function saveBankAccount(BankAccountRequest $request) {
        DB::transaction(function () use ($request) {
            BankAccount::where(['user_id' => auth()->user()->id, 'is_removed' => false])
                ->update(['is_removed' => true]);

            BankAccount::create([
                'user_id' => auth()->user()->id,
                'number' => $request->number,
                'holder_name' => $request->holderName
            ]);
        });

        return (object) ['code' => 200];
    }

    public function getBankAccount() {
        return BankAccount::where(['user_id' => auth()->user()->id, 'is_removed' => false])
            ->select('number', 'holder_name as holderName')
            ->first();
    }
}