-- Zuvio Global School - About Profiles Columns and Permissions Migration
-- Safe, non-destructive migration utilizing CREATE TABLE IF NOT EXISTS and ALTER TABLE ADD COLUMN IF NOT EXISTS

-- 1. Add Columns to leadership table if they do not exist
ALTER TABLE `leadership` ADD COLUMN IF NOT EXISTS `slug` VARCHAR(150) DEFAULT NULL UNIQUE;
ALTER TABLE `leadership` ADD COLUMN IF NOT EXISTS `short_description` TEXT DEFAULT NULL;

-- 2. Seed Content Profile Permissions
INSERT IGNORE INTO `permissions` (`name`, `description`) VALUES
('about.view', 'View list of about us profiles'),
('about.create', 'Create new about us profile'),
('about.edit', 'Edit about us profile details'),
('about.delete', 'Delete about us profile'),
('about.publish', 'Publish/hide about us profiles');

-- 3. Map Permissions to super_admin (role_id = 1 usually, but let's query dynamically to be robust)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name = 'super_admin' AND p.name LIKE 'about.%';

-- 4. Map Permissions to admin (which gets everything except user management & audits)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name = 'admin' AND p.name LIKE 'about.%';
