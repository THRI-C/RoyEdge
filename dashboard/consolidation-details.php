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

if (!isset($_GET['id'])) {
    header('Location: consolidations.php');
    exit;
}

$consolidationId = $_GET['id'];
$user = getCurrentUser($pdo);
$packageManager = new PackageManager($pdo, MANAGER_NO);

// Get consolidated order details
$stmt = $pdo->prepare("
    SELECT co.* 
    FROM consolidated_orders co
    WHERE co.id = ? AND co.user_id = ?
");
$stmt->execute([$consolidationId, $user['id']]);
$consolidation = $stmt->fetch();

if (!$consolidation) {
    header('Location: consolidations.php');
    exit;
}

// Get packages for this consolidated order
$stmt = $pdo->prepare("
    SELECT p.* 
    FROM packages p
    WHERE p.consolidated_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$consolidationId]);
$packages = $stmt->fetchAll();
?>

<?php include 'include/sidebar.php' ?>
<div class="main-content">
    <?php include 'include/navbar.php' ?>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mt-4">Consolidated Order Details</h4>
            <a href="consolidations.php" class="btn btn-secondary">Back to List</a>
        </div>
        
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <i class="fas fa-boxes me-1"></i>
                    Master Tracking: <?= htmlspecialchars($consolidation['master_tracking_number']) ?>
                </div>
                <div>
                    <span class="badge bg-<?= $consolidation['status'] === 'shipped' ? 'success' : 'warning' ?>">
                        <?= ucfirst($consolidation['status'] ?? 'pending') ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Consolidation Name:</strong> <?= htmlspecialchars($consolidation['name']) ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Manager No.:</strong> <?= htmlspecialchars($consolidation['manager_no']) ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Date:</strong> <?= date('M d, Y H:i', strtotime($consolidation['created_at'])) ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Address:</strong> <?= htmlspecialchars($consolidation['address']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Phone:</strong> <?= htmlspecialchars($consolidation['phone']) ?>
                    </div>
                </div>
                
                <h5 class="mb-3">Packages in this order (<?= count($packages) ?>):</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Product Name</th>
                            <th>Tracking No.</th>
                            <th>Quantity</th>
                            <th>Warehouse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($packages as $index => $package): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($package['product_name']) ?></td>
                                <td><?= htmlspecialchars($package['tracking_number']) ?></td>
                                <td><?= htmlspecialchars($package['quantity']) ?></td>
                                <td><?= htmlspecialchars($package['warehouse']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>