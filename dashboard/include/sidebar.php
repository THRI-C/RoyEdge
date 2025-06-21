<?php include 'header.php' ?>
<aside class="sidebar" id="sidebar">
    <div class="logo d-flex justify-content-between p-3 w-100">
        <a href="index.html" class="w-50 "><img src="assets/images/logo-white.png" alt="RoyEdge Logo"></a>
        <button class="text-white bg-transparent border-0 d-block d-md-none"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <nav>
        <ul>
            <li><a href="userdashboard.php" class="active">Overview</a></li>
            <li><a href="dashboard-add-package.php">Add Package</a></li>
            <li><a href="dashboard-bulk-order.php">Bulk Order</a></li>
            <li><a href="dashboard-manage-orders.php">Manage Orders</a></li>
            <li><a href="dashboard-consolidate.php">Consolidate</a></li>
            <li><a href="dashboard-consolidated-orders.php">Consolidated Orders</a></li>
            <li><a href="dashboard-track-shipment.php">Track Shipment</a></li>
            <li><a href="dashboard-shipping-mark.php">Shipping Mark</a></li>
            <li><a href="dashboard-shipping-manager.php">Shipping Manager</a></li>
            <li><a href="dashboard-warehouse.php">Warehouse</a></li>
            <li><a href="dashboard-referrals.php">Referrals</a></li>
            <li><a href="dashboard-voice-matters.php">Your Voice Matters</a></li>
            <li><a href="dashboard-settings.php">Settings</a></li>
        </ul>
    </nav>
    <div class="signout">
        <button id="signout-btn"><i class="ri-logout-box-r-line"></i> Sign Out</button>
    </div>
</aside>

<div class="overlay" id="sidebar-overlay"></div>