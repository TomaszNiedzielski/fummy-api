<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
    public function search(Request $request) {
        $results = DB::table('users')
            ->where('users.full_name', 'like', '%'.$request->searchingWord.'%')
            ->select('full_name as fullName', 'avatar', 'nick')
            ->get();

        return response()->json($results);
    }
}