<?php
require_once __DIR__ . '/functions/config.php';
require_once __DIR__ . '/functions/auth.php';

// Initialize Auth
$auth = new Auth($pdo);

// Perform logout
$auth->logout();

// Redirect to login page with message
header('Location: ../login.php?message=You have been logged out');
exit();
