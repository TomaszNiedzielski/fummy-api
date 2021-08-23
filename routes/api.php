<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProfileController,
    SearchController,
    ChallengeController,
    DonateController,
    MailController,
    PasswordController,
    OfferController
};
use App\Http\Controllers\Admin\{
    AdminAuthController,
    AdminUsersController
};


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

Route::group(['middleware' => ['api', 'cors'], 'prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['api', 'cors']], function() {
    Route::post('profile/load-details', [ProfileController::class, 'loadProfileDetails']);
    Route::post('profile/update-details', [ProfileController::class, 'updateProfileDetails']);

    Route::post('search', [SearchController::class, 'search']);

    Route::get('users/get', [SearchController::class, 'getVerifiedUsers']);

    Route::post('mail/send/verification-mail', [MailController::class, 'sendVerificationMail']);
    Route::post('mail/confirm', [MailController::class, 'confirmVerification']);

    Route::post('password/send-reset-link', [PasswordController::class, 'sendResetLink']);
    Route::post('password/reset', [PasswordController::class, 'reset']);
    Route::post('password/update', [PasswordController::class, 'update']);

    Route::post('offer/update', [OfferController::class, 'update']);
    Route::get('offer/load/{nick}', [OfferController::class, 'load']);
});

Route::group(['middleware' => ['assign.guard:admins', 'cors'], 'prefix' => 'admin'], function() {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);
    
    Route::group(['prefix' => 'users/load'], function() {
        Route::post('all', [AdminUsersController::class, 'loadAllUsers']);
    });
});