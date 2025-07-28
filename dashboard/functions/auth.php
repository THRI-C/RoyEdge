<?php
// auth.php - Authentication functions with referral support
require_once 'config.php';

class Auth
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Register a new user with optional referral support
    public function register($username, $email, $password, $referral_code = null)
    {
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }

        // Check if user already exists
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);

        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        try {
            $this->pdo->beginTransaction();

            // Insert user
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password_hash, referral_code) 
                VALUES (?, ?, ?, ?)
            ");
            $user_code = substr(md5(uniqid($email, true)), 0, 8);
            $stmt->execute([$username, $email, $passwordHash, $user_code]);
            $user_id = $this->pdo->lastInsertId();

            // Process referral if exists
            if ($referral_code) {
                $this->processReferral($user_id, $referral_code);
            }

            $this->pdo->commit();

            return ['success' => true, 'message' => 'Registration successful', 'user_id' => $user_id];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }

    // Process referral during registration
    private function processReferral($referred_user_id, $referral_code)
    {
        try {
            // Find referrer by code
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE referral_code = ?");
            $stmt->execute([$referral_code]);
            $referrer = $stmt->fetch();

            if (!$referrer) {
                return false; // Invalid referral code
            }

            // Create referral record
            $stmt = $this->pdo->prepare("
                INSERT INTO referrals (referrer_id, referred_id, referral_code, status)
                VALUES (?, ?, ?, 'pending')
            ");
            $stmt->execute([$referrer['id'], $referred_user_id, $referral_code]);

            return true;
        } catch (PDOException $e) {
            error_log("Referral processing failed: " . $e->getMessage());
            return false;
        }
    }

    // Login user
    public function login($email, $password)
    {
        // Validate input
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required'];
        }

        // Get user from database
        $stmt = $this->pdo->prepare("SELECT id, username, email, password_hash, role, is_active FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Check if user exists and password is correct
        if ($user) {
            if (!$user['is_active']) {
                return ['success' => false, 'message' => 'Your account is deactivated. Please contact support.'];
            }
            if (password_verify($password, $user['password_hash'])) {
                // Start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_time'] = time();

                // Update last login
                $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);

                return ['success' => true, 'message' => 'Login successful'];
            }
        }

        return ['success' => false, 'message' => 'Invalid email or password'];
    }

    // Logout user
    public function logout()
    {
        // Clear all session data
        $_SESSION = [];

        // Destroy session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy session
        session_destroy();

        return ['success' => true, 'message' => 'Logged out successfully'];
    }

    // Check if session is valid
    public function checkSession()
    {
        if (!isLoggedIn()) {
            return false;
        }

        // Check session timeout
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_LIFETIME) {
            $this->logout();
            return false;
        }

        return true;
    }

    // Change password
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        // Get current user data
        $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // Verify current password
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        // Validate new password
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'New password must be at least 6 characters'];
        }

        // Update password
        $newPasswordHash = password_hash($newPassword, HASH_ALGO);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newPasswordHash, $userId]);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }
}
