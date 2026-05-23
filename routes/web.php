<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Client\ClientAuthController;

Route::get('/', function () {
    return view('welcome');
});

// ─── Super Admin Routes ───────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [SuperAdminController::class, 'loginPage'])->name('login');
    Route::post('/login', [SuperAdminController::class, 'login'])->name('login.post');

    Route::middleware(['auth', 'super_admin'])->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [SuperAdminController::class, 'logout'])->name('logout');
        Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');

        // ─── Client CRUD ──────────────────────────────────────
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::patch('/clients/{id}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');

        // ─── Payments ─────────────────────────────────────────
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

        // ─── BG Owner ─────────────────────────────────────────
        Route::get('/bg-owner', [SuperAdminController::class, 'bgOwner'])->name('bg-owner');
        Route::post('/bg-owner/verify-pin', [SuperAdminController::class, 'verifyBgOwnerPin'])->name('bg-owner.verify-pin');
    });
});

// ─── Client Routes ────────────────────────────────────────────
Route::prefix('client')->name('client.')->group(function () {

    Route::get('/login', [ClientAuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [ClientAuthController::class, 'login'])->name('login.post');
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [ClientAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ClientAuthController::class, 'profile'])->name('profile');
        Route::get('/settings', [ClientAuthController::class, 'settings'])->name('settings');
        Route::post('/settings/password', [ClientAuthController::class, 'updatePassword'])->name('settings.password');
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');
    });

    // Password reset routes
    Route::get('/forgot-password', [ClientAuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [ClientAuthController::class, 'sendResetLink'])->name('forgot-password.send');
    Route::get('/reset-password/{token}', [ClientAuthController::class, 'resetPasswordPage'])->name('password.reset');
    Route::post('/reset-password', [ClientAuthController::class, 'resetPassword'])->name('password.update');
});

// ─── Birthday Card Screens ─────────────────────────────────────
Route::get('/boy/page/{page}/{variant}', function ($page, $variant) {
    $viewName = 'birthday.boy-page-' . $page;
    if ($variant != '1') {
        $viewName .= '-' . $variant;
    }
    return view($viewName);
})->name('boy.page.variant');

Route::get('/girl/page/{page}/{variant}', function ($page, $variant) {
    $viewName = 'birthday.girl-page-' . $page;
    if ($variant != '1') {
        $viewName .= '-' . $variant;
    }
    return view($viewName);
})->name('girl.page.variant');

// ─── Gift Pages ───────────────────────────────────────────────
Route::get('/boy/gift-1/{page}', function ($page) {
    return view('birthday.boy-gift-1-' . $page);
})->name('boy.gift-1');

Route::get('/girl/gift-1/{page}', function ($page) {
    return view('birthday.girl-gift-1-' . $page);
})->name('girl.gift-1');
