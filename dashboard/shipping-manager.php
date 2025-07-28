<?php
// userdashboard.php - Updated with authentication
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
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: #22223b; color: #fca311;">
                        <h4 class="mb-0">Shipping Managers</h4>
                    </div>
                    <div class="card-body" style="background: #f7f7f7;">
                        <div class="alert" style="background-color: rgba(34,34,59,0.08); border-left: 4px solid #22223b; color: #22223b;">
                            <strong>Contact the appropriate shipping manager for your region using the WhatsApp numbers below.</strong>
                        </div>
                        <div class="shipping-managers-list mt-4">
                            <div class="manager-item mb-4 p-3 rounded d-flex align-items-center justify-content-between" style="background: #fff; border-left: 4px solid #22223b;">
                                <div>
                                    <div class="manager-title" style="font-weight: 600; color: #22223b;">China Shipping Manager</div>
                                    <div class="manager-contact text-muted">WhatsApp (仅聊天): <span class="manager-number" id="number-china">+8613136591078</span></div>
                                </div>
                                <button class="btn btn-light copy-btn ms-3" data-copy-target="number-china" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.3rem;"></i></button>
                            </div>
                            <div class="manager-item mb-4 p-3 rounded d-flex align-items-center justify-content-between" style="background: #fff; border-left: 4px solid #22223b;">
                                <div>
                                    <div class="manager-title" style="font-weight: 600; color: #22223b;">Nigeria Shipping Manager (Lagos)</div>
                                    <div class="manager-contact text-muted">WhatsApp (Chat): <span class="manager-number" id="number-lagos">+2349030719089</span></div>
                                </div>
                                <button class="btn btn-light copy-btn ms-3" data-copy-target="number-lagos" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.3rem;"></i></button>
                            </div>
                            <div class="manager-item mb-4 p-3 rounded d-flex align-items-center justify-content-between" style="background: #fff; border-left: 4px solid #22223b;">
                                <div>
                                    <div class="manager-title" style="font-weight: 600; color: #22223b;">Nigeria Shipping Manager (Onitsha)</div>
                                    <div class="manager-contact text-muted">WhatsApp (Chat): <span class="manager-number" id="number-onitsha">+2348109373139</span></div>
                                </div>
                                <button class="btn btn-light copy-btn ms-3" data-copy-target="number-onitsha" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.3rem;"></i></button>
                            </div>
                            <div class="manager-item mb-2 p-3 rounded d-flex align-items-center justify-content-between" style="background: #fff; border-left: 4px solid #22223b;">
                                <div>
                                    <div class="manager-title" style="font-weight: 600; color: #22223b;">UK Shipping Manager</div>
                                    <div class="manager-contact text-muted">WhatsApp (Chat): <span class="manager-number" id="number-uk">+447715239957</span></div>
                                </div>
                                <button class="btn btn-light copy-btn ms-3" data-copy-target="number-uk" title="Copy Number"><i class="ri-file-copy-line" style="color: #fca311; font-size: 1.3rem;"></i></button>
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
    .shipping-managers-list .manager-item {
        transition: box-shadow 0.2s;
    }

    .shipping-managers-list .manager-item:hover {
        box-shadow: 0 2px 12px rgba(34, 34, 59, 0.08);
    }

    .copy-btn {
        border: none;
        background: #fff;
        box-shadow: 0 1px 4px rgba(34, 34, 59, 0.04);
        border-radius: 6px;
        transition: background 0.2s;
    }

    .copy-btn:hover {
        background: #fca31122;
    }
</style>
</body>

</html>