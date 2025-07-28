<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../../login.php?message=Admin access required');
    exit();
}

// Validate required parameters
if (!isset($_GET['id']) || !isset($_GET['type'])) {
    echo '<div class="alert alert-danger">Invalid request - missing parameters.</div>';
    exit();
}

$id = (int)$_GET['id'];
$type = $_GET['type'];
$order = null;
$isConsolidated = ($type === 'consolidated');

// Initialize messages
$messages = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch (true) {
                case $_POST['action'] === 'update_status':
                    $newStatus = $_POST['status'];
                    if ($isConsolidated) {
                        $stmt = $pdo->prepare('UPDATE consolidated_orders SET status = ? WHERE id = ?');
                        if ($stmt->execute([$newStatus, $id])) {
                            $messages[] = ['type' => 'success', 'text' => 'Status updated successfully.'];
                        } else {
                            $messages[] = ['type' => 'danger', 'text' => 'Failed to update status.'];
                        }
                    } else {
                        $stmt = $pdo->prepare('UPDATE packages SET status = ? WHERE id = ?');
                        if ($stmt->execute([$newStatus, $id])) {
                            $messages[] = ['type' => 'success', 'text' => 'Status updated successfully.'];
                        } else {
                            $messages[] = ['type' => 'danger', 'text' => 'Failed to update status.'];
                        }
                    }
                    break;

                case 'update_warehouse':
                    if (!$isConsolidated) {
                        $newWarehouse = $_POST['warehouse'];
                        $stmt = $pdo->prepare('UPDATE packages SET warehouse = ? WHERE id = ?');
                        if ($stmt->execute([$newWarehouse, $id])) {
                            $messages[] = ['type' => 'success', 'text' => 'Warehouse updated successfully.'];
                        } else {
                            $messages[] = ['type' => 'danger', 'text' => 'Failed to update warehouse.'];
                        }
                    }
                    break;

                case 'delete_order':
                    if ($isConsolidated) {
                        $pdo->beginTransaction();
                        try {
                            // Unlink packages from consolidated order
                            $stmt = $pdo->prepare('UPDATE packages SET consolidated_id = NULL WHERE consolidated_id = ?');
                            $stmt->execute([$id]);

                            // Delete consolidated order
                            $stmt = $pdo->prepare('DELETE FROM consolidated_orders WHERE id = ?');
                            $stmt->execute([$id]);

                            $pdo->commit();
                            $messages[] = ['type' => 'success', 'text' => 'Consolidated order deleted successfully.'];
                        } catch (Exception $e) {
                            $pdo->rollBack();
                            $messages[] = ['type' => 'danger', 'text' => 'Failed to delete consolidated order.'];
                        }
                    } else {
                        $stmt = $pdo->prepare('DELETE FROM packages WHERE id = ?');
                        if ($stmt->execute([$id])) {
                            $messages[] = ['type' => 'success', 'text' => 'Package deleted successfully.'];
                        } else {
                            $messages[] = ['type' => 'danger', 'text' => 'Failed to delete package.'];
                        }
                    }
                    break;
            }
        }
    } catch (Exception $e) {
        $messages[] = ['type' => 'danger', 'text' => 'An error occurred: ' . $e->getMessage()];
    }
}

// Fetch order details
if ($isConsolidated) {
    $stmt = $pdo->prepare('SELECT co.*, u.username, u.email FROM consolidated_orders co JOIN users u ON co.user_id = u.id WHERE co.id = ?');
    $stmt->execute([$id]);
    $order = $stmt->fetch();
} else {
    $stmt = $pdo->prepare('SELECT p.*, u.username, u.email FROM packages p JOIN users u ON p.user_id = u.id WHERE p.id = ?');
    $stmt->execute([$id]);
    $order = $stmt->fetch();
}

if (!$order) {
    echo '<div class="alert alert-danger">Order not found.</div>';
    exit();
}

// Fetch warehouses for dropdown (individual packages only)
$warehouses = [];
if (!$isConsolidated) {
    $warehouses = $pdo->query('SELECT DISTINCT warehouse FROM packages WHERE warehouse IS NOT NULL AND warehouse != ""')->fetchAll(PDO::FETCH_COLUMN);
}

include 'include/header.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Order Details</h1>
                <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
            </div>

            <!-- Display Messages -->
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message['text']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>

            <div class="card" style="border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,0.04); border-top:4px solid #fca311;">
                <div class="card-header" style="background: #fca311; color: #22223b; border-bottom: none;">
                    <h5 class="card-title mb-0" style="font-weight:700;">
                        <i class="ri-archive-2-line" style="margin-right:8px;"></i>
                        Order Details
                        <span class="badge" style="margin-left:8px; font-size:0.9em; background:#22223b; color:#fca311;">
                            <?= $isConsolidated ? 'Consolidated' : 'Individual' ?>
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="stat-label">Order ID</div>
                            <div class="stat-value" style="font-size:1.2em;">
                                <?= $isConsolidated ? 'C' . $order['id'] : $order['id'] ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-label">Customer</div>
                            <div class="stat-value" style="font-size:1.1em;">
                                <?= htmlspecialchars($order['username']) ?>
                                <br><small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-label">Description</div>
                            <div class="stat-value">
                                <?= htmlspecialchars($isConsolidated ? $order['name'] : $order['product_name']) ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-label">Status</div>
                            <span class="badge badge-lg" style="font-size:1em; background:#fca311; color:#22223b;">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-label">Tracking Number</div>
                            <div class="stat-value">
                                <?= htmlspecialchars($isConsolidated ? $order['master_tracking_number'] : $order['tracking_number']) ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-label"><?= $isConsolidated ? 'Manager Number' : 'Warehouse' ?></div>
                            <div class="stat-value">
                                <?= htmlspecialchars($isConsolidated ? ($order['manager_no'] ?? '-') : $order['warehouse']) ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="stat-label">Date Created</div>
                            <div class="stat-value">
                                <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?>
                            </div>
                        </div>
                        <?php if (!$isConsolidated): ?>
                            <div class="col-md-6 mb-3">
                                <div class="stat-label">ETA</div>
                                <div class="stat-value">
                                    <?= htmlspecialchars($order['eta'] ?? 'Not set') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($isConsolidated): ?>
                            <div class="col-md-6 mb-3">
                                <div class="stat-label">Delivery Address</div>
                                <div class="stat-value">
                                    <?= htmlspecialchars($order['address'] ?? 'Not provided') ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="stat-label">Phone Number</div>
                                <div class="stat-value">
                                    <?= htmlspecialchars($order['phone'] ?? 'Not provided') ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr style="border-top:1px solid #fca311; margin:24px 0;">

                    <div class="row">
                        <!-- Status Update Form -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Update Status</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="action" value="update_status">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Change Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="in_transit" <?= $order['status'] === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="background:#fca311; color:#22223b; border:none;">
                                            Update Status
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Warehouse Update Form (Individual packages only) -->
                        <?php if (!$isConsolidated): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">Update Warehouse</h6>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="update_warehouse">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Assign Warehouse</label>
                                                <select name="warehouse" class="form-control" required>
                                                    <?php if (empty($warehouses)): ?>
                                                        <option value="">No warehouses available</option>
                                                    <?php else: ?>
                                                        <?php foreach ($warehouses as $wh): ?>
                                                            <option value="<?= htmlspecialchars($wh) ?>" <?= ($order['warehouse'] === $wh) ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($wh) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-secondary" style="border:1px solid #fca311; color:#fca311; background:#fff;" <?= empty($warehouses) ? 'disabled' : '' ?>>
                                                Update Warehouse
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Additional Info (for consolidated orders) -->
                        <?php if ($isConsolidated): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0">Consolidation Info</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <strong>Manager Number:</strong> <?= htmlspecialchars($order['manager_no'] ?? 'Not assigned') ?>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Master Tracking:</strong> <?= htmlspecialchars($order['master_tracking_number']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Danger Zone -->
                        <div class="col-md-12 mt-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Danger Zone</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-danger mb-3">
                                        <strong>Warning:</strong> Deleting this order is permanent and cannot be undone.
                                        <?php if ($isConsolidated): ?>
                                            This will also unlink all associated packages from this consolidated order.
                                        <?php endif; ?>
                                    </p>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                        <input type="hidden" name="action" value="delete_order">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="ri-delete-bin-line me-1"></i>
                                            Delete Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>

<style>
    .stat-label {
        font-size: 0.85em;
        color: #666;
        margin-bottom: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1em;
        color: #333;
        font-weight: 500;
    }

    .badge-lg {
        padding: 8px 12px;
        font-size: 0.9em;
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .btn {
        padding: 8px 16px;
        font-weight: 500;
    }

    .alert {
        border: none;
        border-radius: 8px;
    }
</style>