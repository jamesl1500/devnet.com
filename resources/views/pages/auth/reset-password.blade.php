@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1>Reset Password</h1>
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="password_confirmation">Confirm New Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <button type="submit">Reset Password</button>
    </form>
@endsection