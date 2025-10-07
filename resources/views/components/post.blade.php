<?php
if(!isset($post)) {
    throw new Exception("Post component requires a 'post' parameter.");
}
print_r($post);
return false;
?>
<div class="post" id="post-{{ $post->id }}">
    <div class="post-header">
        <div class="post-user-avatar">
            <?php
                $avatarPath = $post->user->avatar_id ?? '';
            ?>
            <img src="{{ $avatarPath }}" alt="{{ $post->user->name }}'s avatar">
        </div>
        <div class="post-user-info">
            <h4 class="post-user-name">{{ $post->user->name }}</h4>
            <p class="post-user-time">{{ $post->created_at->diffForHumans() }}</p>
        </div>
    </div>
    <div class="post-content">
        <p>{{ $post->content }}</p>
    </div>
</div>