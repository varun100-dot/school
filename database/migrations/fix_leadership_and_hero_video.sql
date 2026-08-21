-- Zuvio Global School - Combined Migration
-- Run this on your production Hostinger MySQL database via phpMyAdmin
-- This migration is safe and non-destructive (uses IF NOT EXISTS / ADD COLUMN IF NOT EXISTS)

-- =============================================================
-- PART 1: Fix leadership table (add missing columns)
-- =============================================================

ALTER TABLE `leadership` ADD COLUMN IF NOT EXISTS `slug` VARCHAR(150) DEFAULT NULL;
ALTER TABLE `leadership` ADD COLUMN IF NOT EXISTS `short_description` TEXT DEFAULT NULL;

-- Add a unique index on slug if not already present (safe approach)
SET @exists = 0;
SELECT @exists := COUNT(1) FROM information_schema.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leadership' AND INDEX_NAME = 'slug';
SET @sql = IF(@exists = 0, 'ALTER TABLE `leadership` ADD UNIQUE INDEX `slug` (`slug`)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Backfill slug from name for existing rows that have no slug
UPDATE `leadership` SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(`name`, ' ', '-'), '&', ''), '.', '')) WHERE `slug` IS NULL OR `slug` = '';

-- =============================================================
-- PART 2: Fix hero_slides table (add video column for banner video support)
-- =============================================================

ALTER TABLE `hero_slides` ADD COLUMN IF NOT EXISTS `video` VARCHAR(255) DEFAULT NULL AFTER `image`;
ALTER TABLE `hero_slides` ADD COLUMN IF NOT EXISTS `media_type` ENUM('image', 'video') DEFAULT 'image' AFTER `video`;

-- =============================================================
-- PART 3: Seed the 4 leadership profiles (INSERT IGNORE = safe to re-run)
-- =============================================================

-- Fix existing placeholder rows first
UPDATE `leadership` SET 
  `name` = 'Pragya Jain',
  `slug` = 'pragya-jain',
  `designation` = 'Co-Founder & Director',
  `image` = '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
  `short_description` = 'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, confidence, and future-ready skills.',
  `sort_order` = 3
WHERE `id` = 1 AND `name` = 'Pragya Jain';

UPDATE `leadership` SET 
  `name` = 'Deepak Jain',
  `slug` = 'deepak-jain',
  `designation` = 'Co-Founder & Director',
  `image` = '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
  `short_description` = 'Deepak Jain is an entrepreneur and business professional who brings a strategic, systems-oriented perspective to Zuvio Global School. He oversees strategic direction, operations, and partnerships.',
  `sort_order` = 2
WHERE `id` = 2 AND `name` = 'Deepak Jain';

-- Insert missing profiles (Sharmin, Rashmi) - INSERT IGNORE skips if slug already exists
INSERT IGNORE INTO `leadership` (`name`, `slug`, `designation`, `image`, `short_description`, `bio`, `message`, `sort_order`, `is_active`) VALUES
('Sharmin Habib', 'sharmin-habib', 'Co-Founder & Director', '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp', 
 'Sharmin Habib is a seasoned educationist and edtech growth expert with over 18 years of experience. She has successfully founded and scaled preschools and digital K–8 learning models.',
 'Sharmin Habib is a seasoned education, business development, growth, and expansion professional with over 18 years of experience across early childhood education, online schooling, EdTech, and strategic business growth.',
 '', 1, 1),

('Rashmi Bhasin', 'rashmi-bhasin', 'Academic Head', '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp',
 'Rashmi Bhasin is the Academic Head of Zuvio Global School, a visionary curriculum thinker committed to designing personalised, child-centered online homeschooling experiences for Nursery to Grade 8.',
 'As an educationist, my role as Academic Head is to shape the academic vision, learning culture, and educational philosophy of an online homeschooling programme.',
 'My vision as Academic Head is to build a future-ready, child-centred, and globally relevant learning ecosystem.', 4, 1);

-- =============================================================
-- PART 4: About permissions (safe INSERT IGNORE)
-- =============================================================

INSERT IGNORE INTO `permissions` (`name`, `description`) VALUES
('about.view', 'View list of about us profiles'),
('about.create', 'Create new about us profile'),
('about.edit', 'Edit about us profile details'),
('about.delete', 'Delete about us profile'),
('about.publish', 'Publish/hide about us profiles');

INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name IN ('super_admin', 'admin') AND p.name LIKE 'about.%';
