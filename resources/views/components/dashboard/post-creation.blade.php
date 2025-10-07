<div class="post-creation-container">
    <div class="post-creation-inner">
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
                        <img src="{{ $avatarPath }}" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="user-name">
                        <div class="user-name-text">
                            <h3>{{ auth()->user()->name }}</h3>
                            <h5>{{ '@' . auth()->user()->username }}</h5>
                        </div>
                        <div class="privacy-dropdown">
                            <select name="post_privacy" id="post_privacy" class="form-control">
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="text" name="post_title" id="post_title" class="form-control" placeholder="Title (Optional)">
            </div>
            <div class="form-group">
                <textarea id="post_content" name="post_content" rows="4" class="form-control" placeholder="What's on your mind?" required></textarea>
            </div>
            <div class="form-interactive">
                <div class="form-left-ai">
                    <ul class="flex gap-4 items-center">
                        <li>
                            <button type="button" title="Rewrite with AI" id="post-ai-generate-button" class="p-2 rounded hover:bg-gray-100">
                                <i class="fa-solid fa-robot"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="form-right-attachments">
                    <ul class="flex gap-4 items-center">
                        <li>
                            <label for="post-attach-image" title="Attach Images" class="p-2 rounded hover:bg-gray-100 cursor-pointer">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="post-attach-image" name="post-images[]" accept="image/*" class="hidden" multiple>
                            </label>
                        </li>
                        <li>
                            <label for="post-attach-file" title="Attach Files" class="p-2 rounded hover:bg-gray-100 cursor-pointer">
                                <i class="fa-solid fa-file"></i>
                                <input type="file" id="post-attach-file" name="post-attachments[]" class="hidden" multiple>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-preview-attachments empty" id="post-preview-attachments">
                <!-- Preview of attached images/files will be shown here -->
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary post-submit">Post</button>
            </div>
        </form>
    </div>
</div>