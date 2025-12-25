<?php
require_once __DIR__ . '/backend/src/Session.php';
require_once __DIR__ . '/backend/config/config.php';
require_once __DIR__ . '/backend/utils/redirect.php';

Session::start();

// Redirect logged-in users to their dashboard
if (Session::isLoggedIn()) {
    $role = Session::getRole();
    if ($role === 'employee') {
        redirect(BASE_URL . '/frontend/views/employee_dashboard.php');
    } elseif ($role === 'manager') {
        redirect(BASE_URL . '/frontend/views/manager/manager_dashboard.php');
    }
}

// Redirect to login page
redirect(BASE_URL . '/frontend/public/login.php');
?>