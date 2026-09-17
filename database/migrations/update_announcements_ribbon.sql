-- Zuvio Global School - Update Announcement Ribbon Text
-- Change session to 2026-27 and remove learning disabilities line from ribbon
UPDATE `announcements` 
SET `text` = 'Admissions ongoing for Mid-Session 2026–27' 
WHERE `id` = 1 OR `text` LIKE '%Learning Disabilities%' OR `text` LIKE '%2026–28%' OR `text` LIKE '%2026-28%';
