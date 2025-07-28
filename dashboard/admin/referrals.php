<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';

?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <div id="overview-section" class="content">
        <?php include 'include/navbar.php'; ?>
        <div class="container py-4">
            <h1>Manage Referrals</h1>
            <div class="card mt-4">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Total Referrals</th>
                                <th>Completed Referrals</th>
                                <th>Points</th>
                                <th>Reward ($)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query('SELECT id, username, email FROM users');
                            while ($row = $stmt->fetch()):
                                $stats = getUserReferralStats($pdo, $row['id']);
                                $reward = $stats['points_earned'] * 1; // 1 point = $1 (edit as needed)
                            ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= $stats['total_referrals'] ?></td>
                                    <td><?= $stats['completed_referrals'] ?></td>
                                    <td><?= $stats['points_earned'] ?></td>
                                    <td>$<?= number_format($reward, 2) ?></td>
                                    <td>
                                        <a href="user-referrals.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">View Referrals</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>