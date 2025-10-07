@extends('layouts.logged')

@section('title', 'Account Settings')
@php $pageTitle = 'Settings'; $pageBreadcrumb = 'Account Settings'; $pageSubtitle = 'Manage your account settings and set e-mail preferences.'; @endphp

@section('content')
    <div class="settings-page">
        @include('pages.settings.includes.head')
        <div class="settings-container container">
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
                            <h3>The basics</h3>
                            <p>Update your basic info below.</p>
                        </div>
                        <form method="post" action="{{ route('settings.index.update') }}">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div class="settings-content-section">
                        <div class="settings-content-section-header">
                            <h3>Change Password</h3>
                            <p>Update your password below.</p>
                        </div>
                        <form method="post" action="{{ route('settings.index.password_update') }}">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account Section -->
                    <div class="settings-content-section">
                        <div class="settings-content-section-header">
                            <h3>Delete Account</h3>
                            <p>Permanently delete your account below.</p>
                        </div>
                        <form method="POST" action="{{ route('settings.index.delete_account') }}" class="delete-account-form">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <label for="password">Confirm with Password</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection