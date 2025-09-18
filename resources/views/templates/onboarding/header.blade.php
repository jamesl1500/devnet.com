<div class="onboarding-header fixed-header">
    <div class="header-inner-container container">
        <div class="header-branding">
            <a href="{{ url('/') }}">
                <h1>Pyrt Devs</h1>
            </a>
        </div>
        <div class="header-menu">
            <ul>
                @auth
                    <li><a href="">{{ Auth::user()->name }}</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-link logout-button">Logout</button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>