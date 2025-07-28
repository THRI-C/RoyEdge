 <?php
    require_once 'functions/auth.php';
    require_once 'functions/config.php';
    require_once 'functions/package-manager.php';
    require_once 'functions/settings.php';

    $auth = new Auth($pdo);

    // Check if user is logged in
    if (!isLoggedIn()) {
        header('Location: ../login.php?message=Please login to access this page');
        exit();
    }

    $user = getCurrentUser($pdo);
    $user_id = $user['id'];

    // Generate referral code if user doesn't have one
    if (empty($user['referral_code'])) {
        $referral_code = substr(md5(uniqid($user_id, true)), 0, 8);
        $stmt = $pdo->prepare("UPDATE users SET referral_code = ? WHERE id = ?");
        $stmt->execute([$referral_code, $user_id]);
        $user['referral_code'] = $referral_code; // Update local user data
    }

    // Get referral stats and history
    $referral_stats = getUserReferralStats($pdo, $user_id);
    $referral_history = getReferralHistory($pdo, $user_id);

    // Handle referral completion (example - you might trigger this elsewhere)
    if (isset($_GET['complete_referral']) && is_numeric($_GET['complete_referral'])) {
        completeReferral($pdo, $_GET['complete_referral'], 100); // 100 points reward
        header('Location: referrals.php'); // Refresh to show updated status
        exit();
    }
    ?>

 <?php include 'include/sidebar.php'; ?>
 <div class="main-content">
     <div class="container-fluid py-4">
         <?php include 'include/navbar.php'; ?>
         <div class="row">
             <div class="col-lg-12">
                 <!-- Referral Header -->
                 <div class="d-flex justify-content-between align-items-center mb-4">
                     <h2 class="mb-0">Referral Program</h2>
                     <span class="badge badge-primary">Earn <?php echo 100; ?> points per referral</span>
                 </div>

                 <!-- Referral Link Card -->
                 <div class="card mb-4">
                     <div class="card-body">
                         <h5 class="card-title">Your Referral Link</h5>
                         <p class="card-text">Share this link with friends and earn rewards when they sign up and complete their profile!</p>

                         <div class="input-group mb-3">
                             <input type="text" id="referralLink" class="form-control"
                                 value="<?php echo "http://" . $_SERVER['HTTP_HOST'] . "/Royedge/register.php?ref=" . $user['referral_code']; ?>" readonly>
                             <div class="input-group-append">
                                 <button class="btn btn-primary" onclick="copyReferralLink()">
                                     <i class="fas fa-copy"></i> Copy Link
                                 </button>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Referral Stats -->
                 <div class="row mb-4">
                     <div class="col-md-4">
                         <div class="stat-card">
                             <h6>Total Referrals</h6>
                             <h3><?php echo $referral_stats['total_referrals']; ?></h3>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="stat-card">
                             <h6>Completed</h6>
                             <h3><?php echo $referral_stats['completed_referrals']; ?></h3>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="stat-card">
                             <h6>Points Earned</h6>
                             <h3><?php echo $referral_stats['points_earned']; ?></h3>
                         </div>
                     </div>
                 </div>

                 <!-- Referral History -->
                 <div class="card">
                     <div class="card-body">
                         <div class="d-flex justify-content-between align-items-center mb-3">
                             <h5 class="card-title mb-0">Your Referral History</h5>
                             <small class="text-muted">Last 50 referrals</small>
                         </div>

                         <div class="table-responsive">
                             <table class="table table-hover">
                                 <thead class="thead-light">
                                     <tr>
                                         <th>Date</th>
                                         <th>Referred User</th>
                                         <th>Status</th>
                                         <th>Points</th>
                                         <th>Actions</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php if (!empty($referral_history)): ?>
                                         <?php foreach ($referral_history as $referral): ?>
                                             <tr>
                                                 <td><?php echo date('M j, Y', strtotime($referral['referral_date'])); ?></td>
                                                 <td>
                                                     <?php
                                                        echo !empty($referral['referred_first_name'])
                                                            ? htmlspecialchars($referral['referred_first_name'] . ' ' . $referral['referred_last_name'])
                                                            : htmlspecialchars($referral['referred_email']);
                                                        ?>
                                                 </td>
                                                 <td>
                                                     <span class="badge badge-<?php
                                                                                echo $referral['status'] == 'completed' ? 'completed' : ($referral['status'] == 'pending' ? 'pending' : 'rejected');
                                                                                ?>">
                                                         <?php echo ucfirst($referral['status']); ?>
                                                     </span>
                                                 </td>
                                                 <td><?php echo $referral['reward_amount'] ?? '0'; ?></td>
                                                 <td>
                                                     <?php if ($referral['status'] == 'pending'): ?>
                                                         <a href="referrals.php?complete_referral=<?php echo $referral['referred_id']; ?>"
                                                             class="btn btn-sm btn-outline-success">
                                                             Mark Complete
                                                         </a>
                                                     <?php endif; ?>
                                                 </td>
                                             </tr>
                                         <?php endforeach; ?>
                                     <?php else: ?>
                                         <tr>
                                             <td colspan="5" class="text-center py-4">No referrals yet. Share your link to start earning!</td>
                                         </tr>
                                     <?php endif; ?>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

 <script src="assets/js/jquery-3.7.1.min.js"></script>
 <script src="assets/js/bootstrap.bundle.min.js"></script>
 <script src="assets/js/fontawesome.min.js"></script>
 <script>
     function copyReferralLink() {
         const copyText = document.getElementById("referralLink");
         copyText.select();
         copyText.setSelectionRange(0, 99999);
         let successful = false;
         try {
             successful = document.execCommand("copy");
         } catch (err) {
             successful = false;
         }
         // Find the button
         const btn = document.querySelector('.input-group-append .btn');
         if (successful && btn) {
             const originalHTML = btn.innerHTML;
             btn.innerHTML = 'Copied!';
             btn.disabled = true;
             setTimeout(function() {
                 btn.innerHTML = originalHTML;
                 btn.disabled = false;
             }, 2000);
         }
     }
 </script>
 </body>

 </html>