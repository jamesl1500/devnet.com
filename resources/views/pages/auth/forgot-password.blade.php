@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <h1>Forgot Password</h1>
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <button type="submit">Send Password Reset Link</button>
    </form>
@endsection