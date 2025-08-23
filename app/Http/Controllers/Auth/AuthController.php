<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Files;
use App\Models\Profiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Pest\Plugins\Profile;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('pages.auth.register');
    }

    // Show Login form
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attach profile
        Profiles::create([
            'user_id' => $user->id,
        ]);

        // Fire registered event
        event(new Registered($user));

        // Login user
        Auth::login($user);

        // Redirect to verification notice
        return redirect()->route('verification.notice');
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard.feed');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Show Forgot Password form
    public function showForgotPasswordForm()
    {
        return view('pages.auth.forgot-password');
    }

    // Send Password Reset Link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Show Reset Password form
    public function showResetPasswordForm($token)
    {
        return view('pages.auth.reset-password', ['token' => $token]);
    }

    // Handle Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showVerifyEmailNotice()
    {
        if(Auth::check()) {
            if(Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('dashboard');
            }

            return view('pages.auth.verify-email');
        }

        return redirect()->route('login');
    }

    public function sendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Onboarding page: Welcome
     */
    public function showOnboardingWelcome()
    {
        if (Auth::check() && Auth::user()->is_onboarding) {
            // Make sure user is on the correct onboarding step
            if (Auth::user()->onboarding_step !== 'welcome') {
                return redirect()->route('onboarding.' . Auth::user()->onboarding_step);
            }

            return view('pages.onboarding.welcome');
        }

        return redirect()->route('login');
    } 

    /**
     * Onboarding page: Welcome POST
     */
    public function postOnboardingWelcome(Request $request)
    {
        // Ensure user is logged in, in onboarding, and on right step
        if (Auth::check() && Auth::user()->is_onboarding && Auth::user()->onboarding_step === 'welcome') {
            $user = Auth::user();
            $user->onboarding_step = 'profile';

            // If save succeeds then move user to next step
            if ($user->save()) {
                return redirect()->route('onboarding.profile');
            }
        }

        return redirect()->route('login');
    }

    /**
     * Onboarding page: Profile
     */
    public function showOnboardingProfile()
    {
        if (Auth::check() && Auth::user()->is_onboarding) {
            // Make sure user is on the correct onboarding step
            if (Auth::user()->onboarding_step !== 'profile') {
                return redirect()->route('onboarding.' . Auth::user()->onboarding_step);
            }

            return view('pages.onboarding.profile');
        }

        return redirect()->route('login');
    }

    /**
     * Onboarding page: Profile POST
     */
    public function postOnboardingProfile(Request $request)
    {
        // Ensure user is logged in, in onboarding, and on right step
        if (Auth::check() && Auth::user()->is_onboarding && Auth::user()->onboarding_step === 'profile') {
            // Handle the POST request for the welcome step
            $user = Auth::user();

            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'headline' => 'required|string|max:255',
                'bio' => 'required|string|max:500',
            ]);

            // Handle file upload for avatar
            if($request->hasFile('avatar')) {
                // Create file modal for the new file
                $avatar = Files::create([
                    'user_id' => $user->id,
                    'file_name' => $request->file('avatar')->getClientOriginalName(),
                    'file_path' => $request->file('avatar')->store('avatars', 'public'),
                    'file_type' => $request->file('avatar')->getClientMimeType(),
                    'file_size' => $request->file('avatar')->getSize()
                ]);

                $user->avatar_id = $avatar->id;
            }

            // Handle file upload for cover photo
            if($request->hasFile('cover_photo')) {
                // Create file modal for the new file
                $coverPhoto = Files::create([
                    'user_id' => $user->id,
                    'file_name' => $request->file('cover_photo')->getClientOriginalName(),
                    'file_path' => $request->file('cover_photo')->store('cover_photos', 'public'),
                    'file_type' => $request->file('cover_photo')->getClientMimeType(),
                    'file_size' => $request->file('cover_photo')->getSize()
                ]);

                $user->cover_id = $coverPhoto->id;
            }

            // Update bio
            $user->bio = $request->input('bio');
            
            // Update headline
            $user->headline = $request->input('headline');

            $user->onboarding_step = 'complete';

            if($user->save()) {
                return redirect()->route('onboarding.complete');
            }
        }

        return redirect()->route('login');
    }

    /**
     * Onboarding page: Complete
     */
    public function showOnboardingComplete()
    {
        if (Auth::check() && Auth::user()->is_onboarding) {
            // Make sure user is on the correct onboarding step
            if (Auth::user()->onboarding_step !== 'complete') {
                return redirect()->route('onboarding.' . Auth::user()->onboarding_step);
            }

            return view('pages.onboarding.complete');
        }

        return redirect()->route('login');
    }

    /**
     * Onboarding page: Complete POST
     */
    public function postOnboardingComplete(Request $request)
    {
        // Ensure user is logged in, in onboarding, and on right step
        if (Auth::check() && Auth::user()->is_onboarding && Auth::user()->onboarding_step === 'complete') {
            // Handle the POST request for the complete step
            $user = Auth::user();

            // Update is_onboarding
            $user->is_onboarding = false;

            if($user->save()) {
                return redirect()->route('dashboard.feed');
            }
        }

        return redirect()->route('login');
    }
}