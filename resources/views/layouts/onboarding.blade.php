<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth" content="{{ Auth::check() }}">

    <meta name="onboarding" content="{{ Auth::check() && Auth::user()->is_onboarding }}">
    <meta name="onboarding-step" content="{{ Auth::check() ? Auth::user()->onboarding_step : '' }}">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="header-container">
    @include('templates.onboarding.header')
</div>
<div class="website-container">
    @yield('content')
</div>
<div class="footer-container">
    @include('templates.auth.footer')
</div>
</body>
</html>
