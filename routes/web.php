<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\ClientController;
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

        // ─── Client CRUD ──────────────────────────────────────
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    });
});

// ─── Client Routes ────────────────────────────────────────────
Route::prefix('client')->name('client.')->group(function () {

    Route::get('/login', [ClientAuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [ClientAuthController::class, 'login'])->name('login.post');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [ClientAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');
    });
});