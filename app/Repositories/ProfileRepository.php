<?php

namespace App\Repositories;

use App\Http\Requests\ProfileDetailsRequest;
use App\Interfaces\ProfileInterface;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Traits\SocialMediaLinksValidator;
use Image;
use Illuminate\Support\Str;

class ProfileRepository implements ProfileInterface
{
    use SocialMediaLinksValidator;

    public function loadProfileDetails(Request $request) {
        $profileDetails = User::select(
            'full_name as fullName',
            'email_verified_at as mailVerifiedAt',
            'nick',
            'bio',
            'social_media_links as socialMediaLinks',
            'avatar',
            'verified as isVerified',
            'is_active as isActive',
            'is_24_hours_delivery_on as is24HoursDeliveryOn'
        );

        if(isset($request->nick)) {
            $profileDetails = $profileDetails->where('nick', $request->nick)->first();
        } else {
            $profileDetails = $profileDetails->where('id', auth()->user()->id)->first();
        }

        if(auth()->check()) {
            $profileDetails->isDashboard = $profileDetails->nick === auth()->user()->nick;
        } else {
            $profileDetails->isDashboard = false;
        }

        if(!$profileDetails) {
            return (object) ['code' => 404, 'message' => 'Użytkownika nie znaleziono.'];
        }

        $profileDetails->isMailVerified = $profileDetails->mailVerifiedAt ? true : false;
        unset($profileDetails->mailVerifiedAt);

        $profileDetails->socialMediaLinks = json_decode($profileDetails->socialMediaLinks);

        return (object) ['code' => 200, 'data' => $profileDetails];
    }

    public function updateProfileDetails(ProfileDetailsRequest $request) {
        if($this->validate($request->socialMediaLinks) === false) {
            return (object) ['code' => 500];
        }

        $updatesArray = [
            'full_name' => $request->fullName,
            'nick' => $request->nick,
            'bio' => $request->bio ? $request->bio : '',
            'social_media_links' => $request->socialMediaLinks ? $request->socialMediaLinks : "{}",
        ];

        if($request->hasFile('avatar')) {
            $updatesArray['avatar'] = $this->moveAvatarToStorage($request->file('avatar'));
        }

        DB::table('users')
            ->where('id', auth()->user()->id)
            ->update($updatesArray);

        return (object) ['code' => 200];
    }

    protected function moveAvatarToStorage($image) {
        $extension = $image->guessExtension();
        $fileName = Str::random(30).'.'.$extension;

        Image::make($image)->resize(600, 600, function($constraint) {
		    $constraint->aspectRatio();
		})->save(public_path('storage/avatars/'.$fileName));

        return $fileName;
    }

    public function updateActivityStatus(Request $request) {
        User::find(auth()->user()->id)->update(['is_active' => $request->isActive]);
    }

    public function updateDeliveryTimeStatus(Request $request) {
        User::find(auth()->user()->id)->update(['is_24_hours_delivery_on' => $request->is24HoursDeliveryOn]);
    }
}