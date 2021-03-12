<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, ProfileController, SearchController, ChallengeController, DonateController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => ['api', 'cors']], function() {
    Route::post('profile/load-details', [ProfileController::class, 'loadProfileDetails']);
    Route::post('profile/update-details', [ProfileController::class, 'updateProfileDetails']);

    Route::post('search', [SearchController::class, 'search']);

    Route::post('challenge/take', [ChallengeController::class, 'takeChallenge']);

    Route::post('donates/load', [DonateController::class, 'loadDonatesData']);
});

Route::get('challenge/get-current/{nick}', [ChallengeController::class, 'getCurrentChallengeByUserNick']);

Route::post('donate', [DonateController::class, 'donate']);

Route::get('users/get', [SearchController::class, 'getAllUsers']);