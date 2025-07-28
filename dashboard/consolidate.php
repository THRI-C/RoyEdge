<?php
require_once 'functions/settings.php';
require_once 'functions/auth.php';
require_once 'functions/config.php';
require_once 'functions/package-manager.php';

$auth = new Auth($pdo);
if (!$auth->checkSession()) {
    header('Location: ../login.php');
    exit;
}

$user = getCurrentUser($pdo);
$manager_no = MANAGER_NO;
$packageManager = new PackageManager($pdo, $manager_no);
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['address']) || empty($_POST['phone'])) {
            throw new Exception("Address and phone number are required");
        }
        
        if (empty($_POST['package_ids'])) {
            throw new Exception("Please select at least one package");
        }

        $package_ids = $_POST['package_ids'];
        $master_tracking = $packageManager->createConsolidatedOrder(
            $user['id'],
            $package_ids,
            $_POST['address'],
            $_POST['phone']
        );
        
        $message = '<div class="alert alert-success">Packages consolidated successfully! Master Tracking: ' . $master_tracking . '</div>';
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

$packages = $packageManager->getPackagesForConsolidation($user['id']);
?>

<?php include 'include/sidebar.php' ?>
<div class="main-content">
    <?php include 'include/navbar.php' ?>
    
    <div class="container-fluid px-4">
        <h4 class="mt-4">Consolidate Packages</h4>
        
        <?php echo $message; ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-boxes me-1"></i>
                Available Packages for Consolidation
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Shipping Address</label>
                        <textarea name="address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product Name</th>
                                <th>Tracking No.</th>
                                <th>Manager's No.</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($packages as $package): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="package_ids[]" value="<?= $package['id'] ?>">
                                </td>
                                <td><?= htmlspecialchars($package['product_name']) ?></td>
                                <td><?= htmlspecialchars($package['tracking_number']) ?></td>
                                <td><?= $manager_no ?></td>
                                <td>
                                    <span class="badge bg-<?= $package['status'] === 'shipped' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($package['status'] ?? 'pending') ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <button type="submit" class="btn btn-secondary">Consolidate Selected Packages</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Rest of your HTML -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
