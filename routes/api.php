<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    UserController,
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
    MailingAddressController,
    NotificationController,
};
use App\Http\Controllers\Admin\{
    AdminAuthController,
    AdminController,
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

Route::group(['middleware' => ['api', 'cors']], function() {
    Route::prefix('auth')->group(function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::prefix('users')->group(function() {
        Route::get('verified', [UserController::class, 'getVerifiedUsers']);
        Route::get('{nick}', [UserController::class, 'getUserDetails'])->where('nick', '[a-z0-9_]{3,}');
        Route::get('me', [UserController::class, 'getUserDetails']);
        Route::post('me', [UserController::class, 'updateUserDetails']);
        Route::put('activity-status', [UserController::class, 'updateActivityStatus']);
        Route::put('delivery-time-status', [UserController::class, 'updateDeliveryTimeStatus']);
    });

    Route::get('search-results', [SearchController::class, 'search']);

    Route::prefix('mail')->group(function() {
        Route::post('verification-mail', [MailController::class, 'sendVerificationMail']);
        Route::post('confirm', [MailController::class, 'confirmVerification']);
    });

    Route::post('password/send-reset-link', [PasswordController::class, 'sendResetLink']);
    Route::post('password/reset', [PasswordController::class, 'reset']);
    Route::put('password', [PasswordController::class, 'update']);

    Route::post('offers', [OfferController::class, 'saveOffers']);
    Route::get('offers', [OfferController::class, 'getOffers']);

    Route::post('videos', [VideoController::class, 'uploadVideos']);
    Route::get('videos', [VideoController::class, 'getVideos']);

    Route::post('orders', [OrderController::class, 'makeOrders']);
    Route::get('orders', [OrderController::class, 'getOrders']);
    Route::post('orders/purchase/verify-status', [OrderController::class, 'verifyPurchaseStatus']);

    Route::post('webhook', [OrderController::class, 'completeOrderWithWebhook']);

    Route::get('incomes/history', [IncomeController::class, 'getIncomesHistory']);
    Route::get('incomes', [IncomeController::class, 'getIncome']);

    Route::post('bank-account', [BankAccountController::class, 'saveBankAccount']);
    Route::get('bank-account', [BankAccountController::class, 'getBankAccount']);

    Route::post('payouts/request', [PayoutController::class, 'createRequest']);
    Route::get('payouts/request/status', [PayoutController::class, 'isRequestSent']);
    Route::get('payouts/history', [PayoutController::class, 'getPayoutsHistory']);

    Route::get('account-balance', [AccountBalanceController::class, 'getAccountBalance']);

    Route::post('mailing-address', [MailingAddressController::class, 'addEmail']);

    Route::get('notifications', [NotificationController::class, 'getNotifications']);
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
});

Route::post('admin/login', [AdminAuthController::class, 'login']);

Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:admins', 'jwt.auth']], function() {
	Route::get('users', [AdminController::class, 'getAllUsers']);
    Route::post('users/{id}/verify', [AdminController::class, 'verifyUser']);
    Route::delete('users/{id}', [AdminController::class, 'deleteUser']);
});