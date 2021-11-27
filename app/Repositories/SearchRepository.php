<?php

namespace App\Repositories;

use App\Interfaces\SearchInterface;
use DB;

class SearchRepository implements SearchInterface
{
    public function search(string $searchingWord) {
        $results = DB::table('users')
            ->where([
                ['full_name', 'like', '%'.$searchingWord.'%'],
                ['verified', '=', true]
            ])
            ->orWhere([
                ['users.nick', 'like', '%'.$searchingWord.'%'],
                ['verified', '=', true]
            ])
            ->join('offers', function($join) {
                $join->on('offers.user_id', '=', 'users.id')
                ->where('offers.is_removed', false);
            })
            ->select(
                'full_name as fullName',
                'avatar',
                'nick',
                'verified as isVerified',
                'is_24_hours_delivery_on as is24HoursDeliveryOn',
                DB::raw('MIN(offers.price) as priceFrom'),
            )
            ->groupBy('fullName', 'avatar', 'nick', 'isVerified', 'is24HoursDeliveryOn')
            ->get();

        return $results;
    }

    public function getVerifiedUsers() {
        $users = DB::table('users')
            ->where('verified', true)
            ->join('offers', function($join) {
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
        foreach($users as $user) {
            $user->prices = (object) [
                'from' => $user->priceFrom,
                'currency' => $user->currency
            ];
            unset($user->priceFrom, $user->currency);

            array_push($updatedUsers, $user);
        }

        return $updatedUsers;
    }
}