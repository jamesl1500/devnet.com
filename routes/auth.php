<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');

    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');

    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');

    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

});

/**
 * Auth protected routes:
 * {get} verify-email
 * {post} email/verification-notification
 * {get} verify-email/{id}/{hash}
 * {get} onboarding/welcome
 * {post} onboarding/welcome
 * {get} onboarding/profile
 * {post} onboarding/profile
 * {get} onboarding/complete
 * {post} onboarding/complete
 */
Route::middleware('auth')->group(function () {
    // {get} verify-email 
    Route::get('verify-email', [AuthController::class, 'showVerifyEmailNotice'])->name('verification.notice');

    Route::post('email/verification-notification', [AuthController::class, 'sendVerificationEmail'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('onboarding/welcome', [AuthController::class, 'showOnboardingWelcome'])->middleware(['onboarding'])->name('onboarding.welcome');
    Route::post('onboarding/welcome', [AuthController::class, 'postOnboardingWelcome'])->middleware(['onboarding'])->name('onboarding.welcome.post');

    Route::get('onboarding/profile', [AuthController::class, 'showOnboardingProfile'])->middleware(['onboarding'])->name('onboarding.profile');
    Route::post('onboarding/profile', [AuthController::class, 'postOnboardingProfile'])->middleware(['onboarding'])->name('onboarding.profile.post');

    Route::get('onboarding/complete', [AuthController::class, 'showOnboardingComplete'])->middleware(['onboarding'])->name('onboarding.complete');
    Route::post('onboarding/complete', [AuthController::class, 'postOnboardingComplete'])->middleware(['onboarding'])->name('onboarding.complete.post');
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
