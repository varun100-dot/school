-- =============================================================
-- Migration: Update Leadership Team Order & Sharmin Habib Role
-- Pragya Jain (1st, Co-Founder & Director)
-- Deepak Jain (2nd, Co-Founder & Director)
-- Sharmin Habib (3rd, Head of Business and Operations, Academic Leadership)
-- =============================================================

UPDATE `leadership` SET 
  `name` = 'Pragya Jain',
  `slug` = 'pragya-jain',
  `designation` = 'Co-Founder & Director',
  `sort_order` = 1
WHERE `slug` = 'pragya-jain';

UPDATE `leadership` SET 
  `name` = 'Deepak Jain',
  `slug` = 'deepak-jain',
  `designation` = 'Co-Founder & Director',
  `sort_order` = 2
WHERE `slug` = 'deepak-jain';

UPDATE `leadership` SET 
  `name` = 'Sharmin Habib',
  `slug` = 'sharmin-habib',
  `designation` = 'Head of Business and Operations',
  `short_description` = 'Sharmin Habib is the Head of Business and Operations at Zuvio Global School with over 18 years of experience across online schooling, EdTech growth, operations, and scalable digital learning models.',
  `sort_order` = 3
WHERE `slug` = 'sharmin-habib';
