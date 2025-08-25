<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostsController;

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
    Route::redirect('settings', 'settings/profile');

    // Post routes
    Route::resource('posts', PostsController::class);

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
