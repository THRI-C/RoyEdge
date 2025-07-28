<?php
require_once 'auth.php';
require_once 'package-manager.php';
require_once 'config.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    $packageManager = new PackageManager($pdo);
    try {
        $packageManager->deletePackage($_POST['package_id'], $user['id']);
        header('Location: manage-orders.php?message=Package deleted successfully');
    } catch (Exception $e) {
        header('Location: manage-orders.php?error=' . urlencode($e->getMessage()));
    }
    exit;
}

header('Location: manage-orders.php');