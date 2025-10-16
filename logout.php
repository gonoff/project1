<?php
require_once __DIR__ . '/config/config.php';

// Destroy session
session_destroy();
$_SESSION = [];

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login
setFlash('success', 'You have been logged out successfully.');
redirect(BASE_URL . '/login.php');
?>
