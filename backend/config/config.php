<?php
// Detect environment (local vs production)
$isProduction = isset($_SERVER['HTTP_HOST']) && 
                (strpos($_SERVER['HTTP_HOST'], 'infinityfree') !== false || 
                 strpos($_SERVER['HTTP_HOST'], 'vibe-with-wyn') !== false);

// Get the actual domain
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Base URL configuration
if ($isProduction) {
    define('BASE_URL', '/employee-leave-management-system');
    define('FULL_URL', $protocol . '://' . $host . '/employee-leave-management-system');
} else {
    define('BASE_URL', '/employee-leave-management-system');
    define('FULL_URL', 'http://localhost/employee-leave-management-system');
}

// Database configuration
if ($isProduction) {
    define('DB_HOST', 'sql211.infinityfree.com');
    define('DB_NAME', 'if0_40752096_leave_management');
    define('DB_USER', 'if0_40752096');
    define('DB_PASS', 'BKhAGIbyv6');
} else {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'leave_management');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

// Error reporting based on environment
if ($isProduction) {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
if ($isProduction && isset($_SERVER['HTTPS'])) {
    ini_set('session.cookie_secure', 1);
}

return [
    'isProduction' => $isProduction,
    'baseUrl' => BASE_URL,
    'fullUrl' => FULL_URL
];
?>
