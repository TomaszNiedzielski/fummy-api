<?php

namespace App\Repositories;

use App\Http\Requests\ProfileDetailsRequest;
use App\Interfaces\ProfileInterface;
use Illuminate\Http\Request;
use DB;
use App\Models\User;

class ProfileRepository implements ProfileInterface
{
    public function loadProfileDetails(Request $request) {
        $profileDetails = User::select('full_name as fullName', 'email_verified_at as mailVerifiedAt', 'nick', 'bio', 'social_media_links as socialMediaLinks', 'avatar', 'verified as isVerified');

        if(isset($request->nick)) {
            $profileDetails = $profileDetails->where('nick', $request->nick)->first();
        } else {
            $profileDetails = $profileDetails->where('id', auth()->user()->id)->first();
        }

        if(!$profileDetails) {
            return (object) ['status' => 'error', 'code' => 404];
        }

        $profileDetails->isMailVerified = $profileDetails->mailVerifiedAt ? true : false;
        unset($profileDetails->mailVerifiedAt);

        $profileDetails->socialMediaLinks = json_decode($profileDetails->socialMediaLinks);

        return $profileDetails;
    }

    public function updateProfileDetails(ProfileDetailsRequest $request) {
        $updatesArray = [
            'full_name' => $request->fullName,
            'nick' => $request->nick,
            'bio' => $request->bio ? $request->bio : '',
            'social_media_links' => $request->socialMediaLinks ? $request->socialMediaLinks : "{}",
        ];

        if($request->hasFile('avatar')) {
            $updatesArray['avatar'] = $this->moveAvatarToStorage($request->file('avatar'));
        }

        $query = DB::table('users')
            ->where('id', auth()->user()->id)
            ->update($updatesArray);

        return $query;
    }

    protected function moveAvatarToStorage($image) {
        $fileNameWithExt = $image->getClientOriginalName();

        $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

        $extension = $image->guessExtension();

        $fileNameToStore = $filename.'_'.time().mt_rand( 0, 0xffff ).'.'.$extension;

        $path = $image->storeAs('public/avatars', $fileNameToStore);

        return $fileNameToStore;
    }
}