<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChallengeRequest;
use App\Interfaces\ChallengeInterface;
use App\Traits\ResponseAPI;

class ChallengeController extends Controller
{
    use ResponseAPI;

    protected $challengeInterface;

    public function __construct(ChallengeInterface $challengeInterface) {
        $this->challengeInterface = $challengeInterface;
    }

    public function takeChallenge(ChallengeRequest $request) {
        $response = $this->challengeInterface->takeChallenge($request);

        return $this->success($response);
    }

    public function getCurrentChallengeByUserNick(string $nick) {
        $response = $this->challengeInterface->getCurrentChallengeByUserNick($nick);

        return $this->success($response);
    }

    public function editChallenge(ChallengeRequest $request) {
        $response = $this->challengeInterface->editChallenge($request);

        return $this->success($response);
    }
}