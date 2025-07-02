<?php

use App\Http\Livewire\Admin\Bid\Index as BidIndex;
use App\Http\Livewire\Admin\Deal\Index as DealIndex;
use App\Http\Livewire\Admin\Home\Dashboard as AdminDashboard;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Logout;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\SocialLogin;
use App\Http\Livewire\Auth\VerifyEmail;
use App\Http\Livewire\Buy\Index;
use App\Http\Livewire\Buy\ShowBuyCertificate;
use App\Http\Livewire\Certificate\ShowSellCertificate;
use App\Http\Livewire\Certificate\VerifyCertificate;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Profile\Profile;
use App\Http\Livewire\Profile\VerifyUserPhone;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Livewire\Negotation\Index as NegotiationIndex;
use App\Http\Livewire\Portfolio\Index as PortfolioIndex;
use App\Http\Livewire\Admin\Certificate\Index as CertificateIndex;
use App\Http\Livewire\Admin\Certificate\ShowCertificate;
use App\Http\Livewire\Admin\User\Index as UserIndex;
use App\Http\Livewire\Certificate\Index as SellCertificate;
use App\Http\Livewire\Dashboard\Guest\GuestIndex;
use App\Http\Controllers\PolicyController;
use App\Http\Livewire\Admin\Notification\Index as NotificationIndex;
use App\Http\Livewire\Admin\Setting\Index as SettingIndex;

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
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name("login");
    Route::get('login/{loginType}', function ($loginType) {
        // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
        // return redirect($azureLogoutUrl);
        if ( in_array($loginType, ['azure', 'google', 'apple']) ) {
            return Socialite::driver($loginType)->redirect();
        } else {
            return redirect()->route('login');
        }
    })->name('login.social');
    Route::get('auth/success/{type?}', SocialLogin::class)->name('login.social.success');
});
Route::get('/register', Register::class)->name("register");
Route::get('/forgot-password/{token}', Login::class)->name("forgot-password-verify");
Route::middleware('auth')->group(function () {
    Route::group(['middleware' => 'is_user'], function () {
        Route::get('dashboard', Dashboard::class)->name("dashboard");
    });
    Route::get('profile/{tab}', Profile::class)->name("profile");
    Route::get('sell', SellCertificate::class)->name("sell");
    Route::get('sell/certificate/{id}', ShowSellCertificate::class)->name("sell.show.certificate");
    Route::get('buy', Index::class)->name("buy");
    Route::get('buy/certificate/{id}', ShowBuyCertificate::class)->name("buy.show.certificate");
    Route::get('logout', [Logout::class, 'logoutUser'])->name("logout");
    Route::get('offers', NegotiationIndex::class)->name('offers');
    Route::get('my-portfolio', PortfolioIndex::class)->name('my-portfolio');

    Route::group(['middleware' => 'is_admin'], function () {
        Route::get('admin/dashboard', AdminDashboard::class)->name("admin.dashboard");
        Route::get('admin/certificates', CertificateIndex::class)->name("admin.certificates");
        Route::get('admin/certificate/{id}', ShowCertificate::class)->name("admin.show.certificate");
        Route::get('admin/bids', BidIndex::class)->name("admin.bids");
        Route::get('admin/deals', DealIndex::class)->name("admin.deals");
        Route::get('admin/users', UserIndex::class)->name("admin.users");
        Route::get('admin/notifications', NotificationIndex::class)->name("admin.notifications");
        Route::get('admin/settings', SettingIndex::class)->name("admin.settings");
    });
});
Route::get('verify-phone-number/{validate_str}', VerifyUserPhone::class)->name("verifyPhoneNumber");
Route::get('verify-email/{validate_str}', VerifyEmail::class)->name("verifyEmail");
Route::get('validate-certificate/{certificate_id}/{status}', VerifyCertificate::class)->name("validateCertificate");
Route::middleware('guest')->group(function () {
    Route::get('guest/dashboard', GuestIndex::class)->name('guest.dashboard');
});
Route::get('policy', [PolicyController::class, 'policy'])->name('policy');
