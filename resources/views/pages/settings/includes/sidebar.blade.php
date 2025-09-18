<div class="settings-sidebar-links">
    <ul class="settings-menu">
        <li class="settings-menu-item {{ request()->is('settings') ? 'active' : '' }}">
            <a href="{{ route('settings.index') }}" class="settings-menu-link">
                Account
            </a>
        </li>
        <li class="settings-menu-item {{ request()->is('settings/profile') ? 'active' : '' }}">
            <a href="{{ route('settings.profile') }}" class="settings-menu-link">
                Profile
            </a>
        </li>
        <li class="settings-menu-item {{ request()->is('settings/security') ? 'active' : '' }}">
            <a href="{{ route('settings.notifications') }}" class="settings-menu-link">
                Notifications
            </a>
        </li>
        <li class="settings-menu-item {{ request()->is('settings/privacy') ? 'active' : '' }}">
            <a href="{{ route('settings.privacy') }}" class="settings-menu-link">
                Privacy
            </a>
        </li>
        <li class="settings-menu-item {{ request()->is('settings/security') ? 'active' : '' }}">
            <a href="{{ route('settings.security') }}" class="settings-menu-link">
                Security
            </a>
        </li>
    </ul>
</div>