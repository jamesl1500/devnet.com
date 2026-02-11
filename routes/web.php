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
    Route::post('posts/create', [PostsController::class, 'store'])->name('posts.store');
    Route::get('posts/view/{post_id}/{post_slug}', [PostsController::class, 'show'])->name('posts.show');
    
    Route::post('posts/{post_id}/like', [PostsController::class, 'like'])->name('posts.like');
    Route::post('posts/{post_id}/unlike', [PostsController::class, 'unlike'])->name('posts.unlike');

    // API route for post creation modal
    Route::get('api/post-creation-modal', function () {
        return view('components.dashboard.post-creation', ['modal' => true]);
    })->name('api.post-creation-modal');

    // Settings routes 
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/update/basic_information', [SettingsController::class, 'index_post'])->name('settings.index.update');
    Route::post('settings/update/password', [SettingsController::class, 'update_password'])->name('settings.index.password_update');
    Route::post('settings/update/delete_user', [SettingsController::class, 'delete_account'])->name('settings.index.delete_account');

    Route::get('settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::post('settings/profile/update_avatar_cover', [SettingsController::class, 'update_avatar_cover'])->name('settings.profile.update_avatar_cover');

    Route::get('settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::post('settings/notifications/update', [SettingsController::class, 'update_notifications'])->name('settings.notifications.update');
    
    Route::get('settings/privacy', [SettingsController::class, 'privacy'])->name('settings.privacy');
    Route::post('settings/privacy/update', [SettingsController::class, 'update_privacy'])->name('settings.privacy.update');
    
    Route::get('settings/security', [SettingsController::class, 'security'])->name('settings.security');
});

require __DIR__.'/auth.php';
