<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\User;
use App\Traits\{JSONCamelize, ResponseAPI};
use DB;

class AdminController extends Controller
{
    use ResponseAPI, JSONCamelize;

    public function getAllUsers()
    {
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

    public function verifyUser(int $id)
    {
        User::where('id', $id)->update(['is_verified' => 1]);

        return $this->success();
    }

    public function deleteUser(int $id)
    {
        User::where('id', $id)->delete();

        return $this->success();
    }

    public function getPayouts()
    {
        $payouts = Payout::with(['user' => function ($query) {
            $query->select('id', 'nick', 'full_name', 'email', 'avatar')
            ->with(['bankAccount' => function ($query) {
                $query->select('user_id', 'number', 'holder_name')
                ->where('is_removed', false);
            }]);
        }])
        ->select('id', 'amount', 'is_complete', 'created_at', 'is_complete', 'user_id')
        ->get();

        $payouts = $this->toCamelCase($payouts);

        return $this->success($payouts);
    }

    public function confirmPayout($id)
    {
        Payout::where('id', $id)->update(['is_complete' => true]);

        return $this->success();
    }
}