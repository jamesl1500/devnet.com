@extends('layouts.onboarding')

@php
    $title = "Profile Setup";
@endphp

@section('content')
    <h1>Profile Setup</h1>
    <p>Please provide the following information to complete your profile setup.</p>

    <form action="{{ route('onboarding.profile.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <img src="" alt="Avatar">
            <label for="avatar">Upload avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/*">
        </div>
        <div>
            <img src="" alt="Cover Photo">
            <label for="cover_photo">Upload cover photo</label>
            <input type="file" id="cover_photo" name="cover_photo" accept="image/*">
        </div>
        <div>
            <label for="headline">Headline</label>
            <textarea id="headline" name="headline" required></textarea>
        </div>
        <div>
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" required></textarea>
        </div>
        <button type="submit">Continue</button>
    </form>
@endsection
