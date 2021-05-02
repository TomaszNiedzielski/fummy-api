<?php

namespace App\Repositories\Admin;

use App\Interfaces\Admin\AdminUsersInterface;
use DB;

class AdminUsersRepository implements AdminUsersInterface
{
    public function loadAllUsers() {
        $users = DB::table('users')
            ->select('full_name', 'email', 'nick', 'bio', 'avatar', 'created_at')
            ->get();

        return $users;
    }
}