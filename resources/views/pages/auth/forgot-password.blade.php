@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-page forgot-password">
    <div class="auth-container">
        <div class="image-left">
            <div class="image-cover">
                <div class="quote-bottom">
                    @php
                        [$message, $author] = explode('-', Illuminate\Foundation\Inspiring::quotes()->random(), 2);
                    @endphp
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
                        <h2>Forgot Password</h2>
                        <p>Enter your email to receive a password reset link.</p>
                    </div>
                    <div class="action-form">
                        @if (session('status') || $errors->any())
                            <x-form.alerts :type="session('status') ? 'success' : 'danger'" :message="session('status') ?: $errors->first()" :messages="$errors->all()" />
                        @endif
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="email" name="email" id="email" placeholder="Email" autofocus required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="full-width">Send Password Reset Link</button>
                                <a class="btn btn-full btn-trans btn-push-up" href="{{ route('login') }}">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection