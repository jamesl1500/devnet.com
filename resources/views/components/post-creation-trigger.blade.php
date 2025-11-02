@props(['text' => 'Create Post', 'icon' => 'fa-plus', 'class' => 'btn-primary'])

<button type="button" 
        class="post-creation-trigger {{ $class }}" 
        onclick="openPostModal()" 
        title="Create a new post"
        aria-label="Open post creation modal">
    <i class="fa-solid {{ $icon }}"></i>
    <span>{{ $text }}</span>
</button>

<style>
.post-creation-trigger {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #00f0ff 0%, #0080ff 100%);
    color: white;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 0.95rem;
}

.post-creation-trigger:hover {
    background: linear-gradient(135deg, #00d4e6 0%, #0066cc 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 240, 255, 0.3);
    color: white;
    text-decoration: none;
}

.post-creation-trigger:active {
    transform: translateY(0);
}

.post-creation-trigger.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.post-creation-trigger.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
}

.post-creation-trigger.btn-outline {
    background: transparent;
    color: #00f0ff;
    border: 2px solid #00f0ff;
}

.post-creation-trigger.btn-outline:hover {
    background: #00f0ff;
    color: white;
}

.post-creation-trigger.btn-floating {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    padding: 0;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.post-creation-trigger.btn-floating span {
    display: none;
}

.post-creation-trigger.btn-floating i {
    font-size: 1.25rem;
}

@media (max-width: 768px) {
    .post-creation-trigger.btn-floating {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
    }
    
    .post-creation-trigger.btn-floating i {
        font-size: 1.1rem;
    }
}
</style>