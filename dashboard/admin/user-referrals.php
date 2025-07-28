<?php
require_once '../functions/config.php';
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
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$viewUser = $stmt->fetch();
if (!$viewUser) {
    header('Location: users.php?error=User not found');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';
$referrals = getReferralHistory($pdo, $id);
$stats = getUserReferralStats($pdo, $id);
$reward = $stats['points_earned'] * 1; // 1 point = $1 (edit as needed)
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <div id="overview-section" class="content">
        <?php include 'include/navbar.php'; ?>
        <div class="container py-4">
            <a href="view-user.php?id=<?= $viewUser['id'] ?>" class="btn btn-secondary mb-3">&larr; Back to User</a>
            <div class="card mb-4">
                <div class="card-body">
                    <h3><?= htmlspecialchars($viewUser['username']) ?>'s Referrals</h3>
                    <p>Email: <?= htmlspecialchars($viewUser['email']) ?></p>
                    <p>Total Referrals: <?= $stats['total_referrals'] ?> | Completed: <?= $stats['completed_referrals'] ?> | Points: <?= $stats['points_earned'] ?> | Reward: $<?= number_format($reward, 2) ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Referral List</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Reward</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($referrals as $ref): ?>
                                <tr>
                                    <td><?= $ref['referred_id'] ?></td>
                                    <td><?= htmlspecialchars(($ref['referred_first_name'] ?? '') . ' ' . ($ref['referred_last_name'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars($ref['referred_email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($ref['status']) ?></td>
                                    <td><?= isset($ref['reward_amount']) ? '$' . number_format($ref['reward_amount'], 2) : '-' ?></td>
                                    <td><?= htmlspecialchars($ref['referral_date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>