<?php
// config.php - Database configuration
session_start();

// Database settings - CHANGE THESE TO YOUR VALUES
define('DB_HOST', 'localhost');
define('DB_NAME', 'royedge');
define('DB_USER', 'root');
define('DB_PASS', '');

// Security settings
define('HASH_ALGO', PASSWORD_DEFAULT); // Use PHP's default (currently bcrypt)
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Helper function to get current user data
function getCurrentUser($pdo)
{
    if (!isLoggedIn()) {
        return null;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Get user referral stats
function getUserReferralStats($pdo, $user_id)
{
    $stats = [
        'total_referrals' => 0,
        'completed_referrals' => 0,
        'points_earned' => 0
    ];

    try {
        // Get total referrals
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM referrals WHERE referrer_id = ?");
        $stmt->execute([$user_id]);
        $stats['total_referrals'] = $stmt->fetch()['total'];

        // Get completed referrals
        $stmt = $pdo->prepare("SELECT COUNT(*) as completed FROM referrals WHERE referrer_id = ? AND status = 'completed'");
        $stmt->execute([$user_id]);
        $stats['completed_referrals'] = $stmt->fetch()['completed'];

        // Get points earned (if you implement points)
        $stmt = $pdo->prepare("SELECT IFNULL(SUM(reward_amount), 0) as points FROM referral_rewards_log WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $stats['points_earned'] = $stmt->fetch()['points'];
    } catch (PDOException $e) {
        error_log("Error getting referral stats: " . $e->getMessage());
    }

    return $stats;
}

// Get referral history
function getReferralHistory($pdo, $user_id)
{
    $history = [];

    try {
        $stmt = $pdo->prepare("
            SELECT r.*, 
                   u.first_name as referred_first_name,
                   u.last_name as referred_last_name,
                   u.email as referred_email,
                   rl.reward_amount 
            FROM referrals r
            LEFT JOIN users u ON r.referred_id = u.id
            LEFT JOIN referral_rewards_log rl ON r.id = rl.referral_id AND rl.user_id = r.referrer_id
            WHERE r.referrer_id = ?
            ORDER BY r.referral_date DESC
        ");
        $stmt->execute([$user_id]);

        while ($row = $stmt->fetch()) {
            $history[] = $row;
        }
    } catch (PDOException $e) {
        error_log("Error getting referral history: " . $e->getMessage());
    }

    return $history;
}

// Complete a referral (call when referred user completes required action)
function completeReferral($pdo, $referred_user_id, $reward_amount = 100)
{
    try {
        $pdo->beginTransaction();

        // Find referral record
        $stmt = $pdo->prepare("
            SELECT id, referrer_id 
            FROM referrals 
            WHERE referred_id = ? AND status = 'pending'
        ");
        $stmt->execute([$referred_user_id]);
        $referral = $stmt->fetch();

        if (!$referral) {
            $pdo->rollBack();
            return false;
        }

        // Update referral status
        $stmt = $pdo->prepare("
            UPDATE referrals 
            SET status = 'completed', reward_issued = 1 
            WHERE id = ?
        ");
        $stmt->execute([$referral['id']]);

        // Add reward to referrer's account
        $stmt = $pdo->prepare("
            UPDATE users 
            SET points_earned = IFNULL(points_earned, 0) + ? 
            WHERE id = ?
        ");
        $stmt->execute([$reward_amount, $referral['referrer_id']]);

        // Log the reward
        $stmt = $pdo->prepare("
            INSERT INTO referral_rewards_log (referral_id, user_id, reward_amount)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$referral['id'], $referral['referrer_id'], $reward_amount]);

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error completing referral: " . $e->getMessage());
        return false;
    }
}

// --- Reactivation Request Popup AJAX Handler ---
if (isset($_POST['check_reactivation_popup']) && isLoggedIn()) {
    $user = getCurrentUser($pdo);
    if ($user && $user['role'] === 'admin') {
        $stmt = $pdo->prepare("SELECT * FROM admin_notifications WHERE type = 'reactivation_request' AND is_read = 0 ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();
        $note = $stmt->fetch();
        if ($note) {
            echo json_encode([
                'show_popup' => true,
                'user_email' => $note['email'],
                'user_id' => $note['user_id'],
                'notification_id' => $note['id']
            ]);
            exit;
        }
    }
    echo json_encode(['show_popup' => false]);
    exit;
}
