<?php declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\IncomeInterface;
use App\Repositories\AccountBalanceRepository;
use Illuminate\Support\Collection;
use DB;

class IncomeRepository extends AccountBalanceRepository implements IncomeInterface
{
    public function getIncomesHistory(): Collection
    {
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
}