<?php
require_once '../../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../login.php?message=Admin access required');
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../users.php?error=Invalid user ID');
    exit();
}
$id = (int)$_GET['id'];
$stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
$stmt->execute(['admin', $id]);
header('Location: ../users.php?message=' . urlencode('User promoted to admin.'));
exit;
