-- Zuvio Global School - Phase 3 Database Migration
-- Safe, idempotent SQL script using CREATE TABLE IF NOT EXISTS

-- 1. Create Permissions Table
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Create Role Permissions Link Table
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
);

-- 3. Create Hero Slides Version Snapshot Table
CREATE TABLE IF NOT EXISTS `hero_slide_versions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hero_slide_id` INT NOT NULL,
  `version_number` INT NOT NULL,
  `title` VARCHAR(255),
  `subtitle` VARCHAR(255),
  `description` TEXT,
  `image` VARCHAR(255),
  `primary_cta_text` VARCHAR(100),
  `primary_cta_url` VARCHAR(255),
  `secondary_cta_text` VARCHAR(100),
  `secondary_cta_url` VARCHAR(255),
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `change_summary` VARCHAR(255),
  FOREIGN KEY (`hero_slide_id`) REFERENCES `hero_slides` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
);

-- 4. Create Media Asset Version Snapshot Table
CREATE TABLE IF NOT EXISTS `media_versions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `media_id` INT NOT NULL,
  `version_number` INT NOT NULL,
  `original_path` VARCHAR(255) NOT NULL,
  `backup_path` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(100),
  `file_size` INT,
  `width` INT,
  `height` INT,
  `replaced_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`replaced_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
);

-- 5. Create System Audit Log Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT,
  `action` VARCHAR(100) NOT NULL,
  `module` VARCHAR(100) NOT NULL,
  `entity_type` VARCHAR(100),
  `entity_id` INT,
  `old_data` LONGTEXT,
  `new_data` LONGTEXT,
  `description` TEXT,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
);

-- 6. Insert Roles (if not present)
INSERT IGNORE INTO `roles` (`name`, `description`) VALUES 
('super_admin', 'Full platform super administration access'),
('admin', 'General platform administration access'),
('editor', 'Content creation and editing access');

-- 7. Seed Permissions
INSERT IGNORE INTO `permissions` (`name`, `description`) VALUES
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
('audit.view', 'View system audit logs trail');

-- 8. Map Role Permissions
-- Super Admin: All Permissions
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p WHERE r.name = 'super_admin';

-- Admin: Everything except User Management & Audit logs
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name = 'admin' AND p.name NOT LIKE 'users.%' AND p.name NOT LIKE 'audit.%';

-- Editor: View logs, blogs view/create/edit/publish, media view/upload
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name = 'editor' AND p.name IN (
  'dashboard.view', 'blogs.view', 'blogs.create', 'blogs.edit', 'blogs.publish', 'media.view', 'media.upload'
);

-- 9. Seed Core Image Assets into Media Table
INSERT IGNORE INTO `media` (`file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `file_size`, `width`, `height`) VALUES
('logo.png', 'assets/images/logo.png', '/assets/images/logo.png', 'Zuvio Global School logo', 'image/png', 20480, 240, 60),
('Hero image 1.png', 'assets/images/Hero image 1.png', '/assets/images/Hero image 1.png', 'Homepage Slide Banner 1', 'image/png', 1048576, 1920, 800),
('Hero image 2.png', 'assets/images/Hero image 2.png', '/assets/images/Hero image 2.png', 'Homepage Slide Banner 2', 'image/png', 1048576, 1920, 800),
('Students learning in classroom.png', 'assets/images/Students learning in classroom.png', '/assets/images/Students learning in classroom.png', 'Students in classroom', 'image/png', 800000, 800, 600),
('Teacher interacting with students.png', 'assets/images/Teacher interacting with students.png', '/assets/images/Teacher interacting with students.png', 'Teacher with students', 'image/png', 800000, 800, 600);
