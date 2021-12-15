<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use App\Http\Requests\UserDetailsRequest;
use App\Traits\ResponseAPI;

class UserController extends Controller
{
    use ResponseAPI;

    protected $userInterface;

    public function __construct(UserInterface $userInterface) {
        $this->middleware('auth:api', ['except' => ['getVerifiedUsers', 'getUserDetails']]);

        $this->userInterface = $userInterface;
    }

    public function getVerifiedUsers() {
        $response = $this->userInterface->getVerifiedUsers();

        return $this->success($response);
    }

    public function getUserDetails(Request $request, string $nick = null) {
        $response = $this->userInterface->getUserDetails($request, $nick);

        if($response->code !== 200) {
            return $this->error(null, null, $response->code);
        }

        return $this->success($response->data);
    }

    public function updateUserDetails(UserDetailsRequest $request) {
        $response = $this->userInterface->updateUserDetails($request);

        if($response->code !== 200) {
            return $this->error(null, null, $response->code);
        }

        return $this->success();
    }

    public function updateActivityStatus(Request $request) {
        $this->userInterface->updateActivityStatus($request);

        return $this->success();
    }

    public function updateDeliveryTimeStatus(Request $request) {
        $this->userInterface->updateDeliveryTimeStatus($request);

        return $this->success();
    }
}
