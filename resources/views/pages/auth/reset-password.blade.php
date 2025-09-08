@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-page forgot-password">
    <div class="auth-container">
        <div class="image-left">
            <div class="image-cover">
                <div class="quote-bottom">
                    <?php
                        // Random generated quote
                        [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                    ?>
                    <blockquote>
                        <p>"{{ $message }}" - {{ $author }}</p>
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="action-right">
            <div class="inner-action-right">
                <div class="action-container">
                    <div class="action-header">
                        <h2>Reset Password</h2>
                        <p>Please enter your new password below.</p>
                    </div>
                    <div class="action-form">
                        @if (session('status') || $errors->any())
                            <x-form.alerts :type="session('status') ? 'success' : 'danger'" :message="session('status') ?: $errors->first()" :messages="$errors->all()" />
                        @endif
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" id="email" placeholder="Email" value="{{ request()->input('email') }}" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" placeholder="New Password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm New Password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit">Reset Password</button>
                                <input type="hidden" name="token" value="{{ $token }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection