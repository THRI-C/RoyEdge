<?php    
    include 'header.php'  
?>

<?php 
 // Handle logout
 if (isset($_POST['logout'])) {
    $auth->logout();
    header('Location: ../login.php?message=You have been logged out');
    exit;
}   
?>
<aside class="sidebar" id="sidebar">
    <div class="logo d-flex justify-content-between p-3 w-100">
        <a href="index.html" class="w-50 "><img src="assets/images/logo-white.png" alt="RoyEdge Logo"></a>
        <button class="text-white bg-transparent border-0 d-block d-md-none"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <nav>
        <ul>
            <li><a href="userdashboard.php" class="active">Overview</a></li>
            <li><a href="add-package.php">Add Package</a></li>
            <li><a href="bulk-order.php">Bulk Order</a></li>
            <li><a href="manage-orders.php">Manage Orders</a></li>
            <li><a href="consolidate.php">Consolidate</a></li>
            <li><a href="consolidated-orders.php">Consolidated Orders</a></li>
            <li><a href="track-shipment.php">Track Shipment</a></li>
            <li><a href="shipping-mark.php">Shipping Mark</a></li>
            <li><a href="shipping-manager.php">Shipping Manager</a></li>
            <li><a href="warehouse.php">Warehouse</a></li>
            <li><a href="referrals.php">Referrals</a></li>
            <li><a href="voice-matters.php">Your Voice Matters</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>
    </nav>
    <div class="signout">
        <form method="POST">
            <button type="submit" id="signout-btn" name="logout" class=" border-0">
                <i class="ri-logout-box-line"></i> Logout
            </button>
        </form>
    </div>
</aside>

<div class="overlay" id="sidebar-overlay"></div>