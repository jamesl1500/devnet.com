@extends('layouts.onboarding')

@section('content')
    <h1>Welcome to the Onboarding Process</h1>
    <p>We're glad to have you here! Let's get started.</p>

    <form action="{{ route('onboarding.welcome.post') }}" method="POST">
        @csrf
        <button type="submit">Continue</button>
    </form>
@endsection
