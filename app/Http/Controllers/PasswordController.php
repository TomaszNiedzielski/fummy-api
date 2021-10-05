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

        return $this->success($response);
    }

    public function reset(Request $request) {
        $response = $this->passwordInterface->reset($request);

        if($response->status === 'error') {
            return $this->error($response->message);
        }

        return $this->success($response->message);
    }

    public function update(Request $request) {
        $response = $this->passwordInterface->update($request);

        if($response->status === 'error') {
            return $this->error(null, $response->errors);
        }

        return $this->success($response->message);
    }
}