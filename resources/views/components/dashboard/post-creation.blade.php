<div class="post-creation-container">
    <div class="post-creation-inner">
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <div class="user-hold">
                    <div class="user-avatar">
                        <img src="https://placehold.co/400" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="user-name">
                        <h3>{{ auth()->user()->name }}</h3>
                        <div class="privacy-dropdown">
                            <select name="privacy" id="privacy" class="form-control">
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <textarea id="content" name="content" rows="4" class="form-control" required></textarea>
            </div>
            <div class="form-interactive">
                <div class="form-left-attachments">
                    <ul class="flex gap-4 items-center">
                        <li>
                            <button type="button" title="Rewrite with AI" class="p-2 rounded hover:bg-gray-100">
                                <i class="fa-solid fa-robot"></i> Write with AI
                            </button>
                        </li>
                        <li>
                            <label for="attach-image" title="Attach Images" class="p-2 rounded hover:bg-gray-100 cursor-pointer">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="attach-image" name="images[]" accept="image/*" class="hidden" multiple>
                            </label>
                        </li>
                        <li>
                            <label for="attach-file" title="Attach Files" class="p-2 rounded hover:bg-gray-100 cursor-pointer">
                                <i class="fa-solid fa-file"></i>
                                <input type="file" id="attach-file" name="attachments[]" class="hidden" multiple>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="form-right-privacy">

                </div>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>
</div>