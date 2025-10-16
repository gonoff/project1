<?php
/**
 * Helper Functions
 * Common utility functions used throughout the application
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a specific URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Display flash message HTML
 */
function displayFlash() {
    $flash = getFlash();
    if ($flash) {
        $icon = [
            'success' => 'check-circle-fill',
            'error' => 'x-circle-fill',
            'warning' => 'exclamation-triangle-fill',
            'info' => 'info-circle-fill'
        ][$flash['type']] ?? 'info-circle-fill';

        echo '<div class="alert alert-' . $flash['type'] . '">';
        echo '<i class="bi bi-' . $icon . '"></i> ';
        echo $flash['message'];
        echo '</div>';
    }
}

/**
 * Format date
 */
function formatDate($date, $format = 'F j, Y') {
    return date($format, strtotime($date));
}

/**
 * Debug helper
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
?>
