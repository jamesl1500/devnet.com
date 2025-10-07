<header class="header logged-in theme-system">
    <div class="header-inner container">
        <div class="header-row row">
            <div class="header-brand col">
                <a href="{{ route('dashboard.following') }}" class="header-logo">
                    <h2>{{ config('app.name') }}</h2>
                </a>
            </div>
            <div class="header-search col">
                <div class="search-wrapper">
                    <form action="" method="GET" class="header-search-form">
                        <input type="text" name="q" class="header-search-input" placeholder="Search...">
                    </form>
                    <div class="search-results">
                        <!-- Search results will be displayed here -->
                    </div>
                </div>
            </div>
            <div class="header-nav col">
                <nav class="header-navigation">
                    <ul class="header-nav-list">
                        <li class="header-nav-item">
                            <a href="" class="header-nav-link">
                                Dashboard
                            </a>
                        </li>
                        <li class="header-nav-item">
                            <a href="" class="header-nav-link">
                                Threads
                            </a>
                        </li>
                        <li class="header-nav-item">
                            <a href="" class="header-nav-link">
                                Groups
                            </a>
                        </li>
                        <li class="header-nav-item dropdown">
                            <a href="#" class="notifications-open nav-link-dropdown-toggle" data-d="notifications-dropdown">
                                <i class="fa-regular fa-bell"></i>
                            </a>
                        </li>
                        <li class="header-nav-item dropdown">
                            <a href="#" class="user-open nav-link-dropdown-toggle" data-d="header-nav-dropdown">
                                <i class="fa-regular fa-user"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="dropdowns">
                    <div class="header-nav-dropdown header-dropdown" id="header-nav-dropdown">
                        <ul class="header-nav-list">
                            <li class="header-nav-item">
                                <a href="#" class="header-nav-link">
                                    Profile
                                </a>
                            </li>
                            <li class="header-nav-item">
                                <a href="{{ route('settings.index') }}" class="header-nav-link">
                                    Settings
                                </a>
                            </li>
                            <li class="header-nav-item">
                                <a href="#" class="header-nav-link">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="notifications-dropdown header-dropdown" id="notifications-dropdown">
                        <ul class="notifications-list">
                            <li class="notifications-item">
                                <a href="#" class="notifications-link">
                                    New comment on your post
                                </a>
                            </li>
                            <li class="notifications-item">
                                <a href="#" class="notifications-link">
                                    New follower
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
</header>