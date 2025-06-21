<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up | RoyEdge Enterprise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/fonts/remixicon.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="assets/images/logo.png" alt="RoyEdge Logo">
        </div>
        <div class="auth-title">Create Your Account</div>
        <form class="auth-form" method="post" action="#">
            <div class="form-group">
                <label for="signup-name">Full Name</label>
                <input type="text" id="signup-name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="signup-email">Email Address</label>
                <input type="email" id="signup-email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" name="password" required autocomplete="new-password">
                <span class="show-password"><i class="ri-eye-line"></i></span>
            </div>
            <button type="submit" class="auth-btn">Sign Up</button>
        </form>
        <a href="login.php" class="auth-link">Already have an account? <span>Login</span></a>
    </div>
    <script src="assets/js/auth.js"></script>
</body>

</html>