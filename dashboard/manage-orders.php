<?php
require_once 'functions/auth.php';
require_once 'functions/package-manager.php';
require_once 'functions/config.php';

$auth = new Auth($pdo);
if (!$auth->checkSession()) {
    header('Location: ../login.php?message=Please login to access this page');
    exit;
}

$user = getCurrentUser($pdo);
if (!$user) {
    header('Location: ../login.php?message=User verification failed');
    exit;
}

$packageManager = new PackageManager($pdo);
$packages = $packageManager->getPackages($user['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $auth->logout();
    header('Location: ../login.php?message=You have been logged out');
    exit;
}
?>

<?php include 'include/sidebar.php' ?>
<div class="main-content">
    <?php include 'include/navbar.php' ?>
    <h4 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 18px;">Your Orders</h4>
    <table class='table' style='background: #fff; border-radius: 8px; overflow: hidden;'>
        <thead>
            <tr>
                <th>Tracking Number</th>
                <th>Product Name</th>
                <th>Shipment Mode</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($packages as $package): ?>
            <tr>
                <td><?= htmlspecialchars($package['tracking_number']) ?></td>
                <td><?= htmlspecialchars($package['product_name']) ?></td>
                <td><?= ucfirst($package['shipment_mode']) ?></td>
                <td>
                    <span class="badge bg-<?= $package['status'] === 'shipped' ? 'success' : 'warning' ?>">
                        <?= ucfirst($package['status'] ?? 'pending') ?>
                    </span>
                </td>
                <td>
                    <form method="POST" action="delete-package.php" style="display:inline">
                        <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                        <button type="submit" class='btn btn-danger'>Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>