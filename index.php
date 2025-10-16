<?php
require_once __DIR__ . '/config/config.php';

// Redirect based on login status
if (isLoggedIn()) {
    // User is logged in, redirect to dashboard
    redirect(BASE_URL . '/dashboard.php');
} else {
    // User is not logged in, redirect to login page
    redirect(BASE_URL . '/login.php');
}
?>
