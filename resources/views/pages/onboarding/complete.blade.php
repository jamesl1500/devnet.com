@extends('layouts.onboarding')

@section('title', 'Complete Profile')

@section('content')
    <h1>Profile Complete</h1>
    <p>Thank you for completing your profile! You are now ready to start using our platform.</p>

    <form action="{{ route('onboarding.complete.post') }}" method="POST">
        @csrf
        <button type="submit">Go to Dashboard</button>
    </form>
@endsection