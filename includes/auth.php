<?php
// Zuvio Global School - Authentication Middleware
require_once dirname(__FILE__) . '/helper.php';

function require_login() {
    safe_session_start();
    if (!isset($_SESSION['user_id']) || empty($_SESSION['username'])) {
        // Redirect to login path
        header('Location: /admin/login');
        exit;
    }
}

function require_admin_role() {
    safe_session_start();
    require_login();
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        echo "<h1>403 Forbidden</h1><p>You do not have administrative privileges to access this area.</p>";
        exit;
    }
}
