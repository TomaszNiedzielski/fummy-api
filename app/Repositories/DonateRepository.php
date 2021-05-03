<?php

namespace App\Repositories;

use App\Interfaces\DonateInterface;
use App\Http\Requests\DonateRequest;
use App\Models\Donate;
use DB;

class DonateRepository extends ChallengeRepository implements DonateInterface
{
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
            ->select('donator_name as name', 'message', 'amount')
            ->orderBy('created_at', 'desc')
            ->get();

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

        return $money;
    }
}