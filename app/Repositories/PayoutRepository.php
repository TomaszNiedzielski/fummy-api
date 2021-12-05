<?php declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\PayoutInterface;
use Illuminate\Support\Collection;
use App\Models\Payout;
use App\Repositories\AccountBalanceRepository;

class PayoutRepository extends AccountBalanceRepository implements PayoutInterface
{
    public function createRequest() {
        if($this->isRequestSent()) {
            return (object) ['code' => 429, 'message' => 'Żądanie wypłaty może zostać zrealizowane tylko raz dziennie.'];
        }

        Payout::create([
            'user_id' => auth()->user()->id,
            'amount' => $this->getAccountBalance()
        ]);

        return (object) ['code' => 200, 'message' => 'Żądanie wypłaty zostało zapisane.'];
    }

    public function isRequestSent(): bool {
        return Payout::where(['user_id' => auth()->user()->id, 'is_complete' => false])->exists();
    }

    public function getPayoutsHistory(): Collection {
        return Payout::where(['user_id' => auth()->user()->id])
            ->select('id', 'created_at as createdAt', 'amount', 'is_complete as isComplete')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}