<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    ProfileController,
    SearchController,
    MailController,
    PasswordController,
    OfferController,
    VideoController,
    OrderController,
    IncomeController,
    BankAccountController,
    PayoutController,
    AccountBalanceController,
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
    Route::post('profile/update-activity-status', [ProfileController::class, 'updateActivityStatus']);
    Route::post('profile/update-delivery-time-status', [ProfileController::class, 'updateDeliveryTimeStatus']);

    Route::post('search', [SearchController::class, 'search']);

    Route::get('users/get', [SearchController::class, 'getVerifiedUsers']);

    Route::post('mail/send/verification-mail', [MailController::class, 'sendVerificationMail']);
    Route::post('mail/confirm', [MailController::class, 'confirmVerification']);

    Route::post('password/send-reset-link', [PasswordController::class, 'sendResetLink']);
    Route::post('password/reset', [PasswordController::class, 'reset']);
    Route::post('password/update', [PasswordController::class, 'update']);

    Route::post('offers/update', [OfferController::class, 'update']);
    Route::get('offers/load/{nick}', [OfferController::class, 'load']);

    Route::post('video/upload', [VideoController::class, 'upload']);
    Route::get('videos/get-list/{nick}', [VideoController::class, 'getList']);

    Route::post('order/create', [OrderController::class, 'create']);
    Route::post('orders/load', [OrderController::class, 'load']);

    Route::post('incomes/get-history', [IncomeController::class, 'getIncomesHistory']);
    Route::post('income/get', [IncomeController::class, 'getIncome']);

    Route::post('bank-account/update', [BankAccountController::class, 'update']);
    Route::post('bank-account/get', [BankAccountController::class, 'get']);

    Route::post('payout/create-request', [PayoutController::class, 'createRequest']);
    Route::post('payout/is-request-sent', [PayoutController::class, 'isRequestSent']);
    Route::post('payout/get-history', [PayoutController::class, 'getPayoutsHistory']);

    Route::post('account-balance/get', [AccountBalanceController::class, 'getAccountBalance']);
});

Route::group(['middleware' => ['assign.guard:admins', 'cors'], 'prefix' => 'admin'], function() {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);
    
    Route::group(['prefix' => 'users/load'], function() {
        Route::post('all', [AdminUsersController::class, 'loadAllUsers']);
    });
});