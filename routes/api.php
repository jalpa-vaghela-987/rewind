<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GlobalController;
use App\Http\Controllers\Api\NegotiationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ManagePaymentController;
use App\Http\Controllers\Api\MyPortfolioController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\SellCertificateController;
use App\Http\Livewire\LatestPurchase;
use Laravel\Cashier\Http\Controllers\PaymentController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login');
    Route::post('social-login', 'SocialLogin');
    Route::post('register-step-1', 'registerStepOne');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('verify-forgot-password', 'forgotPasswordVerify');
    Route::post('reset-password', 'resetPassword');
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('verify-email-otp', 'verifyEmailOtp');
        Route::post('resend-email-otp', 'resendEmailOtp');
        Route::post('register-step-2', 'registerStepTwo');
        Route::post('register-step-3', 'registerStepThree');
        Route::post('register-upload-id-scan', 'uploadIdScan');
        Route::post('register-step-4', 'registerStepFour');
        Route::post('register-upload-incorporation-doc', 'uploadIncorporationDoc');
        Route::post('register-step-5', 'registerStepFive');
        Route::post('register-step-6', 'registerStepSix');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(ProfileController::class)->group(function(){
        Route::get('profile', 'profile');
        Route::post('upload-profile-picture', 'uploadProfilePicture');
        Route::post('change-email', 'changeEmail');
        Route::post('resend-change-email-otp', 'resendChangeEmailOtp');
        Route::post('verify-new-email-otp', 'verifyNewEmailOtp');
        Route::post('change-phone', 'changePhone');
        Route::post('resend-phone-verification-sms', 'resendVerificationSMS');
        Route::post('verify-phone-otp', 'verifyPhoneNumberOTP');
        Route::post('change-name', 'changeName');
        Route::post('change-address', 'changeAddress');
        Route::post('change-company-name', 'changeCompanyName');
        Route::post('change-company-field', 'changeCompanyField');
        Route::post('change-company-address', 'changeCompanyAddress');
        Route::post('add-company-details', 'addCompanyDetails');
        Route::post('my-activities', 'myActivities');
    });
    Route::controller(ManagePaymentController::class)->group(function(){
        Route::post('add-credit-card', 'addCreditCard');
        Route::post('set-primary-card', 'setPrimaryCard');
        Route::post('add-bank-account', 'addBankAccount');
        Route::post('set-primary-bank', 'setPrimaryBank');
        Route::post('delete-bank-account', 'deleteBankAccount');
        Route::post('delete-credit-card', 'deleteCreditCard');
        Route::get('get-my-bank-account-list', 'getMyBankAccountList');
        Route::post('get-my-bank-account-detail', 'getMyBankAccountDetail');
        Route::get('get-my-card-list', 'getMyCardList');
        Route::post('get-my-card-detail', 'getMyCardDetail');
        Route::get('get-my-primary-bank', 'getMyPrimaryBank');
        Route::get('get-my-primary-card', 'getMyPrimaryCard');
    });
    Route::controller(MyPortfolioController::class)->group(function(){
        Route::get('my-portfolio','myPortfolio');
        Route::post('delete-certificate','deleteCertificate');
    });
    Route::controller(TransactionController::class)->group(function(){
        Route::get('my-transactions','myTransactionsList');
        Route::post('my-transaction-detail','MyTransactionDetail');
    });
    Route::controller(SellCertificateController::class)->group(function(){
        Route::post('save-certificate','saveSellCertificate');
    Route::post('view-certificate','viewCertificate');
        Route::get('view-all-certificate','viewAllCertificate');
        Route::post('unit-price-update','updateUnitPrice');
        Route::post('quantity-update','updateQuantity');
        Route::post('cancel-sell-certificate','cancelSellCertificate');
        Route::post('sell-certificate','sellCertificate');
        Route::get('get-notification','getNotification');
        Route::get('get-unread-notification','getUnReadNotification');
        Route::post('read-notification','readNotification');
        Route::post('get-chart','getChart');
    });
    Route::controller(\App\Http\Controllers\Api\BuyCertificateController::class)->group(function(){
        Route::get('buy/view-all-certificate','viewAllCertificate');
        Route::post('buy/view-certificate','viewCertificate');
        Route::post('buy/view-certificate-chart','viewCertificateChart');
        Route::post('buy-certificate','buy');
        Route::post('buy/bid-certificate','bidCertificate');
        Route::post('buy-price-alert','buyPriceAlert');
    });
    Route::controller(DashboardController::class)->group(
        function(){
            Route::get('dashboard-latest-purchase','latestPurchase');
            Route::get('dashboard-trending-certificates','getTrendingCertificates');
            Route::get('dashboard-latest-sales','latestSales');
            Route::get('dashboard-bids','bids');
        }
    );
    Route::controller(NegotiationController::class)->group(function(){
        Route::get('view-all-negotiation','viewAllNegotiation');
        Route::post('verifyBid','verifyBid');
    });
});
Route::controller(GlobalController::class)->group(function(){
    Route::get('get-country-list', 'CountryList');
    Route::get('get-project-list', 'ProjectList');
    Route::get('get-user-list', 'UserList');
});
