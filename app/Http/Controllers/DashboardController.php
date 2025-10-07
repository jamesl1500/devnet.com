<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Posts;

class DashboardController extends Controller
{
    /**
     * For you page
     */
    public function for_you()
    {
        return view('pages.dashboard.for-you');
    }

    /**
     * Following page
     */
    public function following()
    {
        // Get feed
        $user = auth()->user();
        $posts = $this->getFollowingFeed($user);
        return view('pages.dashboard.following', compact('posts'));
    }

    /**
     * Popular page
     */
    public function popular()
    {
        return view('pages.dashboard.popular');
    }

    /**
     * Create "following" feed based on who the user follows and their posts
     */
    private function getFollowingFeed($user)
    {
        // Get IDs of users the current user follows
        $followedUserIds = $user->following()->pluck('followable_id')->toArray();

        // Include the user's own ID to see their posts as well
        $followedUserIds[] = $user->id;

        // Fetch posts from followed users
        $posts = Posts::whereIn('user_id', $followedUserIds)
            ->with(['user', 'media', 'comments'])
            ->latest()
            ->paginate(20);

        return $posts;
    }
}
