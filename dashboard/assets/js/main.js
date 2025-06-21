// Avatar dropdown logic
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

// Handle dropdown menu item clicks
avatarDropdown.addEventListener('click', (e) => {
    e.stopPropagation(); // Prevent dropdown from closing when clicking inside

    const action = e.target.closest('.dropdown-item')?.getAttribute('data-action');
    if (action) {
        console.log('Action clicked:', action);
        // Handle different actions
        switch (action) {
            case 'edit-profile':
                // Handle edit profile
                break;
            case 'settings':
                // Handle settings
                break;
            case 'logout':
                // Handle logout
                break;
        }
    }
});


// Sidebar toggle for mobile
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

// Avatar dropdown logic (works for both navbar and dashboard header)
function setupAvatarDropdown(triggerId, dropdownId) {
    const trigger = document.getElementById(triggerId);
    const dropdown = document.getElementById(dropdownId);
    document.addEventListener('click', function (e) {
        if (trigger && trigger.contains(e.target)) {
            dropdown.classList.toggle('show');
        } else if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
    if (dropdown) {
        dropdown.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.getAttribute('data-action');
                if (action === 'logout') {
                    window.location.href = 'index.php';
                } else if (action === 'settings') {
                    document.querySelector('[data-section="settings"]').click();
                } else if (action === 'edit-profile') {
                    alert('Edit Profile clicked! (Implement profile editing modal or page)');
                }
            });
        });
    }
}
setupAvatarDropdown('avatar-trigger', 'avatar-dropdown');
setupAvatarDropdown('avatar-trigger-navbar', 'avatar-dropdown-navbar');
