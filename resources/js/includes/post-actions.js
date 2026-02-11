/**
 * Post Actions JS
 * ----
 * Handles post actions such as like, share, and comment.
 */
document.addEventListener('DOMContentLoaded', () => {
    /**
     * Like Post
     * ----
     * Toggles the like status of a post.
     * 
     * @param {Event} event - The click event.
     */
    const likePost = (event) => {
        const button = event.currentTarget;
        const postId = button.dataset.postId;
        const isLiked = button.dataset.actionType === 'like';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Checks
        if(!postId) {
            console.error('Post ID is missing.');
            return;
        }

        if(isLiked) {
            // Simulate API call to like the post
            console.log(`Liking post with ID: ${postId}`);
            const api = fetch(`/posts/${postId}/like`, { 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            if(!api) {
                console.error('Failed to like the post.');
                return;
            }    

            // Update button state
            const apiResponse = api.then(response => response.json());
            apiResponse.then(data => {
                if(data.success) {
                    button.dataset.actionType = 'unlike';
                    button.textContent = `Unlike (${data.likesCount})`;
                } else {
                    console.error('Failed to like the post:', data.message);
                }
            }).catch(error => {
                console.error('Error liking the post:', error);
            });
        }
    };

    const actionButtons = document.querySelectorAll('.post-action');
    actionButtons.forEach(button => {
        if(button.dataset.actionType === 'like' || button.dataset.actionType === 'unlike') {
            button.addEventListener('click', likePost);
        }
    });
});