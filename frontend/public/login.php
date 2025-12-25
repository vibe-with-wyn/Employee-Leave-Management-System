<?php
require_once __DIR__ . '/../../backend/controllers/LoginController.php';

// Clear any lingering sessions on login page
if (isset($_GET['clear']) && $_GET['clear'] === '1') {
    Session::start();
    Session::destroy();
    
    // Clear all cookies
    foreach ($_COOKIE as $name => $value) {
        setcookie($name, '', time() - 3600, '/');
    }
    
    header('Location: login.php');
    exit;
}

$controller = new LoginController();
$data = $controller->handleLogin();
require_once __DIR__ . '/../views/login_view.php';
?>