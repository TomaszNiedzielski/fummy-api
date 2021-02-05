<?php

namespace App\Interfaces;

use App\Http\Requests\ProfileDetailsRequest;
use Illuminate\Http\Request;

interface ProfileInterface
{
    /**
     * load profile details
     * 
     * @method  POST    api/profile/load-details
     * @access  public
     */
    public function loadProfileDetails(Request $request);

    /**
     * update profile details
     * 
     * @method  POST    api/profile/update-details
     * @access  public
     */
    public function updateProfileDetails(ProfileDetailsRequest $profileDetailsRequest);
}