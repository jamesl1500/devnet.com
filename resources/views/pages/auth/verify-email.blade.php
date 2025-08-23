@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="">
        {{ __('Please verify your email address by clicking the link sent to your email.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <div class="">
            <div class="">
                <button>
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </div>
    </form>
@endsection