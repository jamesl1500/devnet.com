<?php
if(!isset($post)) {
    throw new Exception("Post component requires a 'post' parameter.");
}

// Get file model for avatar
use \App\Models\Files;

$avatar = Files::find($post->user->avatar_id);
$avatarPath = $avatar ? asset('storage/' . $avatar->file_path) : asset('images/default-avatar.png');

// Optional: Check if user can interact with post (for future features)
$canInteract = auth()->check();
?>
<article class="post" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
    <header class="post-header">
        <div class="post-user-avatar">
            <img src="{{ $avatarPath }}" alt="{{ $post->user->name }}'s avatar" loading="lazy">
        </div>
        <div class="post-user-info">
            <h4 class="post-user-name">{{ $post->user->name }}</h4>
            <time class="post-user-time" datetime="{{ $post->created_at->toISOString() }}" title="{{ $post->created_at->format('F j, Y \a\t g:i A') }}">
                {{ $post->created_at->diffForHumans() }}
            </time>
        </div>
    </header>
    <div class="post-content">
        <?php
            if($post->title !== null && trim($post->title) !== '') 
            {
                ?>
                <h3 class="post-title">{{ $post->title }}</h3>
                <?php
            }
        ?>
        <p>{{ $post->body }}</p>
    </div>
    
    {{-- Future: Post actions like like, comment, share --}}
    @if($canInteract)
    <footer class="post-actions">
        <button class="post-action" type="button" aria-label="Like post">
            <span>Like</span>
            <span class="action-count">(0)</span>
        </button>
        <button class="post-action" type="button" aria-label="Comment on post">
            <span>Comment</span>
            <span class="action-count">(0)</span>
        </button>
        <button class="post-action right-float" type="button" aria-label="Share post">
            <span>Share</span>
            <span class="action-count">(0)</span>
        </button>
    </footer>
    @endif
</article>