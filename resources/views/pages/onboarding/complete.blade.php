@extends('layouts.onboarding')

@section('title', 'Complete Profile')

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
                        <div class="progress-step">
                            <div class="step-number">1</div>
                            <div class="step-label">Welcome</div>
                        </div>
                        <div class="progress-step">
                            <div class="step-number">2</div>
                            <div class="step-label">Profile Setup</div>
                        </div>
                        <div class="progress-step completed">
                            <div class="step-number">3</div>
                            <div class="step-label">Completed</div>
                        </div>
                    </div>
                </div>
                <div class="onboarding-info-right">
                    <div class="info-box">
                        <h2>All Set!</h2>
                        <p>Congratulations on completing your profile setup! You're now ready to explore all that Pyrt Devs has to offer. Click the button below to head to your dashboard and start connecting with the community.</p>
                        <div class="onboarding-action"> 
                            <form action="{{ route('onboarding.complete.post') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <button type="submit">Go to Dashboard</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection