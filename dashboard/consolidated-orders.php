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
$packageManager = new PackageManager($pdo, MANAGER_NO);

// Get all consolidated orders (not individual packages)
$stmt = $pdo->prepare("
    SELECT co.id, co.master_tracking_number, co.name, co.status, co.created_at
    FROM consolidated_orders co
    WHERE co.user_id = ?
    ORDER BY co.created_at DESC
");
$stmt->execute([$user['id']]);
$consolidations = $stmt->fetchAll();
?>

<?php include 'include/sidebar.php' ?>
<div class="main-content">
    <?php include 'include/navbar.php' ?>
    <div class="container-fluid px-4">
        <h4 class="mt-4">Your Consolidated Orders</h4>
        
        <?php if (empty($consolidations)): ?>
            <div class="alert alert-info">No consolidated orders yet!</div>
        <?php else: ?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-boxes me-1"></i>
                    Consolidated Orders List
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Tracking No.</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consolidations as $index => $consolidation): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($consolidation['master_tracking_number']) ?></td>
                                    <td><?= htmlspecialchars($consolidation['name']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $consolidation['status'] === 'shipped' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($consolidation['status'] ?? 'pending') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="consolidation-details.php?id=<?= $consolidation['id'] ?>" class="btn btn-sm btn-primary">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>