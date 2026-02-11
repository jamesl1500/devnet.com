<?php

namespace App\Http\Controllers;

use App\Libraries\PostsLibrary;
use App\Models\Posts;

class DashboardController extends Controller
{
    /**
     * For you page
     */
    public function for_you()
    {
        $posts = $this->getForYouFeed();

        return view('pages.dashboard.for-you', compact('posts'));
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
        $posts = $this->getPopularFeed();

        return view('pages.dashboard.popular', compact('posts'));
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

    /**
     * Create a personalized feed based on trending posts.
     */
    private function getForYouFeed()
    {
        $posts = PostsLibrary::getPublicFeed(20, 'trending');

        if ($posts->count() === 0) {
            return PostsLibrary::getPublicFeed(20, 'latest');
        }

        return $posts;
    }

    /**
     * Create a popular feed based on reactions and comments.
     */
    private function getPopularFeed()
    {
        return PostsLibrary::getPublicFeed(20, 'popular');
    }
}
