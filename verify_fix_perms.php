<?php
// verify_fix_perms.php - Production-safe permission seeder
// Uses INSERT IGNORE only - no DROP, TRUNCATE or DELETE
header('Content-Type: text/plain');
require_once dirname(__FILE__) . '/includes/db.php';

if (!$db) {
    echo "ERROR: Database connection offline\n";
    exit;
}

$log = [];

try {
    // 1. Ensure all 3 roles exist
    $db->exec("INSERT IGNORE INTO `roles` (`name`, `description`) VALUES
        ('super_admin', 'Full platform super administration access'),
        ('admin', 'General platform administration access'),
        ('editor', 'Content creation and editing access')");
    $log[] = "STEP 1: Roles ensured";

    // 2. Seed all permissions (INSERT IGNORE = skip if already exists)
    $db->exec("INSERT IGNORE INTO `permissions` (`name`, `description`) VALUES
        ('dashboard.view', 'View admin dashboard statistics'),
        ('blogs.view', 'View list of blogs'),
        ('blogs.create', 'Create new blog post'),
        ('blogs.edit', 'Edit blog post details'),
        ('blogs.delete', 'Delete blog post'),
        ('blogs.publish', 'Publish/Draft blog posts'),
        ('hero.view', 'View homepage hero slides'),
        ('hero.create', 'Add new hero slides'),
        ('hero.edit', 'Edit hero slide contents'),
        ('hero.delete', 'Delete hero slides'),
        ('hero.publish', 'Enable/Disable hero slides'),
        ('hero.restore', 'Restore old slide versions'),
        ('hero.history', 'View hero version history'),
        ('media.view', 'View media assets library'),
        ('media.upload', 'Upload new media assets'),
        ('media.replace', 'Overwrite existing image paths atomically'),
        ('media.restore', 'Restore previous media asset versions'),
        ('media.delete', 'Delete media assets'),
        ('enquiries.view', 'View student/parent enquiries list'),
        ('enquiries.update', 'Update enquiry pipeline statuses'),
        ('settings.view', 'View system settings configurations'),
        ('settings.edit', 'Edit site contact info and configurations'),
        ('users.view', 'View users list and privileges'),
        ('users.create', 'Add new user accounts'),
        ('users.edit', 'Edit user accounts information'),
        ('users.delete', 'Delete user accounts'),
        ('users.roles', 'Change user roles and privileges'),
        ('audit.view', 'View system audit logs trail')");
    $log[] = "STEP 2: Permissions seeded";

    // 3. Super Admin: all permissions
    $db->exec("INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
        SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p WHERE r.name = 'super_admin'");
    $log[] = "STEP 3: super_admin permissions mapped";

    // 4. Admin: all permissions including dashboard.view (exclude users.% and audit.%)
    $db->exec("INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
        SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p
        WHERE r.name = 'admin' AND p.name NOT LIKE 'users.%' AND p.name NOT LIKE 'audit.%'");
    $log[] = "STEP 4: admin permissions mapped";

    // 5. Editor: limited permissions
    $db->exec("INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
        SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p
        WHERE r.name = 'editor' AND p.name IN (
          'dashboard.view','blogs.view','blogs.create','blogs.edit','blogs.publish','media.view','media.upload'
        )");
    $log[] = "STEP 5: editor permissions mapped";

    // 6. Verify: check dashboard.view is now mapped for admin
    $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM `role_permissions` rp
        JOIN `roles` r ON r.id = rp.role_id
        JOIN `permissions` p ON p.id = rp.permission_id
        WHERE r.name = 'admin' AND p.name = 'dashboard.view'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $log[] = "VERIFY: admin has dashboard.view = " . ($row['cnt'] > 0 ? 'YES' : 'NO');

    // 7. Count all permissions for admin
    $stmt2 = $db->prepare("SELECT COUNT(*) as total FROM `role_permissions` rp
        JOIN `roles` r ON r.id = rp.role_id WHERE r.name = 'admin'");
    $stmt2->execute();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $log[] = "VERIFY: admin total permissions = " . $row2['total'];

    foreach ($log as $line) {
        echo $line . "\n";
    }
    echo "DONE: All steps completed successfully\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    foreach ($log as $line) {
        echo $line . "\n";
    }
}
exit;
