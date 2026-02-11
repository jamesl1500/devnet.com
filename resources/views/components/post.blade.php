<?php
if(!isset($post)) {
    throw new Exception("Post component requires a 'post' parameter.");
}

// Get file model for avatar
use \App\Models\Files;
use \App\Libraries\PostLikes;

$avatar = Files::find($post->user->avatar_id);
$avatarPath = $avatar ? asset('storage/' . $avatar->file_path) : asset('images/default-avatar.png');

// Optional: Check if user can interact with post (for future features)
$canInteract = auth()->check();
?>
<article class="post" id="post-<?php echo $post->id; ?>" data-post-id="<?php echo $post->id; ?>">
    <header class="post-header">
        <div class="post-user-avatar">
            <img src="<?php echo $avatarPath; ?>" alt="<?php echo $post->user->name; ?>'s avatar" loading="lazy">
        </div>
        <div class="post-user-info">
            <h4 class="post-user-name"><?php echo $post->user->name; ?></h4>
            <time class="post-user-time" datetime="<?php echo $post->created_at->toISOString(); ?>" title="<?php echo $post->created_at->format('F j, Y \a\t g:i A'); ?>">
                <?php echo $post->created_at->diffForHumans(); ?>
            </time>
        </div>
    </header>
    <div class="post-content">
        <?php
            if($post->title !== null && trim($post->title) !== '') 
            {
                ?>
                <h3 class="post-title"><?php echo $post->title; ?></h3>
                <?php
            }
        ?>
        <p><?php echo $post->body; ?></p>
    </div>
    
    <?php 
        if($canInteract){
    ?>
    <footer class="post-actions">
        <?php
            $postLikes = new PostLikes();
            $likeCount = $postLikes->countLikes($post->id) ? $postLikes->countLikes($post->id) : 0;

            
            $commentCount = $post->comments ? $post->comments->count() : 0;
        ?>

        <?php
            $userHasLiked = false;
            if(auth()->check()) {
                $userHasLiked = $postLikes->hasUserLiked($post->id, auth()->user()->id);
            }

            if($userHasLiked) {
        ?>
            <button class="post-action" id="unlike-post-<?php echo $post->id; ?>" type="button" aria-label="Unlike post" data-action-type="unlike" data-liked="true" data-post-id="<?php echo $post->id; ?>">
                <span>Unlike</span>
                <span class="action-count">(<?php echo $likeCount; ?>)</span>
            </button>
        <?php } else { ?>
            <button class="post-action" id="like-post-<?php echo $post->id; ?>" type="button" aria-label="Like post" data-action-type="like" data-liked="false" data-post-id="<?php echo $post->id; ?>">
                <span>Like</span>
                <span class="action-count">(<?php echo $likeCount; ?>)</span>
            </button>
        <?php } ?>

        <button class="post-action" type="button" aria-label="Comment on post">
            <span>Comment</span>
            <span class="action-count">(<?php echo $commentCount; ?>)</span>
        </button>
        <button class="post-action right-float" type="button" aria-label="Share post">
            <span>Share</span>
            <span class="action-count">(0)</span>
        </button>
    </footer>
    <?php } ?>
</article>