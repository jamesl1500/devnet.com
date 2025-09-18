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