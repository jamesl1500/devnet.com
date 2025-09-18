@extends('layouts.onboarding')

@section('content')
    <div class="onboarding-page welcome">
        <div class="onboarding-container container">
            <div class="onboarding-m-header">
                <h2>Welcome to Pyrt Devs!</h2>
                <p>Your journey starts here. Let's get you set up.</p>
            </div>
            <div class="onboarding-content">
                <div class="onboarding-progress-left">
                    <div class="progress-bar">
                        <div class="progress-step completed">
                            <div class="step-number">1</div>
                            <div class="step-label">Welcome</div>
                        </div>
                        <div class="progress-step">
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
                        <h3>Getting Started</h3>
                        <p>We're excited to have you on board! This onboarding process will help you set up your profile and preferences to get the most out of Pyrt Devs.</p>
                        <ul>
                            <li>Step 1: Welcome - You're here!</li>
                            <li>Step 2: Profile Setup - Tell us about yourself.</li>
                            <li>Step 3: Completed - You're all done! Enjoy.</li>
                        </ul>
                        <p>If you need any assistance, feel free to reach out to our support team.</p>
                        <div class="onboarding-action"> 
                            <form action="{{ route('onboarding.welcome.post') }}" method="POST">
                                @csrf
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
