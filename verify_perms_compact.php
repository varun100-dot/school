<?php
header('Content-Type: application/json');
require_once dirname(__FILE__) . '/includes/db.php';

if (!$db) {
    echo json_encode(['error' => 'Database connection offline']);
    exit;
}

try {
    $users = $db->query("SELECT u.id, u.username, u.role_id, r.name as role_name FROM `users` u LEFT JOIN `roles` r ON r.id = u.role_id")->fetchAll(PDO::FETCH_ASSOC);
    $roles = $db->query("SELECT id, name FROM `roles`")->fetchAll(PDO::FETCH_ASSOC);
    $counts = $db->query("SELECT rp.role_id, r.name as role_name, COUNT(*) as perm_count FROM `role_permissions` rp JOIN `roles` r ON r.id = rp.role_id GROUP BY rp.role_id")->fetchAll(PDO::FETCH_ASSOC);
    
    $perms = [];
    if (count($users) > 0) {
        $first_user_role = $users[0]['role_id'];
        $stmt = $db->prepare("SELECT p.name FROM `role_permissions` rp JOIN `permissions` p ON p.id = rp.permission_id WHERE rp.role_id = ?");
        $stmt->execute([$first_user_role]);
        $perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    echo json_encode([
        'u' => $users,
        'r' => $roles,
        'c' => $counts,
        'p_count' => count($perms),
        'has_dashboard_view' => in_array('dashboard.view', $perms)
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
