<?php
// userdashboard.php - Updated with authentication
require_once 'functions/auth.php';
require_once 'functions/config.php';
require_once 'functions/package-manager.php';
require_once 'functions/settings.php';

$auth = new Auth($pdo);

// Check if user is logged in
if (!$auth->checkSession()) {
    header('Location: ../login.php?message=Please login to access this page');
    exit;
}

// Get current user data
$user = getCurrentUser($pdo);

// Initialize PackageManager
$packageManager = new PackageManager($pdo, MANAGER_NO);

// Get user stats
$totalOrders = count($packageManager->getPackages($user['id']));
$shipmentsInTransit = count($packageManager->getPackagesByStatus($user['id'], 'in_transit'));
$shipmentsDelivered = count($packageManager->getPackagesByStatus($user['id'], 'delivered'));

// Get recent packages (last 5)
$recentPackages = $packageManager->getRecentPackages($user['id'], 5);
?>

<?php include 'include/sidebar.php' ?>

<main class="main-content" id="main-content">
    <?php include 'include/navbar.php' ?>

    <div id="overview-section" class="content">
        <div class="section-flex">
            <div class="w-100">
                <!-- Welcome Message -->
                <div class="welcome-section mb-4">
                    <h2>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="membership-label">Membership Level</div>
                <h3 class="membership-level">VIP</h3>
                <div class="stat-cards">
                    <div class="stat-card">
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-value"><?php echo $totalOrders; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Shipments in Transit</div>
                        <div class="stat-value"><?php echo $shipmentsInTransit; ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Shipments Delivered</div>
                        <div class="stat-value"><?php echo $shipmentsDelivered; ?></div>
                    </div>
                </div>

                <div class="shipping-groups">
                    <span class="fs-3">Join our Shipping Groups: </span>
                    <a href="#" class="shipping-icon whatsapp"><i class="ri-whatsapp-fill"></i></a>
                    <a href="#" class="shipping-icon telegram"><i class="ri-telegram-fill"></i></a>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <h5 class="quick-links-title">Quick Links</h5>
            <div class="quick-links">
                <a href="add-package.php" class="justify-content-center quick-link"><i class="ri-add-box-line"></i> Add Package</a>
                <a href="track-shipment.php" class="justify-content-center quick-link"><i class="ri-truck-line"></i> Track Shipment</a>
                <a href="consolidate.php" class="justify-content-center quick-link"><i class="ri-stack-line"></i> Consolidate</a>
                <a href="warehouse.php" class="justify-content-center quick-link"><i class="ri-home-4-line"></i> Warehouse</a>
                <a href="tel:+2349030719089" class="justify-content-center quick-link chat"><i class="ri-chat-1-line"></i> Chat with SM Lagos</a>
                <a href="editprofile.php" class="justify-content-center quick-link"><i class="ri-user-settings-line"></i> Edit Profile</a>
            </div>
        </div>
    </div>
</main>

<script src="../asset/js/jquery-3.7.1.min.js"></script>
<script src="../asset/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>

</html>