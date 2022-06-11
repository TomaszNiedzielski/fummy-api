<?php

namespace App\Repositories;

use App\Http\Requests\UserDetailsRequest;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Traits\SocialsValidator;
use Image;
use Illuminate\Support\Str;

class UserRepository implements UserInterface
{
    use SocialsValidator;

    public function getVerifiedUsers()
    {
        $users = DB::table('users')
            ->where('is_verified', true)
            ->join('offers', function ($join) {
                $join->on('offers.user_id', '=', 'users.id')
                ->where('offers.is_removed', false);
            })
            ->select(
                'users.full_name as fullName',
                'users.avatar',
                'users.nick',
                DB::raw('MIN(offers.price) as priceFrom'),
                'offers.currency',
                'users.is_24_hours_delivery_on as is24HoursDeliveryOn'
            )
            ->groupBy('fullName', 'avatar', 'nick', 'currency', 'is24HoursDeliveryOn')
            ->get();

        $updatedUsers = array();
        foreach ($users as $user) {
            $user->prices = (object) [
                'from' => $user->priceFrom,
                'currency' => $user->currency
            ];
            unset($user->priceFrom, $user->currency);

            array_push($updatedUsers, $user);
        }

        return $updatedUsers;
    }

    public function getUserDetails(Request $request, string $nick = null)
    {
        $userDetails = User::select(
            'full_name as fullName',
            'email_verified_at as mailVerifiedAt',
            'nick',
            'bio',
            'socials',
            'avatar',
            'is_verified as isVerified',
            'is_active as isActive',
            'is_24_hours_delivery_on as is24HoursDeliveryOn'
        );

        if (isset($nick) && $nick !== null) {
            $userDetails = $userDetails->where('nick', $nick)->first();
        } else {
            $userDetails = $userDetails->where('id', auth()->user()->id)->first();
        }

        if (auth()->check()) {
            $userDetails->isDashboard = $userDetails->nick === auth()->user()->nick;
        } else {
            $userDetails->isDashboard = false;
        }

        if (!$userDetails) {
            return (object) ['code' => 404, 'message' => 'Użytkownika nie znaleziono.'];
        }

        $userDetails->isMailVerified = $userDetails->mailVerifiedAt ? true : false;
        unset($userDetails->mailVerifiedAt);

        $userDetails->socials = json_decode($userDetails->socials);

        return (object) ['code' => 200, 'data' => $userDetails];
    }

    public function updateUserDetails(UserDetailsRequest $request)
    {
        if ($this->validate($request->socials) === false) {
            return (object) ['code' => 500];
        }

        $updatesArray = [
            'full_name' => $request->fullName,
            'nick' => $request->nick,
            'bio' => $request->bio ? $request->bio : '',
            'socials' => $request->socials ? $request->socials : "{}",
        ];

        if ($request->hasFile('avatar')) {
            $updatesArray['avatar'] = $this->moveAvatarToStorage($request->file('avatar'));
        }

        DB::table('users')
            ->where('id', auth()->user()->id)
            ->update($updatesArray);

        return (object) ['code' => 200];
    }

    protected function moveAvatarToStorage($image)
    {
        $extension = $image->guessExtension();
        $fileName = Str::random(30).'.'.$extension;

        Image::make($image)->resize(600, 600, function ($constraint) {
		    $constraint->aspectRatio();
		})->save(public_path('storage/avatars/'.$fileName));

        return $fileName;
    }

    public function updateActivityStatus(Request $request)
    {
        User::find(auth()->user()->id)->update(['is_active' => $request->isActive]);
    }

    public function updateDeliveryTimeStatus(Request $request)
    {
        User::find(auth()->user()->id)->update(['is_24_hours_delivery_on' => $request->is24HoursDeliveryOn]);
    }
}