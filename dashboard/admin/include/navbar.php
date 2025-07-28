<div id="overview-navbar" class="dashboard-header" style="background: #fff; border-bottom: 2px solid #fca311; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
    <div class="dashboard-title ">
        <button class="border-0 bg-transparent d-inline-block d-md-none" id="sidebar-toggle">
            <i class="fa-solid fa-bars" style="color: #fca311;"></i>
        </button>
        <span style="color: #22223b; font-weight: 700;">Admin Dashboard</span>
    </div>
    <div class="user-profile">
        <span class="welcome-text d-none d-md-block">Welcome, <span class="user-name" style="color: #fca311; font-weight: 600;"> <?php echo htmlspecialchars($user['username'] ?? 'Admin'); ?></span>!</span>
        <div class="avatar-dropdown">
            <div class="avatar avatar-img" id="avatar-trigger">
                <img src="../uploads/profiles/<?php echo htmlspecialchars($user['profile_image'] ?? ''); ?>" alt="User Avatar" class="w-full" style="border: 2px solid #fca311;">
            </div>
            <div class="dropdown-menu" id="avatar-dropdown">
                <div class="dropdown-header">
                    <div class="avatar-img">
                        <img src="../uploads/profiles/<?php echo htmlspecialchars($user['profile_image'] ?? ''); ?>" alt="User Avatar" class="dropdown-avatar w-full">
                    </div>
                    <div class="dropdown-user-info">
                        <span class="dropdown-name"><?php echo htmlspecialchars($user['username'] ?? 'Admin'); ?></span>
                        <span class="dropdown-email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></span>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="settings.php" class="dropdown-item" data-action="settings">
                    <i class="ri-settings-3-line"></i>
                    <span>Settings</span>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" class="dropdown-item mb-2" action="../logout.php">
                    <button type="submit" name="logout" class="text-danger border-0 bg-transparent btn-danger">
                        <i class="ri-logout-box-line"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>