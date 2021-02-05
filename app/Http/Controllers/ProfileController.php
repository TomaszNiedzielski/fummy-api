<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ProfileInterface;
use App\Http\Requests\ProfileDetailsRequest;

class ProfileController extends Controller
{
    public $profileInterface;

    public function __construct(ProfileInterface $profileInterface) {
        $this->profileInterface = $profileInterface;
    }

    public function loadProfileDetails(Request $request) {
        $profileDetails = $this->profileInterface->loadProfileDetails($request);

        return response()->json($profileDetails);
    }

    public function updateProfileDetails(ProfileDetailsRequest $profileDetailsRequest) {
        $response = $this->profileInterface->updateProfileDetails($profileDetailsRequest);

        return response()->json('updated');
    }
}
