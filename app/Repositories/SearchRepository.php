<?php

namespace App\Repositories;

use App\Interfaces\SearchInterface;
use DB;

class SearchRepository implements SearchInterface
{
    public function search(string $q) {
        $results = DB::table('users')
            ->where([
                ['full_name', 'like', '%'.$q.'%'],
                ['is_verified', '=', true]
            ])
            ->orWhere([
                ['users.nick', 'like', '%'.$q.'%'],
                ['is_verified', '=', true]
            ])
            ->join('offers', function($join) {
                $join->on('offers.user_id', '=', 'users.id')
                ->where('offers.is_removed', false);
            })
            ->select(
                'full_name as fullName',
                'avatar',
                'nick',
                'is_verified as isVerified',
                'is_24_hours_delivery_on as is24HoursDeliveryOn',
                DB::raw('MIN(offers.price) as priceFrom'),
            )
            ->groupBy('fullName', 'avatar', 'nick', 'isVerified', 'is24HoursDeliveryOn')
            ->get();

        return $results;
    }
}