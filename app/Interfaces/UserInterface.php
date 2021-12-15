<?php

namespace App\Interfaces;

use App\Http\Requests\UserDetailsRequest;
use Illuminate\Http\Request;

interface UserInterface
{
    /**
     * Get only verified users
     * 
     * @method  GET  api/users
     */
    public function getVerifiedUsers();

    /**
     * load user details
     * 
     * @method  GET  api/users/({nick}|me)
     */
    public function getUserDetails(Request $request, string $nick = null);

    /**
     * update profile details
     * 
     * @method  POST  api/users/me
     */
    public function updateUserDetails(UserDetailsRequest $userDetailsRequest);

    /**
     * Update activity status which describes if user wants to receive orders
     * 
     * @method  PUT  api/users/activity-status
     */
    public function updateActivityStatus(Request $request);

    /**
     * Update delivery status: on/off 24 hours delivery
     * 
     * @method  PUT  api/users/delivery-time-status
     */
    public function updateDeliveryTimeStatus(Request $request);
}