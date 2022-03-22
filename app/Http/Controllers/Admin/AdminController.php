<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ResponseAPI;
use DB;

class AdminController extends Controller
{
    use ResponseAPI;

    public function getAllUsers() {
        $users = DB::table('users')
            ->select('users.id', 'full_name as fullName', 'email', 'nick', 'avatar', 'is_verified as isVerified', DB::raw('COUNT(orders.id) as ordersNumber'))
            ->where('users.id', '!=', 1)
            ->leftJoin('offers', 'offers.user_id', '=', 'users.id')
            ->leftJoin('orders', function ($join) {
                $join->on('orders.offer_id', '=', 'offers.id')
                ->where('orders.is_paid', 1);
            })
            ->groupBy('users.id', 'fullName', 'email', 'nick', 'avatar', 'isVerified')
            ->get();

        return $this->success($users);
    }

    public function verifyUser(int $id) {
        User::where('id', $id)->update(['is_verified' => 1]);

        return $this->success();
    }

    public function deleteUser(int $id) {
        User::where('id', $id)->delete();

        return $this->success();
    }
}