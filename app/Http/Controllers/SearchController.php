<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Traits\ResponseAPI;
use App\Models\User;

class SearchController extends Controller
{
    use ResponseAPI;

    public function search(Request $request) {
        $results = DB::table('users')
            ->where([
                ['full_name', 'like', '%'.$request->searchingWord.'%'],
                ['verified', '=', true]
            ])
            ->orWhere([
                ['users.nick', 'like', '%'.$request->searchingWord.'%'],
                ['verified', '=', true]
            ])
            ->select('full_name as fullName', 'avatar', 'nick', 'verified as isVerified')
            ->get();

        return $this->success($results);
    }

    public function getVerifiedUsers() {
        $users = DB::table('users')
            ->where('verified', true)
            ->join('offers', function($join) {
                $join->on('offers.user_id', '=', 'users.id')
                ->where('offers.is_removed', false);
            })
            ->select('users.full_name as fullName', 'users.avatar', 'users.nick', 'users.verified as isVerified', DB::raw('MIN(offers.price) as priceFrom'), DB::raw('MAX(offers.price) as priceTo'), 'offers.currency')
            ->groupBy('users.full_name', 'users.avatar', 'users.nick', 'users.verified', 'offers.currency')
            ->get();

        $updatedUsers = array();
        foreach($users as $user) {
            $user->prices = (object) [
                'from' => $user->priceFrom.' '.$user->currency,
                'to' => $user->priceTo.' '.$user->currency
            ];
            unset($user->priceFrom);
            unset($user->priceTo);
            unset($user->currency);

            array_push($updatedUsers, $user);
        }

        return $this->success($updatedUsers);
    }
}