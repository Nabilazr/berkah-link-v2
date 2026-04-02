<?php

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── HALAMAN UTAMA ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/debug-migrate', function () {
    Artisan::call('migrate --force');
    return "Database Migrated Successfully!";
});
// ── DASHBOARD (bawaan Breeze) ─────────────────────────────────────────────────
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// ── DASHBOARD ─────────────────────────────────────────────────────────────────
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ── PROFILE (bawaan Breeze) ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── CAMPAIGN ──────────────────────────────────────────────────────────────────
Route::resource('campaigns', CampaignController::class);

// ── DONATION ──────────────────────────────────────────────────────────────────
Route::post('/donations', [DonationController::class, 'store'])
    ->name('donations.store');

Route::get('/donations/{donation}/invoice', [DonationController::class, 'invoice'])
    ->name('donations.invoice');

Route::get('/donations/history', [DonationController::class, 'history'])
    ->name('donations.history')
    ->middleware('auth');

// ── ADMIN ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // Campaign
    Route::patch('/campaigns/{campaign}/status', [AdminController::class, 'updateCampaignStatus'])->name('campaigns.status');
    Route::delete('/campaigns/{campaign}', [AdminController::class, 'destroyCampaign'])->name('campaigns.destroy');

    // Donasi
    Route::patch('/donations/{donation}/paid', [DonationController::class, 'markAsPaid'])->name('donations.markAsPaid');
    Route::delete('/donations/{donation}', [AdminController::class, 'destroyDonation'])->name('donations.destroy');
});

// ── WEBHOOK MAYAR (aktifkan saat production) ──────────────────────────────────
// Route::post('/donations/webhook/mayar', [DonationController::class, 'webhook'])
//      ->name('donations.webhook')
//      ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ── AUTH BREEZE (jangan dihapus!) ─────────────────────────────────────────────
require __DIR__.'/auth.php';