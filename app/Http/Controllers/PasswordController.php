<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use App\Interfaces\PasswordInterface;

class PasswordController extends Controller
{
    use ResponseAPI;

    protected $passwordInterface;

    public function __construct(PasswordInterface $passwordInterface) {
        $this->passwordInterface = $passwordInterface;
    }

    public function sendResetLink(Request $request) {
        $response = $this->passwordInterface->sendResetLink($request);

        if($response->status === 'error') {
            return $this->error($response->message);
        }

        return $this->success($response->message);
    }

    public function change(Request $request) {
        $response = $this->passwordInterface->change($request);

        if($response->status === 'error') {
            return $this->error($response->message);
        }

        return $this->success($response->message);
    }
}