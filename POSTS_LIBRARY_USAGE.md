# Posts Library Usage Examples

The `PostsLibrary` provides a comprehensive set of functions for managing posts in your Laravel application. Here are some common usage examples:

## Basic Usage

### Creating a Post

```php
use App\Libraries\PostsLibrary;

// Simple post creation
$post = PostsLibrary::create([
    'content' => 'This is my first post!',
    'visibility' => 'public'
]);

// Post with title and privacy settings
$post = PostsLibrary::create([
    'title' => 'My Blog Post',
    'content' => 'This is the content of my blog post.',
    'visibility' => 'followers', // public, private, followers
    'user_id' => auth()->id()    // Optional if user is authenticated
]);

// Post with media uploads
$post = PostsLibrary::create([
    'content' => 'Check out these photos!',
    'visibility' => 'public'
], $imageFiles, $attachmentFiles);
```

### Finding Posts

```php
// Find by ID
$post = PostsLibrary::find(1);

// Find by slug
$post = PostsLibrary::findBySlug('my-post-slug-123');

// Get user's posts
$posts = PostsLibrary::getUserPosts(
    $userId, 
    $perPage = 20, 
    $currentUserId = auth()->id()
);
```

### Getting Feeds

```php
// Get following feed
$followingPosts = PostsLibrary::getFollowingFeed(auth()->user());

// Get public feed (discover)
$publicPosts = PostsLibrary::getPublicFeed(20, 'latest');
$popularPosts = PostsLibrary::getPublicFeed(20, 'popular');
$trendingPosts = PostsLibrary::getPublicFeed(20, 'trending');
```

## Post Management

### Updating Posts

```php
$updatedPost = PostsLibrary::update($post, [
    'title' => 'Updated Title',
    'content' => 'Updated content',
    'visibility' => 'private'
]);
```

### Changing Privacy

```php
$post = PostsLibrary::changePrivacy($post, 'private');
```

### Deleting Posts

```php
// Soft delete
$success = PostsLibrary::delete($post);

// Force delete (permanent)
$success = PostsLibrary::delete($post, true);
```

## Reactions & Engagement

### Managing Reactions

```php
// Add or toggle a like
$result = PostsLibrary::toggleReaction($post, 'like');

// Add other reaction types
$result = PostsLibrary::toggleReaction($post, 'love');
$result = PostsLibrary::toggleReaction($post, 'laugh');

// Check user's reaction
$userReaction = PostsLibrary::getUserReaction($post, auth()->id());

// Get reaction counts
$reactionCounts = PostsLibrary::getReactionCount($post);
// Returns: ['like' => 5, 'love' => 2, 'laugh' => 1]
```

### Sharing Posts

```php
$sharedPost = PostsLibrary::share($originalPost, [
    'content' => 'Check this out!', // Optional comment
    'visibility' => 'public'
]);
```

## Search & Discovery

### Searching Posts

```php
// Basic search
$results = PostsLibrary::search('Laravel tips');

// Advanced search with filters
$results = PostsLibrary::search('development', [
    'user_id' => 123,
    'type' => 'text',
    'visibility' => 'public',
    'date_from' => '2024-01-01',
    'date_to' => '2024-12-31'
]);
```

### Hashtag Features

```php
// Get trending hashtags
$trendingTags = PostsLibrary::getTrendingHashtags(10, 7); // top 10 from last 7 days

// Get posts by hashtag
$posts = PostsLibrary::getPostsByHashtag('laravel');
```

## Analytics

### Post Statistics

```php
$stats = PostsLibrary::getPostStats($post);
/*
Returns:
[
    'views' => 0,
    'likes' => 15,
    'comments' => 3,
    'shares' => 2,
    'reactions' => ['like' => 15, 'love' => 5],
    'engagement_rate' => 0
]
*/
```

## Controller Integration

### In your PostsController

```php
<?php

namespace App\Http\Controllers;

use App\Libraries\PostsLibrary;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:5000',
            'visibility' => 'required|in:public,private,followers',
            'images.*' => 'nullable|image|max:10240',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        try {
            $post = PostsLibrary::create(
                $validated,
                $request->file('images'),
                $request->file('attachments')
            );

            return response()->json([
                'success' => true,
                'post' => $post
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleReaction(Request $request, $postId)
    {
        $post = PostsLibrary::find($postId);
        
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $result = PostsLibrary::toggleReaction(
            $post, 
            $request->input('type', 'like')
        );

        return response()->json($result);
    }
}
```

## Livewire Integration

### In a Livewire Component

```php
<?php

namespace App\Livewire;

use App\Libraries\PostsLibrary;
use Livewire\Component;

class PostFeed extends Component
{
    public $posts;
    public $sortBy = 'latest';

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $this->posts = PostsLibrary::getPublicFeed(20, $this->sortBy);
    }

    public function toggleReaction($postId, $type = 'like')
    {
        $post = PostsLibrary::find($postId);
        
        if ($post) {
            PostsLibrary::toggleReaction($post, $type);
            $this->loadPosts(); // Refresh the feed
        }
    }

    public function render()
    {
        return view('livewire.post-feed');
    }
}
```

## Error Handling

The library throws exceptions for various error conditions:

```php
try {
    $post = PostsLibrary::create([
        // Missing required content
    ]);
} catch (Exception $e) {
    // Handle: "Post content is required"
}

try {
    $post = PostsLibrary::update($someoneElsesPost, $data);
} catch (Exception $e) {
    // Handle: "Unauthorized to edit this post"
}

try {
    $post = PostsLibrary::delete($protectedPost);
} catch (Exception $e) {
    // Handle: "Unauthorized to delete this post"
}
```

## Privacy & Security

The library automatically handles:

- **User authentication** - Checks if user is logged in for protected operations
- **Authorization** - Users can only edit/delete their own posts
- **Privacy filtering** - Respects post visibility settings when fetching posts
- **File uploads** - Properly stores and associates media files
- **Input validation** - Validates required fields and data types

## Performance Considerations

- Use eager loading: The library loads relationships (`user`, `media`, `comments`) by default
- Pagination: Most methods return paginated results to handle large datasets
- Caching: Consider caching trending hashtags and popular posts
- Database indexing: Ensure proper indexes on `user_id`, `visibility`, `status`, and `created_at` columns