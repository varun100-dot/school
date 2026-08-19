<?php
// Zuvio Global School - Authentication and Permission Middleware
require_once dirname(__FILE__) . '/helper.php';

function require_login() {
    global $db;
    safe_session_start();
    if (!isset($_SESSION['user_id']) || empty($_SESSION['username'])) {
        header('Location: /admin/login');
        exit;
    }
    
    // Update last activity timestamp
    if ($db) {
        try {
            $stmt = $db->prepare("UPDATE `users` SET `last_activity` = CURRENT_TIMESTAMP WHERE `id` = ?");
            $stmt->execute([$_SESSION['user_id']]);
        } catch (Exception $e) {
            // Ignore error
        }
    }
}

// Check if user has granular permission cached in session
function has_permission($permission) {
    safe_session_start();
    if (!isset($_SESSION['permissions'])) {
        return false;
    }
    return in_array($permission, $_SESSION['permissions']);
}

// Enforce permission gate server-side
function require_permission($permission) {
    safe_session_start();
    require_login();
    if (!has_permission($permission)) {
        header('HTTP/1.1 403 Forbidden');
        echo "<h1>403 Forbidden</h1><p>You do not have the required permission (<code>" . h($permission) . "</code>) to access this page.</p>";
        exit;
    }
}

// Check for legacy admin role mappings compatibility
function require_admin_role() {
    safe_session_start();
    require_login();
    // Allow both admin and super_admin to pass legacy admin gates
    if (!isset($_SESSION['role_name']) || !in_array($_SESSION['role_name'], ['admin', 'super_admin'])) {
        header('HTTP/1.1 403 Forbidden');
        echo "<h1>403 Forbidden</h1><p>You do not have administrative privileges to access this area.</p>";
        exit;
    }
}

// Log admin audits trail securely
function log_audit($action, $module, $entity_type, $entity_id, $old_data = null, $new_data = null, $description = '') {
    global $db;
    if (!$db) return false;
    
    try {
        safe_session_start();
        $user_id = $_SESSION['user_id'] ?? null;
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $old_str = $old_data ? json_encode($old_data, JSON_UNESCAPED_UNICODE) : null;
        $new_str = $new_data ? json_encode($new_data, JSON_UNESCAPED_UNICODE) : null;
        
        $stmt = $db->prepare("
            INSERT INTO `audit_logs` (`user_id`, `action`, `module`, `entity_type`, `entity_id`, `old_data`, `new_data`, `description`, `ip_address`, `user_agent`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $action, $module, $entity_type, $entity_id, $old_str, $new_str, $description, $ip_address, $user_agent]);
    } catch (Exception $e) {
        error_log("[Audit Log Error] " . $e->getMessage());
        return false;
    }
}
