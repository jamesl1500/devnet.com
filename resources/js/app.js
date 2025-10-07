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
 * Dashboard Post Creation JS
 * ----
 * Handles all frontend logic for the post creation component on the dashboard.
 * - Image/File Previews
 * - Form Submission
 * - Attachment Management
 * - AI Integration for requesting content ideas
 */
document.addEventListener('DOMContentLoaded', function() {
    const postForm = document.querySelector('.post-creation-form');

    const postContent = document.getElementById('post_content');
    const postTitle = document.getElementById('post_title');
    const postPrivacy = document.getElementById('post_privacy');

    const attachmentInput = document.getElementById('post-attach-file');
    const imageInput = document.getElementById('post-attach-image');

    const previewContainer = document.getElementById('post-preview-attachments');
    const aiButton = document.getElementById('post-ai-generate-button');

    let attachments = [];
    let images = [];

    // Function to update the preview area
    function updatePreview() {
        previewContainer.innerHTML = '';

        if (images.length === 0 && attachments.length === 0) {
            previewContainer.classList.add('empty');
            return;
        }

        previewContainer.classList.remove('empty');

        images.forEach((img, index) => {
            const imgDiv = document.createElement('div');
            imgDiv.classList.add('preview-item');

            const imgElement = document.createElement('img');
            imgElement.src = img.url;
            imgElement.alt = `Image ${index + 1}`;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('remove-btn');
            removeBtn.innerHTML = '&times;';

            removeBtn.addEventListener('click', function() {
                images.splice(index, 1);
                updatePreview();
            });

            imgDiv.appendChild(imgElement);
            imgDiv.appendChild(removeBtn);
            previewContainer.appendChild(imgDiv);
        });

        attachments.forEach((file, index) => {
            const fileDiv = document.createElement('div');
            fileDiv.classList.add('preview-item', 'file-item');

            const fileName = document.createElement('span');
            fileName.textContent = file.name;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('remove-btn');
            removeBtn.innerHTML = '&times;';

            removeBtn.addEventListener('click', function() {
                attachments.splice(index, 1);
                updatePreview();
            });

            fileDiv.appendChild(fileName);
            fileDiv.appendChild(removeBtn);
            previewContainer.appendChild(fileDiv);
        });
    }

    // Handle image selection
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const files = Array.from(this.files);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    images.push({ file: file, url: e.target.result });
                    updatePreview();
                }
                reader.readAsDataURL(file);
            });

            // Reset input
            this.value = '';
        });
    }

    // Handle attachment selection
    if (attachmentInput) {
        attachmentInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            attachments = attachments.concat(files);
            updatePreview();

            // Reset input
            this.value = '';
        });
    }

    // Handle AI content generation
    if (aiButton) {
        aiButton.addEventListener('click', function() {
            const prompt = postContent.value.trim();
            if (!prompt) {
                alert('Please enter some content to get AI suggestions.');
                return;
            }

            aiButton.disabled = true;
            aiButton.innerHTML = '<i class="fa-solid fa-robot fa-spin"></i>';

           
        });
    }

    // Handle form submission
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('title', postTitle.value);
            formData.append('content', postContent.value);
            formData.append('privacy', postPrivacy.value);

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
                    // Reset form
                    postTitle.value = '';
                    postContent.value = '';
                    postPrivacy.value = 'public';
                    images = [];
                    attachments = [];
                    updatePreview();
                    alert('Post created successfully!');
                } else {
                    alert('Error creating post: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the post.');
            });
        });
    }

    // Initial preview update
    updatePreview();
});