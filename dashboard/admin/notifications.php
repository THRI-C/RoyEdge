<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';
$stmt = $pdo->query("SELECT * FROM admin_notifications WHERE is_read = 0 ORDER BY created_at DESC");
$notifications = $stmt->fetchAll();
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <div id="overview-section" class="content">
        <?php include 'include/navbar.php'; ?>

        <div class="container py-4">
            <h1>Admin Notifications</h1>
            <div class="card mt-4">
                <div class="card-body">
                    <?php if (empty($notifications)): ?>
                        <p>No new notifications.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>User Email</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($notifications as $note): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($note['type']); ?></td>
                                            <td><?php echo htmlspecialchars($note['email']); ?></td>
                                            <td><?php echo htmlspecialchars($note['message']); ?></td>
                                            <td><?php echo htmlspecialchars($note['created_at']); ?></td>
                                            <td>
                                                <?php if (!empty($note['user_id'])): ?>
                                                    <a href="view-user.php?id=<?php echo $note['user_id']; ?>" class="btn btn-sm btn-primary">View User</a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'include/footer.php'; ?>