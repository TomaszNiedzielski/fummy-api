<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProfileInterface;
use App\Http\Requests\ProfileDetailsRequest;
use App\Traits\ResponseAPI;

class ProfileController extends Controller
{
    use ResponseAPI;

    protected $profileInterface;

    public function __construct(ProfileInterface $profileInterface) {
        $this->middleware('auth:api', ['except' => ['loadProfileDetails']]);

        $this->profileInterface = $profileInterface;
    }

    public function loadProfileDetails(Request $request) {
        $response = $this->profileInterface->loadProfileDetails($request);

        if($response->code !== 200) {
            return $this->error(null, null, $response->code);
        }

        return $this->success($response->data);
    }

    public function updateProfileDetails(ProfileDetailsRequest $request) {
        $response = $this->profileInterface->updateProfileDetails($request);

        if($response->code !== 200) {
            return $this->error(null, null, $response->code);
        }

        return $this->success();
    }

    public function updateActivityStatus(Request $request) {
        $this->profileInterface->updateActivityStatus($request);

        return $this->success();
    }

    public function updateDeliveryTimeStatus(Request $request) {
        $this->profileInterface->updateDeliveryTimeStatus($request);

        return $this->success();
    }
}
