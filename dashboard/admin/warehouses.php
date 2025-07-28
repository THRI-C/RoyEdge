<?php
require_once '../functions/config.php';
$user = getCurrentUser($pdo);
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../login.php?message=Admin access required');
    exit();
}
include 'include/header.php';
include 'include/sidebar.php';

// Handle add warehouse
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_warehouse'])) {
    $country = trim($_POST['country']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);

    if ($country && $address && $contact) {
        try {
            $stmt = $pdo->prepare('INSERT INTO warehouses (country, address, contact) VALUES (?, ?, ?)');
            $stmt->execute([$country, $address, $contact]);
            $message = '<div class="alert alert-success">Warehouse added successfully!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Error adding warehouse: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    }
}

$warehouses = $pdo->query('SELECT * FROM warehouses')->fetchAll();
?>
<link rel="stylesheet" href="../assets/css/admin.css">
<main class="main-content" id="main-content">
    <div id="overview-section" class="content">
        <?php include 'include/navbar.php'; ?>
        <div class="container py-4">
            <h1>Manage Warehouses</h1>
            <?= $message ?>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="warehouses.php" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="country" class="form-control" placeholder="Country" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="address" class="form-control" placeholder="Address" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="contact" class="form-control" placeholder="Contact" required>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_warehouse" class="btn btn-primary">Add</button>
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
                                <th>Country</th>
                                <th>Address</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($warehouses as $wh): ?>
                                <tr>
                                    <td><?= $wh['id'] ?></td>
                                    <td><?= htmlspecialchars($wh['country']) ?></td>
                                    <td><?= htmlspecialchars($wh['address']) ?></td>
                                    <td><?= htmlspecialchars($wh['contact']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>
    </div>
</main>
</body>

</html>