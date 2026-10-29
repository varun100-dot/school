-- ============================================================================
-- Migration: Update all saved school-wide grade range references from KG-8 to KG-12
-- Compatible with MySQL (Hostinger u869064717_school) and SQLite
-- ============================================================================

-- 1. Update page_seo table
UPDATE `page_seo` 
SET 
    `seo_title` = REPLACE(REPLACE(REPLACE(`seo_title`, 'Nursery to Grade 8', 'Nursery to Grade 12'), 'Kindergarten to Grade 8', 'Kindergarten to Grade 12'), 'KG to 8th', 'KG to 12th'),
    `meta_description` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`meta_description`, 'Nursery to Grade 8', 'Nursery to Grade 12'), 'Kindergarten to Grade 8', 'Kindergarten to Grade 12'), 'grades K to 8', 'grades K to 12'), 'K-8', 'K-12'), 'K–8', 'K–12'), 'Pre-Primary to Grade 8', 'Pre-Primary to Grade 12'),
    `secondary_keywords` = REPLACE(REPLACE(`secondary_keywords`, 'K-8', 'K-12'), 'k-8', 'k-12'),
    `og_description` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`og_description`, 'grades K to 8', 'grades K to 12'), 'K-8', 'K-12'), 'K–8', 'K–12'), 'Nursery to Grade 8', 'Nursery to Grade 12'), 'Pre-Primary to Grade 8', 'Pre-Primary to Grade 12'), 'by the end of Grade 8', 'by the end of Grade 12')
WHERE `seo_title` LIKE '%8%' OR `meta_description` LIKE '%8%' OR `secondary_keywords` LIKE '%8%' OR `og_description` LIKE '%8%';

-- 2. Update site_settings table (CMS JSON and string values)
UPDATE `site_settings`
SET `setting_value` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    `setting_value`,
    'Kindergarten to Grade 8th', 'Kindergarten to Grade 12'),
    'Kindergarten to Grade 8', 'Kindergarten to Grade 12'),
    'KG to 8th', 'KG to 12th'),
    'KG to Grade 8', 'KG to Grade 12'),
    'Nursery to Grade 8th', 'KG to 12th'),
    'Nursery to Grade 8', 'Nursery to Grade 12'),
    'NURSERY – GRADE 8', 'NURSERY – GRADE 12'),
    'NURSERY - GRADE 8', 'NURSERY - GRADE 12'),
    'grades K to 8.', 'grades K to 12.'),
    'Grades K to 8.', 'Grades K to 12.'),
    'grades K to 8', 'grades K to 12'),
    'Grades K to 8', 'Grades K to 12'),
    'by the end of Grade 8', 'by the end of Grade 12'),
    'Pre-Primary to Grade 8', 'Pre-Primary to Grade 12')
WHERE `setting_value` LIKE '%Grade 8%' OR `setting_value` LIKE '%KG to 8%' OR `setting_value` LIKE '%K to 8%' OR `setting_value` LIKE '%GRADE 8%';
