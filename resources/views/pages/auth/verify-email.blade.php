@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="auth-page login">
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
                            <h2>Verify Email</h2>
                            <p>We need to verify your email address before you can continue.</p>
                        </div>
                        <div class="action-form">
                            @if (session('status') || $errors->any())
                                <x-form.alerts :type="session('status') ? 'success' : 'danger'" :message="session('status') ?: $errors->first()" :messages="$errors->all()" />
                            @endif
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="form-group">
                                    <button type="submit">Resend Verification Email</button>
                                </div>
                            </form>
                        </div>
                        <div class="action-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection