<?php

namespace App\Repositories;

use App\Interfaces\IncomeInterface;
use DB;

class IncomeRepository implements IncomeInterface
{
    public function getIncomesHistory() {
        $incomesHistory = DB::table('incomes')
            ->where('incomes.user_id', auth()->user()->id)
            ->join('orders', 'orders.id', '=', 'incomes.order_id')
            ->join('offers', 'offers.id', '=', 'orders.offer_id')
            ->select('incomes.net_amount as netAmount', 'incomes.created_at as createdAt', 'offers.price as grossAmount', 'orders.purchaser_name as purchaserName')
            ->groupBy('incomes.net_amount', 'incomes.created_at', 'offers.price', 'orders.purchaser_name')
            ->orderBy('incomes.created_at', 'desc')
            ->get();

        return $incomesHistory;
    }

    public function getIncome() {
        $incomes = DB::table('incomes')
            ->where('user_id', auth()->user()->id)
            ->select('net_amount')
            ->get();

        $incomeNetAmount = 0;
        foreach($incomes as $income) {
            $incomeNetAmount = $incomeNetAmount + $income->net_amount;
        }

        return $incomeNetAmount;
    }
}