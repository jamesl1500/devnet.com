@extends('layouts.logged')

@section('title', 'Account Settings')
@php $pageTitle = 'Settings'; $pageBreadcrumb = 'Account Settings'; $pageSubtitle = 'Manage your account settings and set e-mail preferences.'; @endphp

@section('content')
    <div class="settings-page">
        <div class="settings-container container">
            <div class="settings-header">
                <h2>Account Settings</h2>
                <p>Manage your account settings and set e-mail preferences.</p>
            </div>
            <div class="settings-content">
                @include('pages.settings.includes.sidebar')

                <div class="settings-main-content">
                    
                </div>
            </div>
        </div>
    </div>
@endsection