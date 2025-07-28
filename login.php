<?php
// login.php
require_once 'dashboard/functions/auth.php';

$auth = new Auth($pdo);
$message = '';
$messageType = 'error'; // Default to error
$showReactivation = false;

// Check if already logged in
if (isLoggedIn()) {
    header('Location: dashboard/userdashboard.php');
    exit;
}

// Check for messages from URL (like registration success)
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $messageType = 'success'; // URL messages are typically success messages
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $auth->login($email, $password);
    $message = $result['message'];

    if ($result['success']) {
        $messageType = 'success';
        // Redirect to dashboard after successful login
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            header('Location: dashboard/admin/overview.php');
        } else {
            header('Location: dashboard/userdashboard.php');
        }
        exit;
    } else {
        $messageType = 'error';
        if (strpos($message, 'deactivated') !== false) {
            $showReactivation = true;
        }
    }
}
// Handle reactivation request
if (isset($_POST['reactivate_request'])) {
    $reactivateEmail = trim($_POST['reactivate_email'] ?? '');
    $adminEmail = 'admin@yourdomain.com'; // Set your admin email here
    $subject = 'RoyEdge: Reactivation Request';
    $body = "User with email $reactivateEmail has requested account reactivation.";
    @mail($adminEmail, $subject, $body, "From: noreply@yourdomain.com");

    // Insert notification into DB
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$reactivateEmail]);
    $userRow = $stmt->fetch();
    if ($userRow) {
        $userId = $userRow['id'];
        $stmt = $pdo->prepare("INSERT INTO admin_notifications (type, user_id, email, message) VALUES (?, ?, ?, ?)");
        $stmt->execute(['reactivation_request', $userId, $reactivateEmail, 'User requested account reactivation.']);
    }

    $message = 'Your request has been sent to the admin. You will be contacted soon.';
    $messageType = 'success';
    $showReactivation = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background: #007cba;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #005a8b;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        .success {
            background: #efe;
            border: 1px solid #cfc;
            color: #3c3;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }

        .links a {
            color: #007cba;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>Login</h2>

    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Login</button>
    </form>
    <?php if ($showReactivation): ?>
        <div class="message error" style="margin-top:20px;">
            <strong>Your account is deactivated.</strong> If you believe this is a mistake, you can request reactivation below.
        </div>
        <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="reactivate_email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <button type="submit" name="reactivate_request" class="btn btn-warning" style="background:#fca311;color:#22223b;">Request Reactivation</button>
        </form>
    <?php endif; ?>
    <div class="links">
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</body>

</html>