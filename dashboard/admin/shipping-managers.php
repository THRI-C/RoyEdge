<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DEBUG: Show POST and GET data
if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_GET)) {
    echo '<pre>POST: ';
    print_r($_POST);
    echo '</pre>';
    echo '<pre>GET: ';
    print_r($_GET);
    echo '</pre>';
}

require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';

// Handle add shipping manager
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region = trim($_POST['region'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    if ($region && $name && $contact) {
        try {
            $stmt = $pdo->prepare('INSERT INTO shipping_managers (region, name, contact) VALUES (?, ?, ?)');
            $result = $stmt->execute([$region, $name, $contact]);
            if ($result) {
                echo '<div style="color:green;">Insert successful</div>';
            } else {
                echo '<div style="color:red;">Insert failed</div>';
            }
            header('Location: shipping-managers.php?success=1');
            exit();
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">PDO Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    }
}
if (isset($_GET['success'])) {
    $message = '<div class="alert alert-success">Shipping manager added successfully!</div>';
}
$managers = $pdo->query('SELECT * FROM shipping_managers ORDER BY region, name')->fetchAll();
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <div id="overview-section" class="content">
        <?php include 'include/navbar.php'; ?>
        <div class="container py-4">
            <h1>Manage Shipping Managers</h1>
            <?= $message ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="shipping-managers.php" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="region" class="form-control" placeholder="Region/Location" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Manager Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="contact" class="form-control" placeholder="Contact" required>
                        </div>
                        <div class="col-md-1">
                            <input type="submit" name="add_manager" class="btn btn-primary" value="Add">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Region</th>
                                <th>Name</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($managers as $mgr): ?>
                                <tr>
                                    <td><?= $mgr['id'] ?></td>
                                    <td><?= htmlspecialchars($mgr['region']) ?></td>
                                    <td><?= htmlspecialchars($mgr['name']) ?></td>
                                    <td><?= htmlspecialchars($mgr['contact']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include 'include/footer.php'; ?>