@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-page login">
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
                            <h2>Login</h2>
                            <p>Welcome back! Please enter your credentials.</p>
                        </div>
                        <div class="action-form">
                            @if (session('status') || $errors->any())
                                <x-form.alerts :type="session('status') ? 'success' : 'danger'" :message="session('status') ?: $errors->first()" :messages="$errors->all()" />
                            @endif
                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email" id="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit">Login</button><br />
                                    <a class="btn btn-full btn-trans btn-push-up" href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>
                            </form>
                            <div class="oauth-login">
                                <p>Or login with:</p>
                                <div class="oauth-buttons">
                                    <a class="btn btn-full btn-trans github-login" href="{{ route('login.github') }}"><i class="fa-brands fa-github"></i> GitHub</a>
                                </div>
                            </div>
                        </div>
                        <div class="action-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Show errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    </div>
@endsection