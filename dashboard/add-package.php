<?php
require_once 'functions/auth.php';
require_once 'functions/config.php';
require_once 'functions/package-manager.php';

$auth = new Auth($pdo);
if (!$auth->checkSession()) {
    header('Location: ../login.php?message=Please login to access this page');
    exit;
}

$user = getCurrentUser($pdo);
if (!$user) {
    header('Location: ../login.php?message=User verification failed');
    exit;
}

$packageManager = new PackageManager($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['logout'])) {
            $auth->logout();
            header('Location: ../login.php?message=You have been logged out');
            exit;
        }

        $packageManager->addPackage($_POST, $user['id']);
        $message = '<div class="alert alert-success">Package added successfully!</div>';
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
?>

<?php include 'include/sidebar.php' ?>
<div class="main-content">
    <?php include 'include/navbar.php' ?>
    <?php echo $message; ?>
    <form method="POST">
        <div class='mb-4'>
            <label>Product Name</label>
            <input type='text' name="product_name" class='form-control' placeholder='Enter package name' required>
        </div>
        <div class='mb-4'>
            <label>Tracking Number</label>
            <input type='text' name="tracking_number" class='form-control' placeholder='Enter Tracking Number' required>
        </div>
        <div class='mb-4'>
            <label>Amount/Quantity of Goods</label>
            <input type='text' name="quantity" class='form-control' placeholder='Enter quantity' required>
        </div>
        <div class='mb-4'>
            <label>Mode of Shipment</label>
            <select name="shipment_mode" class='form-control' required>
                <option value="">Select shipment mode</option>
                <option value="sea">Sea</option>
                <option value="air">Air Freight</option>
                <option value="express">Express</option>
            </select>
        </div>
        <div class='mb-4'>
            <label>Nature of Goods</label>
            <select name="goods_nature" class='form-control' required>
                <option value="">Select nature of goods</option>
                <option value="liquid">Liquid (measurement value here)</option>
                <option value="normal">Normal (GZ)</option>
                <option value="fragile">Fragile</option>
            </select>
        </div>
        <div class='mb-4'>
            <label>Warehouse Address</label>
            <select name="warehouse" class='form-control' required>
                <option value="">Select warehouse</option>
                <option value="china">China warehouse</option>
                <option value="US">US Warehouse</option>
                <option value="UK">UK Warehouse</option>
            </select>
        </div>
        <button class='btn btn-primary' type='submit'>Add Package</button>
    </form>
</div>
</div>
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>