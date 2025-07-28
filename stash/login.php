<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | RoyEdge Enterprise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fonts/remixicon.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="assets/images/logo.png" alt="RoyEdge Logo">
        </div>
        <div class="auth-title">Sign In to RoyEdge</div>
        <form class="auth-form" method="post" action="#">
            <div class="form-group">
                <label for="login-email">Email Address</label>
                <input type="email" id="login-email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" id="login-password" name="password" required autocomplete="current-password">
                <span class="show-password"><i class="ri-eye-line"></i></span>
            </div>
            <button type="submit" class="auth-btn">Login</button>
        </form>
        <a href="signup.php" class="auth-link">Don't have an account? <span>Sign Up</span></a>
    </div>
    <script src="assets/js/auth.js"></script>
</body>

</html>