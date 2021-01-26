<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $fullName = 'Tomasz Niedzielski';
    $nick = '';
    $fullNameExploded = explode(' ', $fullName);
    // var_dump($fullNameExploded);

    /*for($i = 0; $i < count($fullNameExploded); $i++) {
        if($i < count($fullNameExploded)-1) {
            $nick = $nick.$fullNameExploded[$i].'-';
        } else {
            $nick = $nick.$fullNameExploded[$i];
        }
    }*/


    foreach($fullNameExploded as $i=>$value) {
        if($i < count($fullNameExploded)-1) {
            $nick = $nick.$value.'-';
        } else {
            $nick = $nick.$value;
        }
    }

    $nick = strtolower($nick);

    return $nick;
});