@extends('layouts.logged')

@section('title', 'Account Settings')
@php $pageTitle = 'Settings'; $pageBreadcrumb = 'Profile'; $pageSubtitle = 'Edit your profile to show who you are!'; @endphp

@section('content')
    <div class="settings-page">
        <div class="settings-container container">
            <div class="settings-header">
                <h2>{{ $pageBreadcrumb }}</h2>
                <p>{{ $pageSubtitle }}</p>
            </div>
            <div class="settings-content">
                @include('pages.settings.includes.sidebar')

                <div class="settings-main-content">
                    <!-- Alerts -->
                    @if (session('status') || $errors->any())
                        <x-form.alerts :type="session('status') ? 'success' : 'danger'" :message="session('status') ?: $errors->first()" :messages="$errors->all()" />
                    @endif

                    <!-- Basic Info Form -->
                    <div class="settings-content-section">
                        <div class="settings-content-section-header">
                            <h3>Avatar & Cover Photo</h3>
                            <p>Update your avatar and cover photo below.</p>
                        </div>
                        <form method="post" action="{{ route('settings.profile.update_avatar_cover') }}">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                @if($avatar)
                                    <img src="{{ asset('storage/' . $avatar->file_path) }}" alt="Current Avatar" class="current-avatar">
                                @endif
                                <label for="avatar">Avatar</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*">
                            </div>
                            <div class="form-group">
                                @if($cover)
                                    <img src="{{ asset('storage/' . $cover->file_path) }}" alt="Current Cover Photo" class="current-cover">
                                @endif
                                <label for="cover">Cover Photo</label>
                                <input type="file" id="cover" name="cover" accept="image/*">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection