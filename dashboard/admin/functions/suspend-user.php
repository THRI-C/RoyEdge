<?php
require_once '../../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../login.php?message=Admin access required');
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php?error=Invalid user ID');
    exit();
}
$id = (int)$_GET['id'];
$activate = isset($_GET['activate']) ? 1 : 0;
$stmt = $pdo->prepare('UPDATE users SET is_active = ? WHERE id = ?');
$stmt->execute([$activate ? 1 : 0, $id]);
$msg = $activate ? 'User activated.' : 'User suspended.';
header('Location: ../users.php?message=' . urlencode($msg));
exit;
