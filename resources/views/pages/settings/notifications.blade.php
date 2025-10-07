@extends('layouts.logged')

@section('title', 'Notification Settings')
@php $pageTitle = 'Settings'; $pageBreadcrumb = 'Notifications'; $pageSubtitle = 'Manage your notification settings and preferences.'; @endphp

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

                    <?php
                    // Notification Settings Form
                    foreach($notifications_settings_type as $type => $type_info) {
                        ?>
                        <div class="settings-content-section">
                            <div class="settings-content-section-header">
                                <h3>{{ $type_info['label'] }}</h3>
                                <p>Manage your {{ strtolower($type_info['label']) }} notification settings below.</p>
                            </div>
                            <form method="post" action="{{ route('settings.notifications.update') }}">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type_info['key'] }}">

                                <?php
                                // Determine if setting is enabled
                                $is_enabled = $notification_settings->{$type_info['key']};
                                ?>
                                <div class="form-group">
                                    <?php
                                    if($is_enabled) {
                                    ?>
                                        <button type="submit" name="value" value="0" class="btn btn-danger">Disable {{ strtolower($type_info['label']) }}</button>
                                    <?php
                                    } else {
                                    ?>
                                        <button type="submit" name="value" value="1" class="btn btn-secondary">Enable {{ strtolower($type_info['label']) }}</button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
@endsection