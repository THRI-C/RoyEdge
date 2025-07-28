/** ==================== Avatar Dropdown Utilities ====================== */
const avatarTrigger = document.getElementById('avatar-trigger');
const avatarDropdown = document.getElementById('avatar-dropdown');

// Toggle dropdown
avatarTrigger.addEventListener('click', (e) => {
    e.stopPropagation(); // Prevent event bubbling
    avatarDropdown.classList.toggle('show');
    console.log('Dropdown toggled:', avatarDropdown.classList.contains('show'));
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!avatarTrigger.contains(e.target) && !avatarDropdown.contains(e.target)) {
        avatarDropdown.classList.remove('show');
    }
});

/** ==================== Mobile toggle Utilities ====================== */
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebarOverlay = document.getElementById('sidebar-overlay');

function openSidebar() {
    sidebar.classList.add('show');
    sidebarOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    sidebar.classList.remove('show');
    sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

// Open sidebar on hamburger click
sidebarToggle.addEventListener('click', openSidebar);

// Close sidebar on overlay click
sidebarOverlay.addEventListener('click', closeSidebar);

// Close sidebar when a sidebar link is clicked (on mobile)
document.querySelectorAll('.sidebar nav ul li a').forEach(link => {
    link.addEventListener('click', function () {
        if (window.innerWidth <= 900) closeSidebar();
    });
});

// Optional: Close sidebar on window resize if desktop
window.addEventListener('resize', function () {
    if (window.innerWidth > 900) closeSidebar();
});

/** ====================== Edit profile utilities. =======================*/
function updateFullName() {
    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const fullName = [firstName, lastName].filter(name => name).join(' ');
    document.getElementById('name').value = fullName || document.getElementById('name').getAttribute('data-username');
}

// Add event listeners
document.getElementById('first_name').addEventListener('input', updateFullName);
document.getElementById('last_name').addEventListener('input', updateFullName);

// Image preview functionality
document.getElementById('profile_image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('imagePreview');
            const defaultAvatar = document.getElementById('defaultAvatar');

            if (preview) {
                preview.src = e.target.result;
            } else {
                // Create new image element if doesn't exist
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'profile-image-preview';
                img.id = 'imagePreview';
                defaultAvatar.parentNode.replaceChild(img, defaultAvatar);
            }
        }
        reader.readAsDataURL(file);
    }
});

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function () {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;

    if (newPassword !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

// Prevent reloading of pages
document.addEventListener('submit', (e) => {
    e.preventDefault();
})

// Modal open/close for admin suspend/activate
window.openSuspendModal = function (userId, action) {
    var url = 'suspend-user.php?id=' + userId + (action === 'activate' ? '&activate=1' : '');
    var msg = action === 'activate' ? 'Activate this user?' : 'Suspend this user?';
    document.getElementById('modalBody').textContent = msg;
    document.getElementById('modalConfirmBtn').setAttribute('href', url);
    document.getElementById('confirmModal').style.display = 'flex';
}
window.closeModal = function () {
    document.getElementById('confirmModal').style.display = 'none';
}