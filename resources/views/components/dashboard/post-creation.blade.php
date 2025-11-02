@props(['modal' => false])

<div class="post-creation-container {{ $modal ? 'modal-mode' : '' }}" id="post-creation-container">
    @if($modal)
    <div class="post-creation-header">
        <div class="header-content">
            <h3>Create a Post</h3>
            <button type="button" class="close-modal" onclick="closePostModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Collapsed State (looks like a post) -->
    <div class="post-creation-collapsed" id="post-creation-collapsed">
        <div class="post-header">
            <div class="post-user-avatar">
                <?php
                $avatarPath = '';

                use \App\Models\Files as File;

                $avatar = File::find(auth()->user()->avatar_id);
                if ($avatar) {
                    $avatarPath = asset('storage/' . $avatar->file_path);
                } else {
                    $avatarPath = asset('images/default-avatar.png');
                }
                ?>
                <img src="{{ $avatarPath }}" alt="{{ auth()->user()->name }}" loading="lazy">
            </div>
            <div class="post-user-info">
                <h3 class="post-user-name">{{ auth()->user()->name }}</h3>
                <p class="post-user-time">{{ '@' . auth()->user()->username }}</p>
            </div>
        </div>
        
        <div class="post-content">
            <div class="post-creation-prompt">
                <span class="prompt-text">What's on your mind? Share your thoughts, ideas, or experiences...</span>
                <i class="fa-solid fa-pen prompt-icon"></i>
            </div>
        </div>
        
        <div class="post-actions">
            <div class="post-action">
                <i class="fa-solid fa-image"></i>
                <span>Photo</span>
            </div>
            <div class="post-action">
                <i class="fa-solid fa-paperclip"></i>
                <span>File</span>
            </div>
            <div class="post-action">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Poll</span>
            </div>
            <div class="post-action">
                <i class="fa-solid fa-robot"></i>
                <span>AI Help</span>
            </div>
        </div>
    </div>
    
    <!-- Expanded State (full form) -->
    <div class="post-creation-inner" id="post-creation-inner" style="display: none;">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="post-creation-form">
            @csrf
            @method('POST')
            
            <div class="form-group">
                <div class="user-hold">
                    <div class="user-avatar">
                        <?php
                        $avatarPath = '';
                        
                        use \App\Models\Files;

                        $avatar = Files::find(auth()->user()->avatar_id);
                        if ($avatar) {
                            $avatarPath = asset('storage/' . $avatar->file_path);
                        } else {
                            $avatarPath = asset('images/default-avatar.png');
                        }
                        ?>
                        <img src="{{ $avatarPath }}" alt="{{ auth()->user()->name }}" loading="lazy">
                    </div>
                    <div class="user-info">
                        <div class="user-name-text">
                            <h3>{{ auth()->user()->name }}</h3>
                            <h5>{{ '@' . auth()->user()->username }}</h5>
                        </div>
                        <div class="privacy-dropdown">
                            <select name="post_privacy" id="post_privacy" class="form-control" aria-label="Post privacy setting">
                                <option value="public">🌍 Public</option>
                                <option value="followers">👥 Followers</option>
                                <option value="private">🔒 Private</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group form-title">
                <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Add a catchy title... (Optional)" maxlength="100">
            </div>

            <div class="form-group">
                <textarea id="post_content" name="post_content" rows="4" class="form-control" placeholder="What's on your mind? Share your thoughts, ideas, or experiences..." required maxlength="5000"></textarea>
            </div>

            <div class="form-interactive">
                <div class="form-left-ai">
                    <ul>
                        <li>
                            <button type="button" title="Get AI suggestions" id="post-ai-generate-button" aria-label="Generate content with AI">
                                <i class="fa-solid fa-robot"></i>
                            </button>
                        </li>
                    </ul>
                    <span class="form-ai-status" id="ai-status"></span>
                </div>
                <div class="form-right-attachments">
                    <ul>
                        <li>
                            <label for="post-attach-image" title="Add images" class="cursor-pointer" aria-label="Attach images">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="post-attach-image" name="post-images[]" accept="image/*" class="hidden" multiple aria-label="Select images to upload">
                            </label>
                        </li>
                        <li>
                            <label for="post-attach-file" title="Add files" class="cursor-pointer" aria-label="Attach files">
                                <i class="fa-solid fa-paperclip"></i>
                                <input type="file" id="post-attach-file" name="post-attachments[]" class="hidden" multiple aria-label="Select files to upload">
                            </label>
                        </li>
                        <li>
                            <button type="button" title="Add poll" id="post-add-poll" aria-label="Create a poll">
                                <i class="fa-solid fa-chart-bar"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" title="Add location" id="post-add-location" aria-label="Add location">
                                <i class="fa-solid fa-map-marker-alt"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="form-preview-attachments empty" id="post-preview-attachments">
                <div class="preview-grid" id="preview-grid">
                    <!-- Preview of attached images/files will be shown here -->
                </div>
            </div>

            <div class="form-actions">
                <div class="character-count">
                    <span id="character-counter">0/5000</span>
                </div>
                <div class="form-submit-area">
                    <button type="button" class="save-draft" id="save-draft" title="Save as draft">
                        Save Draft
                    </button>
                    <button type="submit" class="post-submit" id="post-submit-btn">
                        <span class="submit-text">Post</span>
                    </button>
                    <button type="button" class="form-collapse" id="form-collapse-btn" title="Collapse form">
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($modal)
<script>
// Modal-specific functionality will be handled in the main JS
window.postCreationModal = true;
</script>
@endif