<?php

namespace App\Repositories;

use App\Interfaces\AccountBalanceInterface;
use App\Models\Income;
use App\Models\Payout;

class AccountBalanceRepository implements AccountBalanceInterface
{
    public function getAccountBalance(): float {
        return $this->getIncome() - $this->getPayouts();
    }

    public function getIncome(): float {
        return Income::where('user_id', auth()->user()->id)->sum('net_amount');
    }

    public function getPayouts(): float {
        return Payout::where(['user_id' => auth()->user()->id, 'is_complete' => true])->sum('amount');
    }
}