<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Client\ClientAuthController;
use App\Http\Controllers\Client\BirthdayCardController;
use App\Http\Controllers\Client\CardManagerController;
use App\Http\Controllers\PublicStoryController;
use App\Http\Controllers\MusicStreamController;

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

        // ─── Clients ──────────────────────────────────────────
        // Clients sign themselves up now, so the Super Admin only views
        // accounts and controls their access — no create/store route.
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
        Route::patch('/clients/{id}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');

        // ─── Subscription approvals ───────────────────────────
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::patch('/subscriptions/{subscriptionRequest}/approve', [SubscriptionController::class, 'approve'])->name('subscriptions.approve');
        Route::patch('/subscriptions/{subscriptionRequest}/reject', [SubscriptionController::class, 'reject'])->name('subscriptions.reject');
        Route::patch('/subscriptions/{user}/revoke', [SubscriptionController::class, 'revoke'])->name('subscriptions.revoke');

        // ─── Payments ─────────────────────────────────────────
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

        // ─── BG Owner ─────────────────────────────────────────
        Route::get('/bg-owner', [SuperAdminController::class, 'bgOwner'])->name('bg-owner');
        Route::post('/bg-owner/verify-pin', [SuperAdminController::class, 'verifyBgOwnerPin'])->name('bg-owner.verify-pin');
        // PIN-gated: serves the client content only once the PIN is accepted.
        Route::get('/bg-owner/data', [SuperAdminController::class, 'bgOwnerData'])->name('bg-owner.data');

        // ─── Generated links ──────────────────────────────────
           Route::get('/links', [SuperAdminController::class, 'links'])->name('links.index');
           Route::patch('/links/{card}/toggle', [SuperAdminController::class, 'toggleCardLink'])->name('links.toggle');
        Route::get('/music', [MusicController::class, 'index'])->name('music.index');
        Route::post('/music/chunk', [MusicController::class, 'uploadChunk'])->name('music.chunk');
        Route::post('/music/finalize', [MusicController::class, 'finalizeChunked'])->name('music.finalize');
        Route::patch('/music/{track}/toggle', [MusicController::class, 'toggle'])->name('music.toggle');
        Route::delete('/music/{track}', [MusicController::class, 'destroy'])->name('music.destroy');
    });
});

// ─── Client Routes ────────────────────────────────────────────
Route::prefix('client')->name('client.')->group(function () {

    Route::get('/login', [ClientAuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [ClientAuthController::class, 'login'])->name('login.post');

    // ─── Self-signup ──────────────────────────────────────────
    Route::get('/register', [ClientAuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [ClientAuthController::class, 'register'])->name('register.post');
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [ClientAuthController::class, 'dashboard'])->name('dashboard');

        // ─── Card hub: New / Recent / Drafts ───────────────────
        Route::get('/cards', [CardManagerController::class, 'index'])->name('cards');
        Route::post('/cards', [CardManagerController::class, 'store'])->name('cards.store');
        Route::get('/cards/{card}/edit', [CardManagerController::class, 'edit'])->name('cards.edit');
        Route::patch('/cards/{card}/rename', [CardManagerController::class, 'rename'])->name('cards.rename');
        Route::patch('/cards/{card}/link', [CardManagerController::class, 'toggleLink'])->name('cards.link.toggle');
        Route::delete('/cards/{card}', [CardManagerController::class, 'destroy'])->name('cards.destroy');

        // ─── Subscription request (no payment step yet) ────────
        Route::post('/subscription/request', [CardManagerController::class, 'requestSubscription'])->name('subscription.request');
        Route::get('/profile', [ClientAuthController::class, 'profile'])->name('profile');
        Route::get('/settings', [ClientAuthController::class, 'settings'])->name('settings');
        Route::post('/settings/password', [ClientAuthController::class, 'updatePassword'])->name('settings.password');
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');

        // ─── Birthday Card Builder (wizard) ────────────────────
        Route::post('/card/step1', [BirthdayCardController::class, 'saveStep1'])->name('card.step1');
        Route::post('/card/step2', [BirthdayCardController::class, 'saveStep2'])->name('card.step2');
        Route::post('/card/step3', [BirthdayCardController::class, 'saveStep3'])->name('card.step3');
        Route::post('/card/step4', [BirthdayCardController::class, 'saveStep4'])->name('card.step4');
        Route::post('/card/step5', [BirthdayCardController::class, 'saveStep5'])->name('card.step5');
        Route::post('/card/step6', [BirthdayCardController::class, 'saveStep6'])->name('card.step6');
        Route::post('/card/step7', [BirthdayCardController::class, 'saveStep7'])->name('card.step7');
        // The girl Gift 3 clip goes up on its own, so a large video never
        // pushes the step's own request past the server's post_max_size.
        Route::post('/card/gift3-video', [BirthdayCardController::class, 'uploadGift3Video'])->name('card.gift3-video');
        // Save as Draft — names the card and records how far it got.
        Route::post('/card/draft', [BirthdayCardController::class, 'saveDraft'])->name('card.draft');
        Route::post('/card/step8', [BirthdayCardController::class, 'saveStep8'])->name('card.step8');
        Route::post('/card/step9', [BirthdayCardController::class, 'saveStep9'])->name('card.step9');
        Route::post('/card/step10', [BirthdayCardController::class, 'saveStep10'])->name('card.step10');
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

// ─── Gift Pages (New Structure) ────────────────────────────────
Route::get('/boy/page/{page}/{variant}/gift/{gift}/{giftPage}', function ($page, $variant, $gift, $giftPage) {
    $viewName = 'birthday.boy-page-' . $page . '-variant-' . $variant . '-gift-' . $gift . '-page-' . $giftPage;
    return view($viewName);
})->name('boy.page.gift');

Route::get('/girl/page/{page}/{variant}/gift/{gift}/{giftPage}', function ($page, $variant, $gift, $giftPage) {
    $viewName = 'birthday.girl-page-' . $page . '-variant-' . $variant . '-gift-' . $gift . '-page-' . $giftPage;
    return view($viewName);
})->name('girl.page.gift');

// ─── Music ─────────────────────────────────────────────────────
// Audio is served through the app rather than as a static file so that Range
// requests are always answered — without them the player cannot seek, and a
// story that starts a minute into a song never makes a sound. Public, because
// the recipient of a card is not logged in to anything.
Route::get('/music/{track}', [MusicStreamController::class, 'show'])->name('music.stream');

// ─── Public Story ──────────────────────────────────────────────
// The card a recipient opens from the generated link or QR. Every story has
// its own slug, so one route serves every client's card and each link opens
// only that client's configuration.
Route::prefix('c/{slug}')->name('story.')->group(function () {
    Route::get('/', [PublicStoryController::class, 'lock'])->name('lock');
    Route::post('/unlock', [PublicStoryController::class, 'unlock'])->name('unlock');
    Route::get('/welcome', [PublicStoryController::class, 'welcome'])->name('welcome');
    Route::get('/gifts', [PublicStoryController::class, 'gifts'])->name('gifts');
    Route::get('/gift/{gift}', [PublicStoryController::class, 'gift'])->name('gift');
    Route::get('/ending', [PublicStoryController::class, 'ending'])->name('ending');
});
