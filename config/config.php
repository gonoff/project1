<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting - change to 0 in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base paths
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_PATH . '/assets');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('MODULES_PATH', BASE_PATH . '/modules');
define('HELPERS_PATH', BASE_PATH . '/helpers');

// URL paths
define('BASE_URL', '/project1');
define('ASSETS_URL', BASE_URL . '/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('ICONS_URL', ASSETS_URL . '/icons');

// Database configuration
require_once __DIR__ . '/database.php';

// Load helper functions
require_once HELPERS_PATH . '/functions.php';
?>
