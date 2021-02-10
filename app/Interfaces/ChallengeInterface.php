<?php

namespace App\Interfaces;

use App\Http\Requests\ChallengeRequest;

interface ChallengeInterface
{
    /**
     * Take challenge, save title and price
     * 
     * @method  POST    api/challenge/take
     * @access  public
     */
    public function takeChallenge(ChallengeRequest $request);

    /**
     * Get Information about current challenge for specific user
     * 
     * @method  GET     api/challenge/get-current/{nick}
     * @access  public
     */
    public function getCurrentChallengeByUserNick(string $nick);
}