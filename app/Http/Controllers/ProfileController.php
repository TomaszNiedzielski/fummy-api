<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function loadProfileDetails(Request $request) {
        if(isset($request->nick)) {
            $profileDetails = DB::table('users')
                ->where('nick', $request->nick)
                ->select('full_name as fullName', 'bio', 'social_media_links as socialMediaLinks')
                ->first();
        } else {
            $profileDetails = DB::table('users')
                ->where('id', auth()->user()->id)
                ->select('full_name as fullName', 'nick', 'bio', 'social_media_links as socialMediaLinks')
                ->first();
        }

        $socialMediaLinks = $profileDetails->socialMediaLinks;
        $socialMediaLinks = json_decode($socialMediaLinks);
        $profileDetails->socialMediaLinks = $socialMediaLinks;

        return response()->json($profileDetails);
    }

    public function updateProfileDetails(Request $request) {
        DB::table('users')
            ->where('id', auth()->user()->id)
            ->update([
                'full_name' => $request->fullName,
                'bio' => $request->bio,
                'social_media_links' => $request->socialMediaLinks
            ]);

        return response()->json('updated');
    }
}
