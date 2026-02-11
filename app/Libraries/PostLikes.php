<?php

/**
 * PostLikes Library
 *
 * Handles operations related to post likes.
 */

namespace App\Libraries;

class PostLikes
{
    /**
     * Post Likes Model
     */
    protected $postLikesModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postLikesModel = new \App\Models\PostLikes;
    }

    /**
     * add
     *
     * Adds a like to a post by a user.
     *
     * @param  int  $postId  The ID of the post to like.
     * @param  int  $userId  The ID of the user liking the post.
     * @return bool True on success, false on failure.
     */
    public function add(int $postId, int $userId): bool
    {
        // Make sure a like doesn't already exist
        $existingLike = $this->postLikesModel
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            return false; // Like already exists
        }

        // Add the like
        $this->postLikesModel->create([
            'post_id' => $postId,
            'user_id' => $userId,
        ]);

        return true;
    }

    /**
     * remove
     *
     * Removes a like from a post by a user.
     *
     * @param  int  $postId  The ID of the post to unlike.
     * @param  int  $userId  The ID of the user unliking the post.
     * @return bool True on success, false on failure.
     */
    public function remove(int $postId, int $userId): bool
    {
        $existingLike = $this->postLikesModel
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if (! $existingLike) {
            return false; // Like does not exist
        }

        $existingLike->delete();

        return true;
    }

    /**
     * countLikes
     *
     * Counts the number of likes for a given post.
     *
     * @param  int  $postId  The ID of the post.
     * @return int The number of likes.
     */
    public function countLikes(int $postId): int
    {
        return $this->postLikesModel
            ->where('post_id', $postId)
            ->count();
    }

    /**
     * get
     *
     * Retrieves all likes for a given post.
     *
     * @param  int  $postId  The ID of the post.
     * @return \Illuminate\Database\Eloquent\Collection Collection of PostLikes.
     */
    public function get(int $postId)
    {
        return $this->postLikesModel
            ->where('post_id', $postId)
            ->get();
    }

    /**
     * hasUserLiked
     *
     * Checks if a user has liked a given post.
     *
     * @param  int  $postId  The ID of the post.
     * @param  int  $userId  The ID of the user.
     * @return bool True if the user has liked the post, false otherwise.
     */
    public function hasUserLiked(int $postId, int $userId): bool
    {
        $existingLike = $this->postLikesModel
            ->where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        return $existingLike !== null;
    }
}
