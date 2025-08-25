<div class="feed-navigation-container">
    <div class="feed-navigation-inner">
        <ul>
            <li class="{{ $activeTab === 'following' ? 'active' : '' }}"><a href="{{ route('dashboard.following') }}">Following</a></li>
            <li class="{{ $activeTab === 'for-you' ? 'active' : '' }}"><a href="{{ route('dashboard.for-you') }}">For You</a></li>
            <li class="{{ $activeTab === 'popular' ? 'active' : '' }}"><a href="{{ route('dashboard.popular') }}">Popular</a></li>
        </ul>
    </div>
</div>