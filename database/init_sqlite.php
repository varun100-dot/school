<?php
// Initialize local SQLite database for local offline development & testing
$db_file = dirname(__FILE__) . '/local.sqlite';
if (file_exists($db_file)) {
    @unlink($db_file);
}

$db = new PDO('sqlite:' . $db_file);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$schema = file_get_contents(dirname(__FILE__) . '/schema.sql');
$schema = preg_replace('/SET FOREIGN_KEY_CHECKS[^;]+;/i', '', $schema);
$schema = preg_replace('/INT AUTO_INCREMENT PRIMARY KEY/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $schema);
$schema = preg_replace('/TINYINT\([0-9]+\)/i', 'INTEGER', $schema);
$schema = preg_replace('/ENUM\([^)]+\)/i', 'TEXT', $schema);
$schema = preg_replace('/LONGTEXT/i', 'TEXT', $schema);
$schema = preg_replace('/ ON UPDATE CURRENT_TIMESTAMP/i', '', $schema);
$schema = preg_replace('/VARCHAR\([0-9]+\)/i', 'TEXT', $schema);
$schema = preg_replace('/DATETIME/i', 'TIMESTAMP', $schema);

$stmts = explode(';', $schema);
foreach ($stmts as $s) {
    $s = trim($s);
    if (!empty($s) && stripos($s, 'CREATE TABLE') !== false) {
        $db->exec($s);
    }
}

// Seed sql
$seed = file_get_contents(dirname(__FILE__) . '/seed.sql');
$seed = str_replace("\\'", "''", $seed);
$seed_stmts = explode(";\n", $seed);
foreach ($seed_stmts as $s) {
    $s = trim($s);
    if (!empty($s) && stripos($s, 'INSERT INTO') !== false) {
        try {
            $db->exec($s);
        } catch (Exception $e) {
            echo "Seed notice: " . $e->getMessage() . "\n";
        }
    }
}

// Create any optional extension tables (announcements, audit_logs)
$db->exec("
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `text` TEXT NOT NULL,
  `button_text` TEXT,
  `button_url` TEXT,
  `sort_order` INTEGER DEFAULT 0,
  `is_active` INTEGER DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER,
  `action` TEXT,
  `module` TEXT,
  `entity_type` TEXT,
  `entity_id` INTEGER,
  `old_data` TEXT,
  `new_data` TEXT,
  `description` TEXT,
  `ip_address` TEXT,
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` INTEGER NOT NULL,
  `permission_id` INTEGER NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`)
);
");

// Populate permissions
$perms = [
    'dashboard.view', 'blogs.view', 'blogs.create', 'blogs.edit', 'blogs.delete', 'blogs.publish',
    'hero.view', 'hero.create', 'hero.edit', 'hero.delete', 'hero.publish', 'hero.restore', 'hero.history',
    'media.view', 'media.upload', 'media.replace', 'media.restore', 'media.delete',
    'enquiries.view', 'enquiries.update', 'settings.view', 'settings.edit',
    'users.view', 'users.create', 'users.edit', 'users.delete', 'users.roles', 'audit.view'
];
foreach ($perms as $idx => $p) {
    $pid = $idx + 1;
    $db->exec("INSERT OR IGNORE INTO `permissions` (`id`, `name`) VALUES ($pid, '$p')");
    $db->exec("INSERT OR IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, $pid)");
}

// Ensure admin user has password for testing
$admin_pass = password_hash('admin123', PASSWORD_BCRYPT);
try {
    $stmt = $db->prepare("UPDATE `users` SET `password_hash` = ? WHERE `username` = 'zuvioadmin' OR `id` = 1");
    $stmt->execute([$admin_pass]);
    $stmt2 = $db->prepare("INSERT OR IGNORE INTO `users` (`id`, `username`, `email`, `password_hash`, `role_id`) VALUES (2, 'admin', 'admin@local.test', ?, 1)");
    $stmt2->execute([$admin_pass]);
    $db->exec("UPDATE `leadership` SET `designation` = 'Chief Operating Officer (COO)', `image` = '/assets/images/team/Sharmin Habib Chief Operating Officer.jpg' WHERE `slug` = 'sharmin-habib'");
} catch (Exception $e) {}

$blog_count = $db->query("SELECT COUNT(*) FROM blogs")->fetchColumn();
$cat_count = $db->query("SELECT COUNT(*) FROM blog_categories")->fetchColumn();
$user_count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();

echo "SUCCESS! Initialized local SQLite:\n";
echo "Blogs: $blog_count\n";
echo "Categories: $cat_count\n";
echo "Users: $user_count\n";
