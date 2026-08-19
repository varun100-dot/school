<?php
header('Content-Type: text/plain');
require_once dirname(__FILE__) . '/includes/db.php';

if (!$db) {
    echo "Database connection offline\n";
    exit;
}

try {
    $users = $db->query("SELECT id, username, role_id FROM `users`")->fetchAll(PDO::FETCH_ASSOC);
    echo "Users Count: " . count($users) . "\n";
    foreach ($users as $u) {
        echo "- User ID " . $u['id'] . ": " . $u['username'] . " (Role ID: " . $u['role_id'] . ")\n";
    }
    
    $roles = $db->query("SELECT id, name FROM `roles`")->fetchAll(PDO::FETCH_ASSOC);
    echo "Roles Count: " . count($roles) . "\n";
    foreach ($roles as $r) {
        echo "- Role ID " . $r['id'] . ": " . $r['name'] . "\n";
    }
    
    $counts = $db->query("SELECT rp.role_id, COUNT(*) as perm_count FROM `role_permissions` rp GROUP BY rp.role_id")->fetchAll(PDO::FETCH_ASSOC);
    echo "Role Permissions Counts:\n";
    foreach ($counts as $c) {
        echo "- Role ID " . $c['role_id'] . ": " . $c['perm_count'] . " permissions\n";
    }
    
    // Check permissions for role ID 1
    $stmt = $db->prepare("SELECT p.name FROM `role_permissions` rp JOIN `permissions` p ON p.id = rp.permission_id WHERE rp.role_id = ?");
    $stmt->execute([1]);
    $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Role 1 permissions: " . implode(', ', $perms) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
exit;
