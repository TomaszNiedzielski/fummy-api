<?php

namespace App\Repositories;

use App\Interfaces\DonateInterface;
use App\Http\Requests\DonateRequest;
use App\Models\Donate;

class DonateRepository extends ChallengeRepository implements DonateInterface
{
    public function donate(DonateRequest $request) {
        $donate = new Donate;
        $donate->donator_email = $request->donatorEmail;
        $donate->donator_name = $request->donatorName;
        $donate->amount = $request->amount;
        $donate->challenge_id = $this->getCurrentChallengeByUserNick($request->challengerNick)->id;
        $donate->save();

        return $this->getCurrentChallengeByUserNick($request->challengerNick);
    }
}