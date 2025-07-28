<?php
// register.php
require_once 'dashboard/functions/auth.php';

$auth = new Auth($pdo);
$message = '';

// Capture referral code from URL
$referral_code = $_GET['ref'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $referral_code = $_POST['referral_code'] ?? $referral_code;

    $result = $auth->register($username, $email, $password, $referral_code);
    $message = $result['message'];

    if ($result['success']) {
        // Redirect to login page after successful registration
        header('Location: login.php?message=Registration successful! Please login.');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        input[type="text"],
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
    </style>
</head>

<body>
    <h2>Register</h2>

    <?php if ($message): ?>
        <div class="message error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="referral_code" value="<?php echo htmlspecialchars($referral_code); ?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required
                value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="links">
        <a href="login.php">Already have an account? Login here</a>
    </div>
</body>

</html>