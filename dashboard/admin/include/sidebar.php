<aside class="sidebar" id="sidebar" style="background: #22223b; color: #fff; min-height: 100vh;">
    <div class="logo d-flex justify-content-between p-3 w-100" style="border-bottom: 2px solid #fca311;">
        <a href="overview.php" class="w-50 "><img src="../assets/images/logo-white.png" alt="RoyEdge Logo" style="max-width: 120px;"></a>
        <button class="text-white bg-transparent border-0 d-block d-md-none"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <nav>
        <ul style="margin-top: 20px;">
            <li><a href="overview.php" class="active"><i class="ri-dashboard-line" style="color: #fca311;"></i> Overview</a></li>
            <li><a href="users.php"><i class="ri-user-settings-line" style="color: #fca311;"></i> Users</a></li>
            <li><a href="orders.php"><i class="ri-archive-2-line" style="color: #fca311;"></i> Orders</a></li>
            <!-- <li><a href="warehouses.php"><i class="ri-building-2-line" style="color: #fca311;"></i> Warehouses</a></li>
            <li><a href="shipping-managers.php"><i class="ri-user-star-line" style="color: #fca311;"></i> Shipping Managers</a></li> -->
            <li><a href="referrals.php"><i class="ri-gift-line" style="color: #fca311;"></i> Referrals</a></li>
            <li><a href="notifications.php"><i class="ri-notification-3-line" style="color: #fca311;"></i> Notifications</a></li>
            <li><a href="settings.php"><i class="ri-settings-3-line" style="color: #fca311;"></i> Settings</a></li>
        </ul>
    </nav>
    <div class="signout mt-auto p-3">
        <form method="POST" action="../../logout.php">
            <button type="submit" id="signout-btn" name="logout" class="border-0 bg-transparent text-white" style="font-weight:600;">
                <i class="ri-logout-box-line" style="color: #fca311;"></i> Logout
            </button>
        </form>
    </div>
</aside>
<div class="overlay" id="sidebar-overlay"></div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">