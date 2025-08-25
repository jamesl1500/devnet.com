<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeedNavigation extends Component
{
    // Active tab
    public string $activeTab;

    /**
     * Create a new component instance.
     */
    public function __construct(string $activeTab = 'following')
    {
        $this->activeTab = $activeTab;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.dashboard.feed-navigation');
    }
}
