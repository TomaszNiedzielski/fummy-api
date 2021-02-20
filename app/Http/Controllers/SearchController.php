<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Traits\ResponseAPI;

class SearchController extends Controller
{
    use ResponseAPI;

    public function search(Request $request) {
        $results = DB::table('users')
            ->where('users.full_name', 'like', '%'.$request->searchingWord.'%')
            ->select('full_name as fullName', 'avatar', 'nick')
            ->get();

        return $this->success($results);
    }

    public function getAllUsers() {
        $users = DB::table('users')
            ->select('full_name as fullName', 'avatar', 'nick')
            ->get();

        return $this->success($users);
    }
}