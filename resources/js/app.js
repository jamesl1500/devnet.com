/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

/**
 * Header JS
 * ----
 */

// Header Dropdown Logic
const dropdownToggles = document.querySelectorAll('.nav-link-dropdown-toggle');

dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const dropdownId = this.getAttribute('data-d');
        const dropdown = document.getElementById(dropdownId);

        if (dropdown) {
            const isVisible = dropdown.style.display === 'block';

            // Hide all dropdowns
            document.querySelectorAll('.header-dropdown').forEach(dd => dd.style.display = 'none');

            // Toggle current dropdown
            dropdown.style.display = isVisible ? 'none' : 'block';
        }
    });
});

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.header-nav-item')) {
        document.querySelectorAll('.header-dropdown').forEach(dd => dd.style.display = 'none');
    }
});

/**
 * Onboarding Page Frontend JS
 * ----
 */

// Avatar Photo Preview
const avatarInput = document.getElementById('avatar_photo');
const avatarPreview = document.getElementById('avatar_preview');

if (avatarInput && avatarPreview) {
    avatarInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        } else {
            avatarPreview.src = "https://placehold.co/400"; // Reset to placeholder if no file is selected
        }
    });
}

// Cover Photo Preview
const coverInput = document.getElementById('cover_photo');
const coverPreview = document.getElementById('cover_photo_preview');

if (coverInput && coverPreview) {
    coverInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                coverPreview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        } else {
            coverPreview.src = "https://placehold.co/600x400"; // Reset to placeholder if no file is selected
        }
    });
}

/** 
 * Enhanced Post Creation JS
 * ----
 * Handles all frontend logic for the post creation component on the dashboard.
 * - Image/File Previews with enhanced UI
 * - Form Submission with loading states
 * - Attachment Management with drag & drop
 * - AI Integration for requesting content ideas
 * - Modal functionality for global post creation
 * - Character counting and validation
 * - Auto-save draft functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    const postForm = document.querySelector('.post-creation-form');
    const postContent = document.getElementById('post_content');
    const postTitle = document.getElementById('post_title');
    const postPrivacy = document.getElementById('post_privacy');
    const attachmentInput = document.getElementById('post-attach-file');
    const imageInput = document.getElementById('post-attach-image');
    const previewContainer = document.getElementById('post-preview-attachments');
    const previewGrid = document.getElementById('preview-grid');
    const aiButton = document.getElementById('post-ai-generate-button');
    const aiStatus = document.getElementById('ai-status');
    const characterCounter = document.getElementById('character-counter');
    const submitButton = document.getElementById('post-submit-btn');
    const saveDraftButton = document.getElementById('save-draft');

    // Post creation collapse/expand functionality
    const postCreationContainer = document.getElementById('post-creation-container');
    const collapsedState = document.getElementById('post-creation-collapsed');
    const expandedState = document.getElementById('post-creation-inner');
    const collapseButton = document.getElementById('form-collapse-btn');

    let attachments = [];
    let images = [];
    let isSubmitting = false;
    let draftTimeout;
    let isExpanded = false;
    let hoverTimeout;

    // Initialize post creation state
    function initializePostCreation() {
        if (postCreationContainer && collapsedState && expandedState) {
            // Set initial state
            collapsedState.style.display = 'block';
            expandedState.style.display = 'none';
            postCreationContainer.classList.remove('expanded');
            isExpanded = false;
        }
    }

    // Expand form function
    function expandForm() {
        if (!isExpanded && postCreationContainer && collapsedState && expandedState) {
            isExpanded = true;
            postCreationContainer.classList.add('expanded');
            collapsedState.style.display = 'none';
            expandedState.style.display = 'block';
            
            // Focus on content area for better UX
            setTimeout(() => {
                if (postContent) {
                    postContent.focus();
                }
            }, 300);
        }
    }

    // Collapse form function
    function collapseForm() {
        if (isExpanded && postCreationContainer && collapsedState && expandedState) {
            // Don't collapse if there's content
            if ((postContent && postContent.value.trim()) || 
                (postTitle && postTitle.value.trim()) || 
                images.length > 0 || 
                attachments.length > 0) {
                return;
            }

            isExpanded = false;
            postCreationContainer.classList.remove('expanded');
            collapsedState.style.display = 'block';
            expandedState.style.display = 'none';
        }
    }

    // Hover functionality for expansion
    if (collapsedState) {
        collapsedState.addEventListener('mouseenter', function() {
            hoverTimeout = setTimeout(() => {
                expandForm();
            }, 800); // 800ms hover delay before expanding
        });

        collapsedState.addEventListener('mouseleave', function() {
            if (hoverTimeout) {
                clearTimeout(hoverTimeout);
            }
        });

        // Click to expand immediately
        collapsedState.addEventListener('click', function() {
            if (hoverTimeout) {
                clearTimeout(hoverTimeout);
            }
            expandForm();
        });
    }

    // Collapse button functionality
    if (collapseButton) {
        collapseButton.addEventListener('click', function(e) {
            e.preventDefault();
            collapseForm();
        });
    }

    // Auto-collapse when clicking outside (but not if form has content)
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.post-creation-container') && isExpanded) {
            collapseForm();
        }
    });

    // Prevent collapse when typing or interacting with form
    if (expandedState) {
        expandedState.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Initialize the component
    initializePostCreation();

    // Character counting
    function updateCharacterCount() {
        if (postContent && characterCounter) {
            const length = postContent.value.length;
            const maxLength = 5000;
            characterCounter.textContent = `${length}/${maxLength}`;
            
            characterCounter.className = 'character-count';
            if (length > maxLength * 0.9) {
                characterCounter.classList.add('warning');
            }
            if (length > maxLength) {
                characterCounter.classList.add('error');
            }

            // Disable submit if over limit
            if (submitButton) {
                submitButton.disabled = length > maxLength || length === 0;
            }
        }
    }

    // Auto-save draft functionality
    function saveDraft() {
        if (!postContent?.value.trim() && !postTitle?.value.trim()) return;

        const draftData = {
            title: postTitle?.value || '',
            content: postContent?.value || '',
            privacy: postPrivacy?.value || 'public',
            timestamp: new Date().toISOString()
        };

        localStorage.setItem('post_draft', JSON.stringify(draftData));
        
        if (saveDraftButton) {
            saveDraftButton.textContent = 'Saved';
            setTimeout(() => {
                saveDraftButton.textContent = 'Save Draft';
            }, 2000);
        }
    }

    // Load draft functionality
    function loadDraft() {
        const draftData = localStorage.getItem('post_draft');
        if (draftData) {
            try {
                const draft = JSON.parse(draftData);
                if (postTitle) postTitle.value = draft.title || '';
                if (postContent) postContent.value = draft.content || '';
                if (postPrivacy) postPrivacy.value = draft.privacy || 'public';
                updateCharacterCount();
            } catch (e) {
                console.error('Error loading draft:', e);
            }
        }
    }

    // Enhanced preview update function
    function updatePreview() {
        if (!previewContainer || !previewGrid) return;

        previewGrid.innerHTML = '';

        if (images.length === 0 && attachments.length === 0) {
            previewContainer.classList.add('empty');
            return;
        }

        previewContainer.classList.remove('empty');

        // Add images
        images.forEach((img, index) => {
            const imgDiv = document.createElement('div');
            imgDiv.classList.add('preview-item');
            imgDiv.innerHTML = `
                <img src="${img.url}" alt="Image ${index + 1}" loading="lazy">
                <button type="button" class="remove-btn" onclick="removeImage(${index})" aria-label="Remove image">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            previewGrid.appendChild(imgDiv);
        });

        // Add files
        attachments.forEach((file, index) => {
            const fileDiv = document.createElement('div');
            fileDiv.classList.add('preview-item', 'file-item');
            fileDiv.innerHTML = `
                <div class="file-icon">
                    <i class="fa-solid fa-file"></i>
                </div>
                <div class="file-name">${file.name}</div>
                <button type="button" class="remove-btn" onclick="removeAttachment(${index})" aria-label="Remove file">
                    <i class="fa-solid fa-times"></i>
                </button>
            `;
            previewGrid.appendChild(fileDiv);
        });
    }

    // Global functions for removing items
    window.removeImage = function(index) {
        images.splice(index, 1);
        updatePreview();
    };

    window.removeAttachment = function(index) {
        attachments.splice(index, 1);
        updatePreview();
    };

    // Enhanced image selection with validation
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            files.forEach(file => {
                if (!allowedTypes.includes(file.type)) {
                    alert(`File ${file.name} is not a supported image format.`);
                    return;
                }

                if (file.size > maxSize) {
                    alert(`File ${file.name} is too large. Maximum size is 10MB.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    images.push({ file: file, url: e.target.result });
                    updatePreview();
                };
                reader.readAsDataURL(file);
            });

            this.value = '';
        });
    }

    // Enhanced attachment selection with validation
    if (attachmentInput) {
        attachmentInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            const maxSize = 10 * 1024 * 1024; // 10MB

            files.forEach(file => {
                if (file.size > maxSize) {
                    alert(`File ${file.name} is too large. Maximum size is 10MB.`);
                    return;
                }

                attachments.push(file);
            });

            updatePreview();
            this.value = '';
        });
    }

    // Enhanced AI content generation
    if (aiButton && aiStatus) {
        aiButton.addEventListener('click', function() {
            const prompt = postContent?.value.trim();
            if (!prompt) {
                alert('Please enter some content to get AI suggestions.');
                return;
            }

            aiButton.disabled = true;
            aiButton.innerHTML = '<i class="fa-solid fa-robot fa-spin"></i>';
            aiStatus.textContent = 'Generating suggestions...';
            aiStatus.classList.add('loading');

            // Simulate AI call (replace with actual AI integration)
            setTimeout(() => {
                aiButton.disabled = false;
                aiButton.innerHTML = '<i class="fa-solid fa-robot"></i>';
                aiStatus.textContent = 'AI suggestions ready!';
                aiStatus.classList.remove('loading');
                
                setTimeout(() => {
                    aiStatus.textContent = '';
                }, 3000);
            }, 2000);
        });
    }

    // Enhanced form submission with loading states
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (isSubmitting) return;

            const content = postContent?.value.trim();
            const title = postTitle?.value.trim();

            if (!content) {
                alert('Please enter some content for your post.');
                postContent?.focus();
                return;
            }

            if (content.length > 5000) {
                alert('Post content is too long. Maximum 5000 characters allowed.');
                return;
            }

            isSubmitting = true;
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="loading-spinner"></span>Publishing...';

            const formData = new FormData();
            formData.append('title', title);
            formData.append('content', content);
            formData.append('privacy', postPrivacy?.value || 'public');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            images.forEach((img, index) => {
                formData.append(`images[${index}]`, img.file);
            });

            attachments.forEach((file, index) => {
                formData.append(`attachments[${index}]`, file);
            });

            fetch(postForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData,
                credentials: 'include',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear form
                    if (postTitle) postTitle.value = '';
                    if (postContent) postContent.value = '';
                    if (postPrivacy) postPrivacy.value = 'public';
                    images = [];
                    attachments = [];
                    updatePreview();
                    updateCharacterCount();
                    
                    // Clear draft
                    localStorage.removeItem('post_draft');
                    
                    // Collapse form back to original state
                    setTimeout(() => {
                        collapseForm();
                    }, 1000);
                    
                    // Show success message
                    showNotification('Post created successfully! 🎉', 'success');
                    
                    // Close modal if in modal mode
                    if (window.postCreationModal) {
                        closePostModal();
                    }
                    
                    // Optionally reload the page or update the feed
                    setTimeout(() => {
                        if (!window.postCreationModal) {
                            window.location.reload();
                        }
                    }, 1500);
                } else {
                    showNotification('Error creating post: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while creating the post.', 'error');
            })
            .finally(() => {
                isSubmitting = false;
                submitButton.disabled = false;
                submitButton.innerHTML = '<span class="submit-text">Post</span>';
            });
        });
    }

    // Save draft functionality
    if (saveDraftButton) {
        saveDraftButton.addEventListener('click', saveDraft);
    }

    // Auto-save draft on content change
    if (postContent) {
        postContent.addEventListener('input', function() {
            updateCharacterCount();
            
            // Auto-save draft after 2 seconds of inactivity
            clearTimeout(draftTimeout);
            draftTimeout = setTimeout(saveDraft, 2000);
        });
    }

    if (postTitle) {
        postTitle.addEventListener('input', function() {
            clearTimeout(draftTimeout);
            draftTimeout = setTimeout(saveDraft, 2000);
        });
    }

    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideUp 0.3s ease;
            max-width: 300px;
            word-wrap: break-word;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 5000);
    }

    // Initialize
    updateCharacterCount();
    updatePreview();
    loadDraft();
});

/**
 * Modal Functionality
 * ----
 * Global functions for opening and closing the post creation modal
 */
function openPostModal() {
    const overlay = document.createElement('div');
    overlay.className = 'post-creation-modal-overlay';
    overlay.id = 'post-modal-overlay';
    
    // Create modal content
    overlay.innerHTML = `
        <div class="post-creation-container modal-mode">
            <div class="post-creation-header">
                <div class="header-content">
                    <h3>Create a Post</h3>
                    <button type="button" class="close-modal" onclick="closePostModal()">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-loading">
                <div style="text-align: center; padding: 40px;">
                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; color: #00f0ff;"></i>
                    <p style="margin-top: 16px; color: #666;">Loading post editor...</p>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    
    // Trigger animation
    setTimeout(() => {
        overlay.classList.add('active');
    }, 10);
    
    // Load the actual post creation component via AJAX
    fetch('/api/post-creation-modal', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(html => {
        const container = overlay.querySelector('.post-creation-container');
        container.innerHTML = html;
        
        // Reinitialize the post creation JavaScript for the modal
        window.postCreationModal = true;
        
        // Trigger a custom event to reinitialize
        document.dispatchEvent(new CustomEvent('postModalLoaded'));
    })
    .catch(error => {
        console.error('Error loading modal:', error);
        closePostModal();
        showNotification('Failed to load post editor', 'error');
    });
}

function closePostModal() {
    const overlay = document.getElementById('post-modal-overlay');
    if (overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
            window.postCreationModal = false;
        }, 300);
    }
}

// Close modal on overlay click
document.addEventListener('click', function(e) {
    if (e.target.id === 'post-modal-overlay') {
        closePostModal();
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('post-modal-overlay')) {
        closePostModal();
    }
});

// Make functions globally available
window.openPostModal = openPostModal;
window.closePostModal = closePostModal;