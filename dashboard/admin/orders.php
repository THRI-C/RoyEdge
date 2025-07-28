<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';

// --- Summary Stats ---
$totalOrders = $pdo->query('SELECT COUNT(*) FROM packages')->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM packages WHERE status = 'pending'")->fetchColumn();
$inTransitOrders = $pdo->query("SELECT COUNT(*) FROM packages WHERE status = 'in_transit'")->fetchColumn();
$deliveredOrders = $pdo->query("SELECT COUNT(*) FROM packages WHERE status = 'delivered'")->fetchColumn();
$cancelledOrders = $pdo->query("SELECT COUNT(*) FROM packages WHERE status = 'cancelled'")->fetchColumn();
$todaysOrders = $pdo->query("SELECT COUNT(*) FROM packages WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$consolidatedOrders = $pdo->query('SELECT COUNT(*) FROM consolidated_orders')->fetchColumn();

// --- Filters ---
$where = [];
$params = [];
if (!empty($_GET['status'])) {
    $where[] = 'p.status = ?';
    $params[] = $_GET['status'];
}
if (!empty($_GET['warehouse'])) {
    $where[] = 'p.warehouse = ?';
    $params[] = $_GET['warehouse'];
}
if (!empty($_GET['order_type'])) {
    if ($_GET['order_type'] === 'individual') {
        $where[] = 'p.consolidated_id IS NULL';
    } elseif ($_GET['order_type'] === 'consolidated') {
        $where[] = 'p.consolidated_id IS NOT NULL';
    }
}
if (!empty($_GET['search'])) {
    $where[] = '(p.id = ? OR u.username LIKE ? OR u.email LIKE ? OR p.tracking_number LIKE ?)';
    $params[] = $_GET['search'];
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// --- Fetch Orders (Individual) ---
$sql = "SELECT p.*, u.username, u.email FROM packages p JOIN users u ON p.user_id = u.id $whereSql ORDER BY p.created_at DESC LIMIT 100";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

// --- Fetch Consolidated Orders ---
$consolidated = $pdo->query('SELECT co.*, u.username, u.email FROM consolidated_orders co JOIN users u ON co.user_id = u.id ORDER BY co.created_at DESC LIMIT 100')->fetchAll();

// --- Fetch Warehouses for Filter ---
$warehouses = $pdo->query('SELECT DISTINCT warehouse FROM packages')->fetchAll(PDO::FETCH_COLUMN);
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <?php include 'include/navbar.php'; ?>
    <div class="container py-4">
        <h1>Manage Orders</h1>
        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col">
                <div class="stat-card">Total Orders <div><?= $totalOrders ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">Pending <div><?= $pendingOrders ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">In Transit <div><?= $inTransitOrders ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">Delivered <div><?= $deliveredOrders ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">Cancelled <div><?= $cancelledOrders ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">Today's New <div><?= $todaysOrders ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">Consolidated <div><?= $consolidatedOrders ?></div>
                </div>
            </div>
        </div>
        <!-- Filters -->
        <form class="row g-2 mb-4" method="GET">
            <div class="col-md-2">
                <input type="text" name="search" class="form-control" placeholder="Order ID, User, Tracking" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" <?= (($_GET['status'] ?? '') === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="in_transit" <?= (($_GET['status'] ?? '') === 'in_transit') ? 'selected' : '' ?>>In Transit</option>
                    <option value="delivered" <?= (($_GET['status'] ?? '') === 'delivered') ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= (($_GET['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="warehouse" class="form-control">
                    <option value="">All Warehouses</option>
                    <?php foreach ($warehouses as $wh): ?>
                        <option value="<?= htmlspecialchars($wh) ?>" <?= (($_GET['warehouse'] ?? '') === $wh) ? 'selected' : '' ?>><?= htmlspecialchars($wh) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="order_type" class="form-control">
                    <option value="">All Types</option>
                    <option value="individual" <?= (($_GET['order_type'] ?? '') === 'individual') ? 'selected' : '' ?>>Individual</option>
                    <option value="consolidated" <?= (($_GET['order_type'] ?? '') === 'consolidated') ? 'selected' : '' ?>>Consolidated</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
        <!-- Orders Table -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="mb-2 text-muted" style="font-size:0.95em;">
                    <strong>Note:</strong> <span class="d-inline-block">Use the <b>View/Manage</b> button to open a modal for quick management, or the <b>View/Manage (Page)</b> link to open a full page view.</span>
                </div>
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Description</th>
                            <th>Order Type</th>
                            <th>Status</th>
                            <th>Tracking #</th>
                            <th>Shipping Mark</th>
                            <th>Warehouse</th>
                            <th>Date Created</th>
                            <th>ETA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Individual Orders -->
                        <?php foreach ($orders as $order): ?>
                            <tr class="order-row" data-order-id="<?= $order['id'] ?>" data-type="individual">
                                <td><input type="checkbox" class="order-checkbox" value="<?= $order['id'] ?>"></td>
                                <td><?= $order['id'] ?></td>
                                <td><?= htmlspecialchars($order['username']) ?> <br><small><?= htmlspecialchars($order['email']) ?></small></td>
                                <td><?= htmlspecialchars($order['product_name']) ?></td>
                                <td><span class="badge bg-info">Individual</span></td>
                                <td><span class="badge bg-<?= $order['status'] === 'delivered' ? 'success' : ($order['status'] === 'in_transit' ? 'warning' : ($order['status'] === 'cancelled' ? 'danger' : 'secondary')) ?>"><?= ucfirst($order['status']) ?></span></td>
                                <td><?= htmlspecialchars($order['tracking_number']) ?></td>
                                <td><?= htmlspecialchars($order['shipping_mark'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($order['warehouse']) ?></td>
                                <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                                <td><?= htmlspecialchars($order['eta'] ?? '-') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-order-btn" data-type="individual" data-id="<?= $order['id'] ?>">View/Manage</button>
                                    <a href="order-details.php?id=<?= $order['id'] ?>&type=individual" class="btn btn-sm btn-outline-primary ms-1" target="_blank">View/Manage (Page)</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Consolidated Orders -->
                        <?php foreach ($consolidated as $co): ?>
                            <tr class="order-row" data-order-id="<?= $co['id'] ?>" data-type="consolidated">
                                <td><input type="checkbox" class="order-checkbox" value="c<?= $co['id'] ?>"></td>
                                <td>C<?= $co['id'] ?></td>
                                <td><?= htmlspecialchars($co['username']) ?> <br><small><?= htmlspecialchars($co['email']) ?></small></td>
                                <td><?= htmlspecialchars($co['name']) ?></td>
                                <td><span class="badge bg-secondary">Consolidated</span></td>
                                <td><span class="badge bg-<?= $co['status'] === 'delivered' ? 'success' : ($co['status'] === 'in_transit' ? 'warning' : ($co['status'] === 'cancelled' ? 'danger' : 'secondary')) ?>"><?= ucfirst($co['status']) ?></span></td>
                                <td><?= htmlspecialchars($co['master_tracking_number']) ?></td>
                                <td>-</td>
                                <td><?= htmlspecialchars($co['manager_no'] ?? '-') ?></td>
                                <td><?= date('M d, Y', strtotime($co['created_at'])) ?></td>
                                <td>-</td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-order-btn" data-type="consolidated" data-id="<?= $co['id'] ?>">View/Manage</button>
                                    <a href="order-details.php?id=<?= $co['id'] ?>&type=consolidated" class="btn btn-sm btn-outline-primary ms-1" target="_blank">View/Manage (Page)</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Bulk Actions -->
                <div class="mt-3">
                    <button class="btn btn-secondary btn-sm" id="bulk-status">Change Status</button>
                    <button class="btn btn-secondary btn-sm" id="bulk-warehouse">Assign Warehouse</button>
                    <button class="btn btn-danger btn-sm" id="bulk-delete">Delete Selected</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Details Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="order-modal-content">
                <!-- Modal content loaded by JS -->
            </div>
        </div>
    </div>
    <?php include 'include/footer.php'; ?>
    <script src="../../asset/js/jquery-3.7.1.min.js"></script>
    <script src="../../asset/js/bootstrap.min.js"></script>
    <script>
        // Modal logic (plain JS/jQuery)
        $(document).on('click', '.view-order-btn', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            $.get('order-details.php', {
                id: id,
                type: type
            }, function(html) {
                $('#order-modal-content').html(html);
                $('#orderModal').modal('show');
            });
        });
        $('#select-all').on('change', function() {
            $('.order-checkbox').prop('checked', this.checked);
        });
        // Bulk Actions
        function getSelectedOrders() {
            var ids = [];
            $('.order-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }
        $('#bulk-status').on('click', function() {
            var ids = getSelectedOrders();
            if (ids.length === 0) {
                alert('Select at least one order.');
                return;
            }
            var status = prompt('Enter new status (pending, in_transit, delivered, cancelled):');
            if (!status) return;
            $.post('bulk-orders-action.php', {
                action: 'update_status',
                ids: ids,
                status: status
            }, function(resp) {
                alert(resp.message || resp);
                location.reload();
            }, 'json');
        });
        $('#bulk-warehouse').on('click', function() {
            var ids = getSelectedOrders();
            if (ids.length === 0) {
                alert('Select at least one order.');
                return;
            }
            var warehouse = prompt('Enter warehouse name:');
            if (!warehouse) return;
            $.post('bulk-orders-action.php', {
                action: 'update_warehouse',
                ids: ids,
                warehouse: warehouse
            }, function(resp) {
                alert(resp.message || resp);
                location.reload();
            }, 'json');
        });
        $('#bulk-delete').on('click', function() {
            var ids = getSelectedOrders();
            if (ids.length === 0) {
                alert('Select at least one order.');
                return;
            }
            if (!confirm('Are you sure you want to delete selected orders?')) return;
            $.post('bulk-orders-action.php', {
                action: 'delete',
                ids: ids
            }, function(resp) {
                alert(resp.message || resp);
                location.reload();
            }, 'json');
        });
    </script>
    <style>
        .stat-card {
            background: #f7f7f7;
            border-radius: 8px;
            padding: 18px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 10px;
        }
    </style>
</main>