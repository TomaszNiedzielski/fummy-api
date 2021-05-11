<?php

namespace App\Repositories;

use App\Interfaces\DonateInterface;
use App\Http\Requests\DonateRequest;
use App\Models\Donate;
use DB;

class DonateRepository extends ChallengeRepository implements DonateInterface
{
    private $HOT_PAY_PROVISION = 3;
    private $OWN_PROVISION = 6;

    public function donate(DonateRequest $request) {
        $donate = new Donate;
        $donate->donator_email = $request->donatorEmail;
        $donate->donator_name = $request->donatorName;
        $donate->message = $request->message;
        $donate->amount = $request->amount;
        $donate->challenge_id = $this->getCurrentChallengeByUserNick($request->challengerNick)->id;
        $donate->save();

        return $this->getCurrentChallengeByUserNick($request->challengerNick);
    }

    public function loadDonatesData(int $challengeId) {
        $donates = DB::table('donates')
            ->where('challenge_id', $challengeId)
            ->select('donator_name as name', 'message', 'amount', 'created_at as createdAt')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $donates;
    }

    public function countMoneyFromDonates() {
        $amounts = DB::table('challenges')
            ->where('challenges.user_id', auth()->user()->id)
            ->leftJoin('donates', 'donates.challenge_id', '=', 'challenges.id')
            ->select('donates.amount')
            ->get();
        
        $money = 0;
        foreach($amounts as $amount) {
            $money += $amount->amount;
        }

        /* Subtract provision from donates */
        $money = $money - ($money*$this->HOT_PAY_PROVISION/100 + $money*$this->OWN_PROVISION/100);

        return $money;
    }
}