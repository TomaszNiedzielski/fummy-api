<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\AccountBalanceInterface;
use App\Models\Income;
use App\Models\Payout;

class AccountBalanceRepository implements AccountBalanceInterface
{
    public function getAccountBalance(): string
    {
        $accountBalance = $this->getIncome() - $this->getPayouts();

        return strval($accountBalance);
    }

    public function getIncome(): string
    {
        $income = Income::where('user_id', auth()->user()->id)->sum('net_amount');
        
        return strval($income);
    }

    public function getPayouts(): string
    {
        $payout = Payout::where(['user_id' => auth()->user()->id, 'is_complete' => true])->sum('amount');
    
        return strval($payout);
    }
}