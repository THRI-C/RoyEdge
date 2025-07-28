<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <?php include 'include/navbar.php'; ?>
    <div id="overview-section" class="content">
        <!-- Modal HTML -->
        <div class="modal" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Confirm Action</h5>
                        <button type="button" class="close" onclick="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body" id="modalBody">Are you sure?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                        <a href="#" id="modalConfirmBtn" class="btn btn-primary">Confirm</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Make Admin Confirmation -->
        <div class="modal" id="makeAdminModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Make Admin</h5>
                        <button type="button" class="close" onclick="closeMakeAdminModal()">&times;</button>
                    </div>
                    <div class="modal-body">Are you sure you want to promote this user to admin?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeMakeAdminModal()">Cancel</button>
                        <a href="#" id="makeAdminConfirmBtn" class="btn btn-primary">Confirm</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="container py-4">
            <h1>Manage Users</h1>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query('SELECT id, username, email, role, is_active FROM users');
                                while ($row = $stmt->fetch()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                        <td>
                                            <?php if ($row['is_active']): ?>
                                                <span class="badge" style="background:#28a745;color:#fff;">Active</span>
                                            <?php else: ?>
                                                <span class="badge" style="background:#dc3545;color:#fff;">Suspended</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="view-user.php?id=<?php echo $row['id']; ?>" title="View"><i class="ri-eye-line action-icon"></i></a>
                                            <?php if ($row['is_active']): ?>
                                                <a href="#" title="Suspend" onclick="openSuspendModal('<?php echo $row['id']; ?>','suspend');return false;"><i class="ri-user-unfollow-line action-icon"></i></a>
                                            <?php else: ?>
                                                <a href="#" title="Activate" onclick="openSuspendModal('<?php echo $row['id']; ?>','activate');return false;"><i class="ri-user-follow-line action-icon"></i></a>
                                            <?php endif; ?>
                                            <?php if ($row['role'] !== 'admin'): ?>
                                                <a href="#" title="Make Admin" onclick="openMakeAdminModal('<?php echo $row['id']; ?>');return false;"><i class="ri-shield-user-line action-icon"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'include/footer.php'; ?>
    <script>
        function openMakeAdminModal(userId) {
            document.getElementById('makeAdminConfirmBtn').setAttribute('href', 'functions/make-admin.php?id=' + userId);
            document.getElementById('makeAdminModal').style.display = 'flex';
        }

        function closeMakeAdminModal() {
            document.getElementById('makeAdminModal').style.display = 'none';
        }
    </script>