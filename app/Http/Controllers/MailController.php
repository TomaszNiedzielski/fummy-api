<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use App\Interfaces\MailInterface;

class MailController extends Controller
{
    use ResponseAPI;

    protected $mailInterface;

    public function __construct(MailInterface $mailInterface) {
        $this->mailInterface = $mailInterface;
    }

    public function confirmVerification(Request $request) {
        $response = $this->mailInterface->confirmVerification($request);

        if(!$response) {
            return $this->error();
        }

        return $this->success();
    }

    public function sendVerificationMail() {
        $response = $this->mailInterface->sendVerificationMail();

        if($response->code === 429) {
            return $this->error($response->message, null, $response->code);
        }

        return $this->success($response);
    }
}