<?php

namespace App\Libraries;

use App\Models\Comments;
use App\Models\Files;
use App\Models\Follows;
use App\Models\Posts as PostModel;
use App\Models\Posts_media;
use App\Models\Reactions;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsLibrary
{
    /**
     * Create a new post
     *
     * @throws Exception
     */
    public static function create(array $data, ?array $images = null, ?array $attachments = null): PostModel
    {
        // Validate required fields
        if (empty($data['content'])) {
            throw new Exception('Post content is required');
        }

        if (empty($data['user_id']) && ! Auth::check()) {
            throw new Exception('User must be authenticated to create a post');
        }

        $userId = $data['user_id'] ?? Auth::id();

        // Create the post
        $post = new PostModel;
        $post->user_id = $userId;
        $post->title = $data['title'] ?? null;
        $post->body = $data['content'];
        $post->visibility = $data['privacy'] ?? $data['visibility'] ?? 'public';
        $post->status = $data['status'] ?? 'published';
        $post->type = $data['type'] ?? 'text';
        $post->group_id = $data['group_id'] ?? null;
        $post->cover_id = $data['cover_id'] ?? null;
        $post->original_post_id = $data['original_post_id'] ?? null;

        // Generate unique slug
        $post->slug = self::generateSlug($data['title'] ?? Str::limit($data['content'], 30));

        $post->save();

        // Handle media uploads
        if ($images) {
            self::handleImageUploads($post, $images);
        }

        if ($attachments) {
            self::handleFileUploads($post, $attachments);
        }

        return $post->fresh(['user', 'media', 'comments']);
    }

    /**
     * Find a post by ID with relationships
     */
    public static function find(int $postId, array $with = ['user', 'media', 'comments']): ?PostModel
    {
        return PostModel::with($with)->find($postId);
    }

    /**
     * Find post by slug
     */
    public static function findBySlug(string $slug, array $with = ['user', 'media', 'comments']): ?PostModel
    {
        return PostModel::with($with)->where('slug', $slug)->first();
    }

    /**
     * Get posts for a specific user
     *
     * @param  int|null  $currentUserId  - For privacy filtering
     */
    public static function getUserPosts(int $userId, int $perPage = 20, ?int $currentUserId = null): LengthAwarePaginator
    {
        $query = PostModel::with(['user', 'media', 'comments'])
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->latest();

        // Apply privacy filtering
        if ($currentUserId !== $userId) {
            $query = self::applyPrivacyFilter($query, $userId, $currentUserId);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get feed posts for authenticated user (following feed)
     */
    public static function getFollowingFeed(User $user, int $perPage = 20): LengthAwarePaginator
    {
        // Get IDs of users the current user follows
        $followedUserIds = $user->following()->pluck('followable_id')->toArray();

        // Include the user's own ID to see their posts as well
        $followedUserIds[] = $user->id;

        return PostModel::whereIn('user_id', $followedUserIds)
            ->with(['user', 'media', 'comments'])
            ->where('status', 'published')
            ->where('visibility', '!=', 'private')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get public posts (discover/popular feed)
     *
     * @param  string  $sortBy  - 'latest', 'popular', 'trending'
     */
    public static function getPublicFeed(int $perPage = 20, string $sortBy = 'latest'): LengthAwarePaginator
    {
        $query = PostModel::with(['user', 'media', 'comments'])
            ->where('status', 'published')
            ->where('visibility', 'public');

        switch ($sortBy) {
            case 'popular':
                // Sort by reaction count (requires aggregation)
                $query->withCount('reactions')
                    ->orderBy('reactions_count', 'desc')
                    ->orderBy('created_at', 'desc');
                break;
            case 'trending':
                // Posts from last 7 days sorted by engagement
                $query->where('created_at', '>=', now()->subDays(7))
                    ->withCount(['reactions', 'comments'])
                    ->orderByRaw('(reactions_count + comments_count) DESC')
                    ->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
        }

        return $query->paginate($perPage);
    }

    /**
     * Update post content and settings
     *
     * @throws Exception
     */
    public static function update(PostModel $post, array $data): PostModel
    {
        // Check authorization
        if (! self::canEdit($post, Auth::id())) {
            throw new Exception('Unauthorized to edit this post');
        }

        if (isset($data['title'])) {
            $post->title = $data['title'];
        }

        if (isset($data['content'])) {
            $post->body = $data['content'];
        }

        if (isset($data['visibility'])) {
            $post->visibility = $data['visibility'];
        }

        if (isset($data['status'])) {
            $post->status = $data['status'];
        }

        $post->save();

        return $post->fresh(['user', 'media', 'comments']);
    }

    /**
     * Delete a post
     *
     * @throws Exception
     */
    public static function delete(PostModel $post, bool $forceDelete = false): bool
    {
        // Check authorization
        if (! self::canDelete($post, Auth::id())) {
            throw new Exception('Unauthorized to delete this post');
        }

        // Delete associated media files
        foreach ($post->media as $media) {
            if ($media->file) {
                Storage::delete($media->file->file_path);
                $media->file->delete();
            }
            $media->delete();
        }

        // Delete reactions and comments
        $post->reactions()->delete();
        $post->comments()->delete();

        if ($forceDelete) {
            return $post->forceDelete();
        }

        return $post->delete();
    }

    /**
     * Add or toggle reaction on a post
     */
    public static function toggleReaction(PostModel $post, string $reactionType = 'like', ?int $userId = null): array
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            throw new Exception('User must be authenticated to react');
        }

        $existingReaction = Reactions::where([
            'user_id' => $userId,
            'reactable_type' => PostModel::class,
            'reactable_id' => $post->id,
        ])->first();

        if ($existingReaction) {
            if ($existingReaction->type === $reactionType) {
                // Remove reaction if same type
                $existingReaction->delete();

                return [
                    'status' => 'removed',
                    'reaction_count' => self::getReactionCount($post),
                ];
            } else {
                // Update reaction type
                $existingReaction->update(['type' => $reactionType]);

                return [
                    'status' => 'updated',
                    'reaction' => $existingReaction,
                    'reaction_count' => self::getReactionCount($post),
                ];
            }
        }

        // Create new reaction
        $reaction = Reactions::create([
            'user_id' => $userId,
            'reactable_type' => PostModel::class,
            'reactable_id' => $post->id,
            'type' => $reactionType,
        ]);

        return [
            'status' => 'created',
            'reaction' => $reaction,
            'reaction_count' => self::getReactionCount($post),
        ];
    }

    /**
     * Get reaction counts for a post
     */
    public static function getReactionCount(PostModel $post): array
    {
        return Reactions::where('reactable_type', PostModel::class)
            ->where('reactable_id', $post->id)
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    /**
     * Check if user has reacted to post
     *
     * @return string|null - reaction type or null
     */
    public static function getUserReaction(PostModel $post, ?int $userId = null): ?string
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return null;
        }

        $reaction = Reactions::where([
            'user_id' => $userId,
            'reactable_type' => PostModel::class,
            'reactable_id' => $post->id,
        ])->first();

        return $reaction?->type;
    }

    /**
     * Share/repost a post
     */
    public static function share(PostModel $originalPost, array $data = [], ?int $userId = null): PostModel
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            throw new Exception('User must be authenticated to share');
        }

        // Check if already shared
        $existingShare = PostModel::where([
            'user_id' => $userId,
            'original_post_id' => $originalPost->id,
            'type' => 'share',
        ])->first();

        if ($existingShare) {
            throw new Exception('Post already shared');
        }

        return self::create([
            'content' => $data['content'] ?? '',
            'user_id' => $userId,
            'type' => 'share',
            'original_post_id' => $originalPost->id,
            'visibility' => $data['visibility'] ?? 'public',
            'status' => 'published',
        ]);
    }

    /**
     * Change post privacy/visibility
     *
     * @throws Exception
     */
    public static function changePrivacy(PostModel $post, string $visibility): PostModel
    {
        if (! self::canEdit($post, Auth::id())) {
            throw new Exception('Unauthorized to modify this post');
        }

        $validVisibilities = ['public', 'private', 'followers'];
        if (! in_array($visibility, $validVisibilities)) {
            throw new Exception('Invalid visibility setting');
        }

        $post->visibility = $visibility;
        $post->save();

        return $post;
    }

    /**
     * Search posts
     */
    public static function search(string $query, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $postQuery = PostModel::with(['user', 'media', 'comments'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('body', 'LIKE', "%{$query}%");
            });

        // Apply filters
        if (isset($filters['user_id'])) {
            $postQuery->where('user_id', $filters['user_id']);
        }

        if (isset($filters['type'])) {
            $postQuery->where('type', $filters['type']);
        }

        if (isset($filters['visibility'])) {
            $postQuery->where('visibility', $filters['visibility']);
        } else {
            // Default to public posts for search
            $postQuery->where('visibility', 'public');
        }

        if (isset($filters['date_from'])) {
            $postQuery->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $postQuery->where('created_at', '<=', $filters['date_to']);
        }

        return $postQuery->latest()->paginate($perPage);
    }

    /**
     * Get trending hashtags from posts
     */
    public static function getTrendingHashtags(int $limit = 10, int $days = 7): Collection
    {
        $posts = PostModel::where('created_at', '>=', now()->subDays($days))
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->pluck('body');

        $hashtags = [];
        foreach ($posts as $content) {
            preg_match_all('/#([a-zA-Z0-9_]+)/', $content, $matches);
            foreach ($matches[1] as $hashtag) {
                $hashtag = strtolower($hashtag);
                $hashtags[$hashtag] = ($hashtags[$hashtag] ?? 0) + 1;
            }
        }

        arsort($hashtags);

        return collect($hashtags)->take($limit);
    }

    /**
     * Get posts by hashtag
     */
    public static function getPostsByHashtag(string $hashtag, int $perPage = 20): LengthAwarePaginator
    {
        $hashtag = ltrim($hashtag, '#');

        return PostModel::with(['user', 'media', 'comments'])
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where('body', 'LIKE', "%#{$hashtag}%")
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get post analytics/stats
     */
    public static function getPostStats(PostModel $post): array
    {
        return [
            'views' => 0, // Would need views tracking
            'likes' => $post->reactions()->where('type', 'like')->count(),
            'comments' => $post->comments()->count(),
            'shares' => PostModel::where('original_post_id', $post->id)->count(),
            'reactions' => self::getReactionCount($post),
            'engagement_rate' => 0, // Calculate based on views if available
        ];
    }

    /**
     * Handle image uploads for a post
     */
    private static function handleImageUploads(PostModel $post, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $file = Files::create([
                    'user_id' => $post->user_id,
                    'file_name' => $image->getClientOriginalName(),
                    'file_path' => $image->store("posts/{$post->id}/images"),
                    'file_type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                ]);

                Posts_media::create([
                    'post_id' => $post->id,
                    'file_id' => $file->id,
                    'type' => 'image',
                    'order' => $index,
                ]);
            }
        }
    }

    /**
     * Handle file uploads for a post
     */
    private static function handleFileUploads(PostModel $post, array $attachments): void
    {
        foreach ($attachments as $index => $file) {
            if ($file instanceof UploadedFile) {
                $fileRecord = Files::create([
                    'user_id' => $post->user_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $file->store("posts/{$post->id}/files"),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                Posts_media::create([
                    'post_id' => $post->id,
                    'file_id' => $fileRecord->id,
                    'type' => 'file',
                    'order' => $index,
                ]);
            }
        }
    }

    /**
     * Apply privacy filtering to query
     *
     * @return mixed
     */
    private static function applyPrivacyFilter($query, int $targetUserId, ?int $currentUserId = null)
    {
        if (! $currentUserId) {
            // Guest users can only see public posts
            return $query->where('visibility', 'public');
        }

        if ($currentUserId === $targetUserId) {
            // User can see all their own posts
            return $query;
        }

        // Check if current user follows target user
        $isFollowing = Follows::where([
            'follower_id' => $currentUserId,
            'followable_type' => User::class,
            'followable_id' => $targetUserId,
        ])->exists();

        if ($isFollowing) {
            // Followers can see public and followers-only posts
            return $query->whereIn('visibility', ['public', 'followers']);
        }

        // Non-followers can only see public posts
        return $query->where('visibility', 'public');
    }

    /**
     * Generate unique slug for post
     */
    private static function generateSlug(string $title): string
    {
        $baseSlug = Str::slug($title) ?: 'post';
        $slug = $baseSlug;
        $counter = 1;

        while (PostModel::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        // Add some randomness for security
        return substr(Crypt::encrypt($slug), 0, 100);
    }

    /**
     * Check if user can edit post
     */
    private static function canEdit(PostModel $post, ?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        // Only post owner can edit
        return $post->user_id === $userId;
    }

    /**
     * Check if user can delete post
     */
    private static function canDelete(PostModel $post, ?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        // Post owner can delete, also check for admin role if implemented
        return $post->user_id === $userId;
    }
}
