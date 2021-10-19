<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use DB;

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
            ->select('users.full_name as fullName', 'users.avatar', 'users.nick', DB::raw('MIN(offers.price) as priceFrom'), 'offers.currency')
            ->groupBy('users.full_name', 'users.avatar', 'users.nick', 'offers.currency')
            ->get();

        $updatedUsers = array();
        foreach($users as $user) {
            $user->prices = (object) [
                'from' => $user->priceFrom,
                'currency' => $user->currency
            ];
            unset($user->priceFrom, $user->currency);

            array_push($updatedUsers, $user);
        }

        return $this->success($updatedUsers);
    }
}