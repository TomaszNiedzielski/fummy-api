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

    /**
     * Update activity status which describes if user want to receive orders
     * 
     * @method  POST    api/profile/update-activity-status
     * @access  public
     */
    public function updateActivityStatus(Request $request);

    /**
     * Update delivery status: on/off 24 hours delivery
     * 
     * @method  POST    api/profile/update-delivery-time-status
     * @access  public
     */
    public function updateDeliveryTimeStatus(Request $request);
}