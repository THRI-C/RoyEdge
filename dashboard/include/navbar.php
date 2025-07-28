<?php 
    // Handle logout
    if (isset($_POST['logout'])) {
        $auth->logout();
        header('Location: ../login.php?message=You have been logged out');
        exit;
    }
?>
<div id="overview-navbar" class="dashboard-header">
     <div class="dashboard-title ">
         <button class="border-0 bg-transparent d-inline-block d-md-none" id="sidebar-toggle">
             <i class="fa-solid fa-bars"></i>
         </button>
         <span>Dashboard</span>
     </div>
     <div class="user-profile">
         <span class="welcome-text d-none d-md-block">Welcome, <span class="user-name"><?php echo htmlspecialchars($user['username']); ?></span>!</span>
         <div class="avatar-dropdown">
             <div class="avatar avatar-img" id="avatar-trigger">
                 <img src="uploads/profiles/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="User Avatar" class="w-full">
             </div>
             <div class="dropdown-menu" id="avatar-dropdown">
                 <div class="dropdown-header">
                     <div class="avatar-img">
                         <img src="uploads/profiles/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="User Avatar" class="dropdown-avatar w-full">
                     </div>
                     <div class="dropdown-user-info">
                         <span class="dropdown-name"><?php echo htmlspecialchars($user['username']); ?></span>
                         <span class="dropdown-email">j<?php echo htmlspecialchars($user['email']); ?></span>
                     </div>
                 </div>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item" data-action="edit-profile">
                     <i class="ri-user-edit-line"></i>
                     <span>Edit Profile</span>
                 </a>
                 <a href="#" class="dropdown-item" data-action="settings">
                     <i class="ri-settings-3-line"></i>
                     <span>Settings</span>
                 </a>
                 <div class="dropdown-divider"></div>
                 <form method="POST" class="dropdown-item mb-2">
                     <button type="submit" name="logout" class="text-danger border-0 bg-transparent btn-danger">
                         <i class="ri-logout-box-line"></i> Logout
                     </button>
                 </form>
             </div>
         </div>
     </div>
 </div>