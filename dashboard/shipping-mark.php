<?php
require_once 'functions/auth.php';
require_once 'functions/config.php';
require_once 'functions/package-manager.php';
require_once 'functions/settings.php';

$auth = new Auth($pdo);

// Check if user is logged in
if (!$auth->checkSession()) {
    header('Location: ../login.php?message=Please login to access this page');
    exit;
}

// Get current user data
$user = getCurrentUser($pdo);
?>
<?php include 'include/sidebar.php' ?>
<div class="main-content">
    <?php include 'include/navbar.php' ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: #22223b; color: #fca311;">
                        <h4 class="mb-0">Shipping Mark Instructions</h4>
                    </div>
                    <div class="card-body" style="background: #f7f7f7;">
                        <div class="alert" style="background-color: rgba(34,34,59,0.08); border-left: 4px solid #22223b; color: #22223b;">
                            <strong>Important Notice:</strong> Carefully follow these shipping mark details to ensure smooth delivery of your orders.
                        </div>

                        <div class="shipping-mark-container p-4 border rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <label class="text-muted d-block mb-2">User ID</label>
                                        <div class="info-value">SEALCORE-CHUKWUMA3103</div>
                                    </div>
                                    <div class="info-group mb-4">
                                        <label class="text-muted d-block mb-2">Shipping Manager Number</label>
                                        <div class="info-value">09030719089</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <label class="text-muted d-block mb-2">Mode of Shipment</label>
                                        <div class="info-value">(Sea/Air/Express) Specify Exact Mode of Shipment</div>
                                    </div>
                                    <div class="info-group mb-4">
                                        <label class="text-muted d-block mb-2">Location</label>
                                        <div class="info-value">(Specify actual Location)</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3" style="background-color: rgba(34,34,59,0.05); border-radius: 8px;">
                                <h5 style="color: #22223b; font-weight: 600; margin-bottom: 15px;">
                                    <i class="ri-information-line me-2" style="color: #fca311;"></i>Usage Instructions
                                </h5>
                                <ul style="color: #22223b; list-style-type: none; padding-left: 0;">
                                    <li class="mb-2"><i class="ri-checkbox-circle-line me-2" style="color: #fca311;"></i>Include this shipping mark on all your packages</li>
                                    <li class="mb-2"><i class="ri-checkbox-circle-line me-2" style="color: #fca311;"></i>Ensure all information is clearly visible</li>
                                    <li class="mb-2"><i class="ri-checkbox-circle-line me-2" style="color: #fca311;"></i>Attach securely to prevent loss during transit</li>
                                    <li class="mb-2"><i class="ri-checkbox-circle-line me-2" style="color: #fca311;"></i>Keep a copy for your records</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

<style>
    .shipping-mark-container {
        background: #fff;
        border-color: #22223b !important;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #22223b;
        padding: 8px 12px;
        background-color: rgba(34, 34, 59, 0.04);
        border-radius: 6px;
        border-left: 3px solid #22223b;
    }

    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }

    .text-muted {
        color: #4a4e69 !important;
        font-weight: 500;
        font-size: 0.9rem;
    }

    @media print {

        .sidebar,
        .navbar {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
        }

        .shipping-mark-container {
            border: 1px solid #000 !important;
        }

        .info-value {
            border: 1px solid #000 !important;
            background: none !important;
        }
    }
</style>
</body>

</html>