-- Zuvio Global School - Phase 4 Announcements Migration
-- Safe, idempotent SQL script using CREATE TABLE IF NOT EXISTS

-- 1. Create Announcements Table
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `text` TEXT NOT NULL,
  `button_text` VARCHAR(100),
  `button_url` VARCHAR(255),
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Seed Initial Announcements
INSERT IGNORE INTO `announcements` (`id`, `text`, `button_text`, `button_url`, `sort_order`, `is_active`) VALUES
(1, 'Admissions ongoing for Mid-Session 2026–27 | Admissions open for Children with Learning Disabilities.', 'Apply Now', '/contact', 1, 1);

-- 3. Seed Permissions for Announcements
INSERT IGNORE INTO `permissions` (`name`, `description`) VALUES
('announcements.view', 'View list of announcements'),
('announcements.create', 'Create new announcements'),
('announcements.edit', 'Edit announcement details'),
('announcements.delete', 'Delete announcements');

-- 4. Map Role Permissions for Announcements
-- Super Admin & Admin: All Permissions for announcements
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name = 'super_admin' AND p.name LIKE 'announcements.%';

INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name = 'admin' AND p.name LIKE 'announcements.%';
