<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';
include 'include/navbar.php';
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="container py-4">
    <h1>Admin Settings</h1>
    <div class="card mt-4">
        <div class="card-body">
            <!-- Settings form or options will go here -->
            <p>Settings management coming soon.</p>
        </div>
    </div>
</div>
<?php include 'include/footer.php'; ?>