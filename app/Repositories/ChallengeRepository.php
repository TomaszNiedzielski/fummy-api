<?php

namespace App\Repositories;

use App\Interfaces\ChallengeInterface;
use App\Http\Requests\ChallengeRequest;
use App\Models\Challenge;
use DB;

class ChallengeRepository implements ChallengeInterface
{
    public function takeChallenge(ChallengeRequest $request) {
        $challenge = new Challenge;
        $challenge->user_id = auth()->user()->id;
        $challenge->title = $request->title;
        $challenge->price = $request->price;
        $challenge->save();

        return 'challenge saved';
    }

    public function getCurrentChallengeByUserNick(string $nick) {
        $challenge = DB::table('challenges')
            ->join('users', 'users.id', '=', 'challenges.user_id')
            ->where('users.nick', $nick)
            ->select('challenges.id', 'challenges.title', 'challenges.price', 'challenges.created_at as createdAt')
            ->orderBy('challenges.created_at', 'desc')
            ->first();

        if($challenge) {
            $challenge->donatesSum = $this->countMoneyFromDonatesPerChallenge($challenge->id);
        }

        return $challenge;
    }

    private function countMoneyFromDonatesPerChallenge($id) {
        $donateValues = DB::table('donates')
            ->where('challenge_id', $id)
            ->pluck('amount');

        $donatesSum = 0;
        foreach($donateValues as $donateValue) {
            $donatesSum += $donateValue;
        }

        return $donatesSum;
    }
}