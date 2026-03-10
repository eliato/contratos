<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ContractDownloadController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\ContractTemplates as AdminContractTemplates;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PaymentList as AdminPaymentList;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Livewire\Admin\UserList as AdminUserList;
use App\Livewire\CreateContract;
use App\Livewire\SignContract;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Google OAuth
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Contract routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('contracts/create',            CreateContract::class)->name('contracts.create');
    Route::get('contracts/{contract}',        SignContract::class)->name('contracts.show');
    Route::get('contracts/{contract}/sign',   SignContract::class)->name('contracts.sign');

    Route::get('contracts/{contract}/pay',        [PaymentController::class, 'initiate'])
        ->middleware('throttle:payment')
        ->name('contracts.pay');

    Route::get('contracts/{contract}/pay/return', [PaymentController::class, 'return'])
        ->name('contracts.pay.return');

    Route::get('contracts/{contract}/download',   ContractDownloadController::class)
        ->name('contracts.download');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          AdminDashboard::class)->name('dashboard');
    Route::get('/users',     AdminUserList::class)->name('users');
    Route::get('/payments',  AdminPaymentList::class)->name('payments');
    Route::get('/templates', AdminContractTemplates::class)->name('templates');
    Route::get('/settings',  AdminSettings::class)->name('settings');
});

// WOMPI webhook — no auth, rate limited
Route::post('webhooks/wompi', [PaymentController::class, 'webhook'])
    ->middleware('throttle:60,1')
    ->name('webhooks.wompi');

require __DIR__.'/settings.php';
