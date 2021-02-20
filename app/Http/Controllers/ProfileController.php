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
        $this->profileInterface = $profileInterface;
    }

    public function loadProfileDetails(Request $request) {
        $profileDetails = $this->profileInterface->loadProfileDetails($request);

        return $this->success($profileDetails);
    }

    public function updateProfileDetails(ProfileDetailsRequest $profileDetailsRequest) {
        $response = $this->profileInterface->updateProfileDetails($profileDetailsRequest);

        return $this->success();
    }
}
