<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../src/Session.php';
require_once __DIR__ . '/../utils/redirect.php';

class LogoutController {
    private $auth;
    private $baseUrl = '/employee-leave-management-system';

    public function __construct() {
        Session::start();
        $this->auth = new Auth();
    }

    public function handleLogout() {
        // Set headers to prevent caching
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $this->auth->logout();
        
        // Redirect with clear parameter to ensure clean state
        redirect($this->baseUrl . '/frontend/public/login.php?clear=1', 'You have been logged out.', 'success');
    }
}

// Handle request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller = new LogoutController();
    $controller->handleLogout();
} else {
    error_log("LogoutController: No valid action provided");
    http_response_code(400);
    echo "Invalid action.";
}