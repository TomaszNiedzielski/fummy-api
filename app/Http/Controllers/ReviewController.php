<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Interfaces\ReviewInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ResponseAPI;

    protected $reviewInterface;

    public function __construct(ReviewInterface $reviewInterface)
    {
        $this->reviewInterface = $reviewInterface;
    }

    public function checkKey(Request $request)
    {
        $response = $this->reviewInterface->checkKey($request->key);
    
        if ($response->code === 200) {
            return $this->success($response->data);
        }

        return $this->error($response->message, null, 401);
    }

    public function saveReview(ReviewRequest $request)
    {
        $this->reviewInterface->saveReview($request);
        
        return $this->success();
    }

    public function getReviews(Request $request)
    {
        $userNick = $request->query('user_nick');
        $response = $this->reviewInterface->getReviews($userNick);

        return $this->success($response);
    }
}
