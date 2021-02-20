<?php

namespace App\Repositories;

use App\Http\Requests\ProfileDetailsRequest;
use App\Interfaces\ProfileInterface;
use Illuminate\Http\Request;
use DB;

class ProfileRepository implements ProfileInterface
{
    public function loadProfileDetails(Request $request) {
        if(isset($request->nick)) {
            $profileDetails = DB::table('users')
                ->where('nick', $request->nick)
                ->select('full_name as fullName', 'bio', 'social_media_links as socialMediaLinks', 'avatar')
                ->first();
        } else {
            $profileDetails = DB::table('users')
                ->where('id', auth()->user()->id)
                ->select('full_name as fullName', 'nick', 'bio', 'social_media_links as socialMediaLinks', 'avatar')
                ->first();
        }

        $socialMediaLinks = $profileDetails->socialMediaLinks;
        $socialMediaLinks = json_decode($socialMediaLinks);
        $profileDetails->socialMediaLinks = $socialMediaLinks;

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