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
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <?php include 'include/navbar.php'; ?>
    <div class="container py-4">
        <a href="users.php" class="btn btn-secondary mb-3">&larr; Back to Users</a>
        <div class="card">
            <div class="card-body">
                <h3>User Details</h3>
                <table class="table table-bordered w-100 mt-3">
                    <tr>
                        <th>Profile Image</th>
                        <td>
                            <div style="width:80px; height:80px; border-radius: 50px; overflow:hidden; object-fit:contain; ">
                                <img src="../uploads/profiles/<?php echo htmlspecialchars($viewUser['profile_image']); ?>" alt="User Avatar" class="w-full h-full" style="overflow:hidden;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <td><?php echo $viewUser['id']; ?></td>
                    </tr>
                    <tr>
                        <th>First name</th>
                        <td><?php echo htmlspecialchars($viewUser['first_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Last name</th>
                        <td><?php echo htmlspecialchars($viewUser['last_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?php echo htmlspecialchars($viewUser['username']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($viewUser['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo htmlspecialchars($viewUser['phone']); ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($viewUser['address']); ?></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td><?php echo htmlspecialchars($viewUser['city']); ?></td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td><?php echo htmlspecialchars($viewUser['state']); ?></td>
                    </tr>
                    <tr>
                        <th>L.G.A</th>
                        <td><?php echo htmlspecialchars($viewUser['lga']); ?></td>
                    </tr>
                    <tr>
                        <th>Postal Code</th>
                        <td><?php echo htmlspecialchars($viewUser['postal_code']); ?></td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td><?php echo htmlspecialchars($viewUser['country']); ?></td>
                    </tr>
                    <tr>
                        <th>D.O.B</th>
                        <td><?php echo htmlspecialchars($viewUser['date_of_birth']); ?></td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><?php echo htmlspecialchars($viewUser['role']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $viewUser['is_active'] ? 'Active' : 'Suspended'; ?></td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?php echo htmlspecialchars($viewUser['created_at'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Last Login</th>
                        <td><?php echo htmlspecialchars($viewUser['last_login'] ?? ''); ?></td>
                    </tr>
                </table>
                <a href="user-referrals.php?id=<?php echo $viewUser['id']; ?>" class="btn btn-primary mt-3">View Referrals</a>
            </div>
        </div>
    </div>
</main>
<?php include 'include/footer.php'; ?>