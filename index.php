<?php
require_once __DIR__ . '/backend/src/Session.php';

Session::start();

$baseUrl = '/employee-leave-management-system';

// If not logged in, always go to the login page
if (!Session::isLoggedIn()) {
    header("Location: {$baseUrl}/frontend/public/login.php");
    exit;
}

// If first login, force the login page (change-password flow handled there)
$firstLogin = Session::get('first_login');
if ($firstLogin) {
    header("Location: {$baseUrl}/frontend/public/login.php");
    exit;
}

// Otherwise, route to the correct dashboard
$role = Session::get('role');
if ($role === 'manager') {
    header("Location: {$baseUrl}/frontend/views/manager/manager_dashboard.php");
} else {
    header("Location: {$baseUrl}/frontend/views/employee_dashboard.php");
}
exit;
?>
<?php
// Always send base URL visitors to the login page.
header('Location: /employee-leave-management-system/frontend/public/login.php', true, 302);
exit;