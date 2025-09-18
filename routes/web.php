<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\SettingsController;

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard
Route::get('dashboard', [DashboardController::class, 'following'])->middleware(['auth', 'verified', 'onboarded'])->name('dashboard.following');
Route::get('dashboard/popular', [DashboardController::class, 'popular'])->middleware(['auth', 'verified', 'onboarded'])->name('dashboard.popular');
Route::get('dashboard/for-you', [DashboardController::class, 'for_you'])->middleware(['auth', 'verified', 'onboarded'])->name('dashboard.for-you');

Route::middleware(['auth'])->group(function () {
    // Post routes
    Route::resource('posts', PostsController::class);

    // Settings routes 
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('settings/privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
    Route::get('settings/security', [SettingsController::class, 'security'])->name('settings.security');
});

require __DIR__.'/auth.php';
