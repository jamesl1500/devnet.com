<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard.feed', absolute: false).'?verified=1');
        }

        $request->fulfill();

        // User should then be ready to onboard
        if ($request->user()->is_onboarding) {
            return redirect()->route('onboarding.' . $request->user()->onboarding_step);
        }

        // If user isnt onboarding then redirect back to dashboard
        return redirect()->intended(route('dashboard.feed', absolute: false).'?verified=1');
    }
}
