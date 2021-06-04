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
        $users = User::select('full_name as fullName', 'avatar', 'nick', 'verified as isVerified')
            ->where('verified', true)
            ->get();

        return $this->success($users);
    }
}