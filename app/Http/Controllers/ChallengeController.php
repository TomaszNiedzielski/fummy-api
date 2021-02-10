<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChallengeRequest;
use App\Interfaces\ChallengeInterface;

class ChallengeController extends Controller
{
    protected $challengeInterface;

    public function __construct(ChallengeInterface $challengeInterface) {
        $this->challengeInterface = $challengeInterface;
    }

    public function takeChallenge(ChallengeRequest $request) {
        $response = $this->challengeInterface->takeChallenge($request);

        return response()->json($response);
    }

    public function getCurrentChallengeByUserNick(string $nick) {
        $response = $this->challengeInterface->getCurrentChallengeByUserNick($nick);

        return response()->json($response);
    }
}
