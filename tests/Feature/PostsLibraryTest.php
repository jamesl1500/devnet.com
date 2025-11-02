<?php

use App\Libraries\PostsLibrary;
use App\Models\Posts;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('can create a post', function () {
    $this->actingAs($this->user);

    $postData = [
        'content' => 'This is a test post content',
        'title' => 'Test Post Title',
        'visibility' => 'public'
    ];

    $post = PostsLibrary::create($postData);

    expect($post)->toBeInstanceOf(Posts::class);
    expect($post->body)->toBe('This is a test post content');
    expect($post->title)->toBe('Test Post Title');
    expect($post->visibility)->toBe('public');
    expect($post->user_id)->toBe($this->user->id);
    expect($post->status)->toBe('published');
});

test('can find a post by id', function () {
    $this->actingAs($this->user);

    $post = PostsLibrary::create([
        'content' => 'Findable post content',
        'visibility' => 'public'
    ]);

    $foundPost = PostsLibrary::find($post->id);

    expect($foundPost)->not->toBeNull();
    expect($foundPost->id)->toBe($post->id);
    expect($foundPost->body)->toBe('Findable post content');
});

test('can get user posts', function () {
    $this->actingAs($this->user);

    // Create multiple posts for the user
    PostsLibrary::create(['content' => 'Post 1', 'visibility' => 'public']);
    PostsLibrary::create(['content' => 'Post 2', 'visibility' => 'public']);
    PostsLibrary::create(['content' => 'Post 3', 'visibility' => 'private']);

    $posts = PostsLibrary::getUserPosts($this->user->id, 10, $this->user->id);

    expect($posts->count())->toBe(3);
});

test('can toggle post reactions', function () {
    $this->actingAs($this->user);

    $post = PostsLibrary::create([
        'content' => 'Post to like',
        'visibility' => 'public'
    ]);

    // Add a like
    $result = PostsLibrary::toggleReaction($post, 'like');
    expect($result['status'])->toBe('created');

    // Toggle the same like (should remove)
    $result = PostsLibrary::toggleReaction($post, 'like');
    expect($result['status'])->toBe('removed');

    // Add a different reaction
    $result = PostsLibrary::toggleReaction($post, 'love');
    expect($result['status'])->toBe('created');
});

test('can change post privacy', function () {
    $this->actingAs($this->user);

    $post = PostsLibrary::create([
        'content' => 'Privacy test post',
        'visibility' => 'public'
    ]);

    $updatedPost = PostsLibrary::changePrivacy($post, 'private');

    expect($updatedPost->visibility)->toBe('private');
});

test('can update post content', function () {
    $this->actingAs($this->user);

    $post = PostsLibrary::create([
        'content' => 'Original content',
        'title' => 'Original title',
        'visibility' => 'public'
    ]);

    $updatedPost = PostsLibrary::update($post, [
        'content' => 'Updated content',
        'title' => 'Updated title'
    ]);

    expect($updatedPost->body)->toBe('Updated content');
    expect($updatedPost->title)->toBe('Updated title');
});

test('can search posts', function () {
    $this->actingAs($this->user);

    PostsLibrary::create(['content' => 'Laravel is awesome', 'visibility' => 'public']);
    PostsLibrary::create(['content' => 'PHP development tips', 'visibility' => 'public']);
    PostsLibrary::create(['content' => 'JavaScript tutorials', 'visibility' => 'public']);

    $results = PostsLibrary::search('Laravel');

    expect($results->count())->toBe(1);
    expect($results->first()->body)->toContain('Laravel');
});

test('can get post stats', function () {
    $this->actingAs($this->user);

    $post = PostsLibrary::create([
        'content' => 'Stats test post',
        'visibility' => 'public'
    ]);

    // Add some reactions
    PostsLibrary::toggleReaction($post, 'like');

    $stats = PostsLibrary::getPostStats($post);

    expect($stats)->toHaveKey('likes');
    expect($stats)->toHaveKey('comments');
    expect($stats)->toHaveKey('shares');
    expect($stats)->toHaveKey('reactions');
    expect($stats['likes'])->toBe(1);
});

test('validates required fields when creating post', function () {
    $this->actingAs($this->user);

    expect(fn() => PostsLibrary::create([]))
        ->toThrow(Exception::class, 'Post content is required');
});

test('prevents unauthorized post deletion', function () {
    $this->actingAs($this->user);
    $otherUser = User::factory()->create();

    $post = PostsLibrary::create([
        'content' => 'Protected post',
        'visibility' => 'public'
    ]);

    $this->actingAs($otherUser);

    expect(fn() => PostsLibrary::delete($post))
        ->toThrow(Exception::class, 'Unauthorized to delete this post');
});
