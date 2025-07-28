<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';
// Fetch stats
$totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalOrders = $pdo->query('SELECT COUNT(*) FROM packages')->fetchColumn();
$totalWarehouses = 6; // Set this dynamically if you have a warehouses table
$totalReferrals = $pdo->query('SELECT COUNT(*) FROM referrals')->fetchColumn();
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <div id="overview-section" class="content">
        <div class="section-flex">
            <div class="w-100">
                <!-- Welcome Message -->
                <div class="welcome-section mb-4">
                    <h2>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?> <span class="badge" style="font-weight:600;">Admin</span></p>
                </div>
                <div class="membership-label">Admin Level</div>
                <h3 class="membership-level">Super Admin</h3>
                <div class="stat-cards">
                    <div class="stat-card">
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value"><?php echo $totalUsers; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-value"><?php echo $totalOrders; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Warehouses</div>
                        <div class="stat-value"><?php echo $totalWarehouses; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Referrals</div>
                        <div class="stat-value"><?php echo $totalReferrals; ?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div style="margin-top: 30px;">
        <h5 class="quick-links-title">Quick Links</h5>
        <div class="quick-links">
            <a href="users.php" class="justify-content-center quick-link"><i class="ri-user-settings-line"></i> Manage Users</a>
            <a href="orders.php" class="justify-content-center quick-link"><i class="ri-archive-2-line"></i> Manage Orders</a>
            <a href="warehouses.php" class="justify-content-center quick-link"><i class="ri-building-2-line"></i> Manage Warehouses</a>
            <a href="shipping-managers.php" class="justify-content-center quick-link"><i class="ri-user-star-line"></i> Shipping Managers</a>
            <a href="referrals.php" class="justify-content-center quick-link"><i class="ri-gift-line"></i> Referrals</a>
            <a href="settings.php" class="justify-content-center quick-link"><i class="ri-settings-3-line"></i> Admin Settings</a>
        </div>
    </div>
    </div>
</main>
<script src="../../asset/js/jquery-3.7.1.min.js"></script>
<script src="../../asset/js/bootstrap.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>

</html>