@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-page register">
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
                        <h2>Register</h2>
                        <p>Create your account! Please fill in the details below.</p>
                    </div>
                    <div class="action-form">
                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            <div class="form-group double-sided">
                                <input type="text" name="name" id="name" placeholder="Name" required>
                                <input type="text" name="username" id="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" id="email" placeholder="Email" required>
                            </div>
                            <div class="form-group double-sided">
                                <input type="password" name="password" id="password" placeholder="Password" required>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="full-width">Register</button>
                                <a class="btn btn-full btn-trans btn-push-up" href="{{ route('login') }}">Already have an account? Login</a>
                            </div>
                        </form>
                        <div class="oauth-login">
                            <p>Or register with:</p>
                            <div class="oauth-buttons">
                                <a class="btn btn-full btn-trans github-login" href="{{ route('login.github') }}"><i class="fa-brands fa-github"></i> GitHub</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection