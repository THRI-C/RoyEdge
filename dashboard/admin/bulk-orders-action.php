<?php
require_once '../functions/config.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action']) || !isset($_POST['ids'])) {
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}
$ids = $_POST['ids'];
if (!is_array($ids)) {
    $ids = explode(',', $ids);
}
$action = $_POST['action'];
try {
    if ($action === 'update_status') {
        $status = $_POST['status'] ?? '';
        foreach ($ids as $id) {
            if (strpos($id, 'c') === 0) {
                $realId = substr($id, 1);
                $stmt = $pdo->prepare('UPDATE consolidated_orders SET status = ? WHERE id = ?');
                $stmt->execute([$status, $realId]);
            } else {
                $stmt = $pdo->prepare('UPDATE packages SET status = ? WHERE id = ?');
                $stmt->execute([$status, $id]);
            }
        }
        echo json_encode(['message' => 'Status updated for selected orders.']);
        exit();
    }
    if ($action === 'update_warehouse') {
        $warehouse = $_POST['warehouse'] ?? '';
        foreach ($ids as $id) {
            if (strpos($id, 'c') !== 0) { // Only individual orders
                $stmt = $pdo->prepare('UPDATE packages SET warehouse = ? WHERE id = ?');
                $stmt->execute([$warehouse, $id]);
            }
        }
        echo json_encode(['message' => 'Warehouse updated for selected orders.']);
        exit();
    }
    if ($action === 'delete') {
        foreach ($ids as $id) {
            if (strpos($id, 'c') === 0) {
                $realId = substr($id, 1);
                $stmt = $pdo->prepare('DELETE FROM consolidated_orders WHERE id = ?');
                $stmt->execute([$realId]);
            } else {
                $stmt = $pdo->prepare('DELETE FROM packages WHERE id = ?');
                $stmt->execute([$id]);
            }
        }
        echo json_encode(['message' => 'Selected orders deleted.']);
        exit();
    }
    echo json_encode(['error' => 'Unknown action.']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
