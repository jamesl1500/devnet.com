@extends('layouts.onboarding')

@php
    $title = "Profile Setup";
@endphp

@section('content')
    <div class="onboarding-page profile">
        <div class="onboarding-container container">
            <div class="onboarding-m-header">
                <h2>Welcome to Pyrt Devs!</h2>
                <p>Your journey starts here. Let's get you set up.</p>
            </div>
            <div class="onboarding-content">
                <div class="onboarding-progress-left">
                    <div class="progress-bar">
                        <div class="progress-step">
                            <div class="step-number">1</div>
                            <div class="step-label">Welcome</div>
                        </div>
                        <div class="progress-step completed">
                            <div class="step-number">2</div>
                            <div class="step-label">Profile Setup</div>
                        </div>
                        <div class="progress-step">
                            <div class="step-number">3</div>
                            <div class="step-label">Completed</div>
                        </div>
                    </div>
                </div>
                <div class="onboarding-info-right">
                    <div class="info-box">
                        <h3>Profile Setup</h3>
                        <p>Let's get to know you better! Please upload a profile picture and cover photo, and tell us a bit about yourself.</p>
                        <div class="onboarding-action">
                            @if (session('status') || $errors->any())
                                <x-form.alerts :type="session('status') ? 'success' : 'danger'" :message="session('status') ?: $errors->first()" :messages="$errors->all()" />
                            @endif
                            <form action="{{ route('onboarding.profile.post') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <img src="https://placehold.co/400" alt="Avatar" id="avatar_preview">
                                    <label for="avatar_photo">Upload avatar</label>
                                    <input type="file" id="avatar_photo" name="avatar_photo" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <img src="https://placehold.co/600x400" alt="Cover Photo" id="cover_photo_preview">
                                    <label for="cover_photo">Upload cover photo</label>
                                    <input type="file" id="cover_photo" name="cover_photo" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label for="headline">Headline</label>
                                    <textarea id="headline" name="headline" placeholder="Enter your headline" required>{{ old('headline') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="bio">Bio</label>
                                    <textarea id="bio" name="bio" placeholder="Tell us about yourself" required>{{ old('bio') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit">Continue</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
