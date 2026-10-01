-- Zuvio Global School - Phase 6 Full Admin & CMS Production Integration
-- Safe, idempotent SQL script with foreign key checks toggled

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure site_settings can store rich JSON documents
ALTER TABLE `site_settings` MODIFY COLUMN `setting_value` LONGTEXT;

-- 2. Upsert all 26 website pages in `pages` based on unique `slug`
INSERT INTO `pages` (`name`, `slug`, `is_active`) VALUES
('Home', 'home', 1),
('About Zuvio', 'about-zuvio', 1),
('About Us (Overview)', 'about', 1),
('Our Team & Leadership', 'our-team', 1),
('Founder’s Message', 'founder-message', 1),
('Affiliations & Accreditations', 'affiliations-accreditations', 1),
('Curriculum Framework', 'curriculum', 1),
('Academics Overview', 'academics', 1),
('Technology & AI Labs', 'technology', 1),
('Special Education', 'special-education', 1),
('Electives & Languages', 'electives', 1),
('NEP 2020 Guidelines', 'nep-2020', 1),
('Academic Resources', 'resources', 1),
('Admissions Overview', 'admissions', 1),
('Enrol Now (5 Steps)', 'enrol-now', 1),
('Eligibility Matrix', 'eligibility', 1),
('Academic Calendar', 'calendar', 1),
('Fee Structure', 'fees', 1),
('Parent FAQs', 'faq', 1),
('Beyond Overview', 'beyond', 1),
('Co-curricular & Clubs', 'co-curricular', 1),
('Student Achievers', 'student-achievers', 1),
('Photo Gallery', 'gallery', 1),
('Virtual Classroom', 'virtual-classroom', 1),
('Blogs & Insights', 'blogs', 1),
('Contact Us', 'contact', 1)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `is_active` = VALUES(`is_active`);

-- 3. Ensure comprehensive page_seo entries exist for every page (joining dynamically on slug to guarantee valid page_id foreign keys)
INSERT INTO `page_seo` (`page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`)
SELECT
  p.id AS page_id,
  d.primary_keyword,
  d.secondary_keywords,
  d.search_intent,
  d.seo_title,
  d.meta_description,
  d.canonical_url,
  d.index_status,
  d.og_title,
  d.og_description,
  d.og_image
FROM (
  SELECT 'home' AS slug, 'Zuvio Global School' AS primary_keyword, 'online school, K-8 homeschooling, live online classes' AS secondary_keywords, 'brand search' AS search_intent, 'Zuvio Global School | Learning Beyond Boundaries' AS seo_title, 'A future-ready online school combining CBSE curriculum with personalized pathways, Oxford thematic learning, and AI fluency for grades K to 8.' AS meta_description, 'https://zuvioglobalschool.com/' AS canonical_url, 'index, follow' AS index_status, 'Zuvio Global School' AS og_title, 'Learning Beyond Boundaries' AS og_description, '/assets/images/logo.png' AS og_image UNION ALL
  SELECT 'about-zuvio', 'About Zuvio', 'online school story, education philosophy, mission vision', 'informational', 'About Zuvio — Vision, Mission & Philosophy | Zuvio Global School', 'Discover why Zuvio Global School was founded: an adaptive online school designed around the child, blending CBSE alignment with global quality.', 'https://zuvioglobalschool.com/about-zuvio', 'index, follow', 'About Zuvio — Zuvio Global School', 'Learning Beyond Boundaries', '/assets/images/about_us_hero.jpg' UNION ALL
  SELECT 'about', 'About Us', 'Zuvio overview, school values, approach', 'informational', 'About Us | Zuvio Global School', 'Explore Zuvio Global School: our origin story, core values, Z-U-V-I-O approach, and transformative leadership in online schooling.', 'https://zuvioglobalschool.com/about', 'index, follow', 'About Us | Zuvio Global School', 'Child-centric online schooling.', '/assets/images/about_us_hero.jpg' UNION ALL
  SELECT 'our-team', 'Our Team', 'Zuvio leadership, Pragya Jain, Deepak Jain, Sharmin Habib', 'informational', 'Our Team & Leadership | Zuvio Global School', 'Meet the experienced founders, academic leadership, and advisory board guiding Zuvio Global School’s pedagogical excellence.', 'https://zuvioglobalschool.com/our-team', 'index, follow', 'Leadership Team — Zuvio Global School', 'Experienced educators and visionary founders.', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp' UNION ALL
  SELECT 'founder-message', 'Founder Message', 'Pragya Jain letter, founder vision', 'informational', 'Founder’s Message — Pragya Jain | Zuvio Global School', 'Read the personal message from Co-Founder Pragya Jain on why education must adapt to the child, not the child to the system.', 'https://zuvioglobalschool.com/founder-message', 'index, follow', 'Founder’s Message | Zuvio Global School', 'A personal note from our Founder.', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp' UNION ALL
  SELECT 'affiliations-accreditations', 'Accreditations', 'IAO accredited, ISSO member, Oxford Quality', 'verification', 'Affiliations & Accreditations | Zuvio Global School', 'Verify our official accreditations: IAO (IA 4883), ISSO sports membership, and Oxford Quality education partnership.', 'https://zuvioglobalschool.com/affiliations-accreditations', 'index, follow', 'Affiliations & Accreditations — Zuvio Global School', 'IAO Accredited, ISSO Member, Oxford Quality Partner.', '/assets/images/iao-logo.png' UNION ALL
  SELECT 'curriculum', 'Curriculum Framework', 'CBSE curriculum online, Oxford thematic learning, K-8 subjects', 'educational research', 'Curriculum Framework — K to Grade 8 | Zuvio Global School', 'Detailed curriculum pathways mapped to CBSE, NEP 2020, and NCF across Early Years, Foundation, Preparatory, and Middle School stages.', 'https://zuvioglobalschool.com/curriculum', 'index, follow', 'Curriculum Framework | Zuvio Global School', 'CBSE, NEP 2020 & Oxford Thematic Learning.', '/assets/images/Students learning in classroom.png' UNION ALL
  SELECT 'academics', 'Academics Overview', 'online schooling methodology, assessment model', 'informational', 'Academics Overview | Zuvio Global School', 'Explore our comprehensive academic framework: interdisciplinary learning, continuous growth assessment, and digital literacy.', 'https://zuvioglobalschool.com/academics', 'index, follow', 'Academics | Zuvio Global School', 'Future-ready academic pathways.', '/assets/images/Students learning in classroom.png' UNION ALL
  SELECT 'technology', 'Technology AI Labs', 'IBM AI certified school, coding for kids, virtual LMS', 'feature search', 'Technology & AI Labs | Zuvio Global School', 'Interactive STEM, age-appropriate AI literacy, IBM-supported curriculum, and modern US-based LMS infrastructure.', 'https://zuvioglobalschool.com/technology', 'index, follow', 'Technology & AI Labs | Zuvio Global School', 'Empowering digital fluency from Grade 1.', '/assets/images/Hero image 2.png' UNION ALL
  SELECT 'special-education', 'Special Education', 'inclusive online school, SEN educators, neurodivergent support', 'informational', 'Special Education & Inclusion | Zuvio Global School', 'Dedicated Special Educators and customized Individualized Education Plans (IEP) ensuring every learner flourishes.', 'https://zuvioglobalschool.com/special-education', 'index, follow', 'Special Education | Zuvio Global School', 'Inclusive by design for diverse minds.', '/assets/images/about_us_hero.jpg' UNION ALL
  SELECT 'electives', 'Electives Languages', 'foreign languages, coding, creative arts electives', 'exploratory', 'Electives & Foreign Languages | Zuvio Global School', 'Enrichment electives including French, Spanish, Sanskrit, Coding, Financial Literacy, and Speech & Drama.', 'https://zuvioglobalschool.com/electives', 'index, follow', 'Electives & Languages | Zuvio Global School', 'Beyond the textbook enrichment.', '/assets/images/Hero image 1.png' UNION ALL
  SELECT 'nep-2020', 'NEP 2020', 'NEP 2020 compliance online school, 5+3+3+4 framework', 'compliance', 'NEP 2020 Pedagogical Alignment | Zuvio Global School', 'How Zuvio implements the National Education Policy 2020 with experiential learning, formative assessment, and multi-disciplinary pathways.', 'https://zuvioglobalschool.com/nep-2020', 'index, follow', 'NEP 2020 Guidelines | Zuvio Global School', 'Aligned with India’s National Education Policy.', '/assets/images/Hero image 2.png' UNION ALL
  SELECT 'resources', 'Academic Resources', 'school worksheets, digital textbooks, parent resource kit', 'resource download', 'Academic Resources & Downloads | Zuvio Global School', 'Access curated digital library resources, Oxford reading supplements, coding consoles, and diagnostic sample papers.', 'https://zuvioglobalschool.com/resources', 'index, follow', 'Academic Resources | Zuvio Global School', 'Curated learning resources and toolkits.', '/assets/images/Students learning in classroom.png' UNION ALL
  SELECT 'admissions', 'Admissions', 'online school admission, enrol child K-8', 'transactional', 'Admissions & Onboarding | Zuvio Global School', 'Begin your child’s onboarding journey. Explore fee structures, eligibility criteria, academic calendar, and enrollment steps.', 'https://zuvioglobalschool.com/admissions', 'index, follow', 'Admissions | Zuvio Global School', 'Admissions Open for Academic Year 2026–2027.', '/assets/images/logo.png' UNION ALL
  SELECT 'enrol-now', 'Enrol Now', 'school enrollment steps, register online school', 'transactional', 'Enrol Now — 5 Simple Steps | Zuvio Global School', 'Our simple 5-step admissions process: counselor discussion, application, diagnostic interaction, enrollment, and orientation.', 'https://zuvioglobalschool.com/admissions/enrol-now', 'index, follow', 'Enrol Now | Zuvio Global School', 'Seamless admissions in 5 simple steps.', '/assets/images/logo.png' UNION ALL
  SELECT 'eligibility', 'Eligibility Criteria', 'online school age criteria, nursery to grade 8', 'informational', 'Eligibility Criteria & Age Guidelines | Zuvio Global School', 'Age benchmarks, previous academic documentation requirements, and prerequisite tech setup for enrollment.', 'https://zuvioglobalschool.com/admissions/eligibility', 'index, follow', 'Eligibility Criteria | Zuvio Global School', 'Clear age and admission benchmarks.', '/assets/images/logo.png' UNION ALL
  SELECT 'calendar', 'Academic Calendar', 'school terms, exam dates, vacation calendar', 'informational', 'Academic Calendar 2026–2027 | Zuvio Global School', 'Term schedules, examination windows, major holidays, and international student scheduling details.', 'https://zuvioglobalschool.com/admissions/calendar', 'index, follow', 'Academic Calendar | Zuvio Global School', 'Term dates and academic schedule.', '/assets/images/logo.png' UNION ALL
  SELECT 'fees', 'Fee Structure', 'online school fees, tuition cost K-8', 'transactional', 'Fee Structure & Transparent Pricing | Zuvio Global School', 'Transparent, all-inclusive annual and term fee breakdowns with sibling discounts and scholarship details.', 'https://zuvioglobalschool.com/admissions/fees', 'index, follow', 'Fee Structure | Zuvio Global School', 'Transparent and competitive tuition fees.', '/assets/images/logo.png' UNION ALL
  SELECT 'faq', 'Parent FAQs', 'frequently asked questions online schooling, 18 questions', 'informational', 'Parent FAQs — Online Schooling Answers | Zuvio Global School', 'Comprehensive answers to all 18 top parent questions: timings, screen time, CBSE alignment, offline transition, and teacher access.', 'https://zuvioglobalschool.com/faq', 'index, follow', 'Parent FAQs | Zuvio Global School', 'Clear answers to all parent questions.', '/assets/images/logo.png' UNION ALL
  SELECT 'beyond', 'Beyond Overview', 'extracurricular programs, holistic development', 'informational', 'Zuvio Beyond — Holistic Development Programmes', 'Explore our signature non-academic programmes: AI Explorers, Young Authors, Global Citizenship, and Entrepreneurship.', 'https://zuvioglobalschool.com/beyond', 'index, follow', 'Zuvio Beyond Programmes', 'Preparing the complete graduate.', '/assets/images/about_us_hero.jpg' UNION ALL
  SELECT 'co-curricular', 'Co-curricular Clubs', 'school clubs, debate club, coding club, art', 'activity search', 'Co-Curricular & Student Clubs | Zuvio Global School', 'Interactive Friday student clubs spanning STEM, Creative Writing, Public Speaking, Chess, and Environmental Stewardship.', 'https://zuvioglobalschool.com/beyond/co-curricular', 'index, follow', 'Co-Curricular & Clubs | Zuvio Global School', 'Discover passions through active student clubs.', '/assets/images/Hero image 1.png' UNION ALL
  SELECT 'student-achievers', 'Student Achievers', 'student awards, olympiad winners, achievers', 'social proof', 'Student Achievers & Hall of Fame | Zuvio Global School', 'Celebrating distinguished student accomplishments across international competitions, Olympiads, and creative arts.', 'https://zuvioglobalschool.com/beyond/student-achievers', 'index, follow', 'Student Achievers | Zuvio Global School', 'Our students shine on global stages.', '/assets/images/Students learning in classroom.png' UNION ALL
  SELECT 'gallery', 'Photo Gallery', 'school photos, virtual classroom photos, student projects', 'visual proof', 'Campus & Life Gallery | Zuvio Global School', 'Visual glimpse into life at Zuvio: interactive classes, student project showcases, and community celebrations.', 'https://zuvioglobalschool.com/beyond/gallery', 'index, follow', 'Gallery | Zuvio Global School', 'Life and learning at Zuvio.', '/assets/images/Students learning in classroom.png' UNION ALL
  SELECT 'virtual-classroom', 'Virtual Classroom', 'inside online classroom, LMS preview', 'product preview', 'Inside the Virtual Classroom | Zuvio Global School', 'A guided walkthrough of our live interactive classroom environment, secure digital tools, and active student participation.', 'https://zuvioglobalschool.com/beyond/virtual-classroom', 'index, follow', 'Inside the Virtual Classroom | Zuvio Global School', 'Safe, engaging, and interactive live learning.', '/assets/images/Students learning in classroom.png' UNION ALL
  SELECT 'blogs', 'Blogs Insights', 'education articles, parenting tips, schooling trends', 'content exploration', 'Blogs & Insights | Zuvio Global School', 'Expert perspectives, school announcements, pedagogical research, and parenting guides for modern online education.', 'https://zuvioglobalschool.com/blogs', 'index, follow', 'Blogs & Insights | Zuvio Global School', 'Perspectives for modern parents and learners.', '/assets/images/about_us_hero.jpg' UNION ALL
  SELECT 'contact', 'Contact Us', 'school address, phone, admissions office Netaji Subhash Place', 'transactional', 'Contact Us & Campus Enquiries | Zuvio Global School', 'Connect with our admissions office in Netaji Subhash Place, Delhi. Phone: +91 7827262956 | info@zuvioglobalschool.com', 'https://zuvioglobalschool.com/contact', 'index, follow', 'Contact Us | Zuvio Global School', 'We are here to support your child.', '/assets/images/logo.png'
) AS d
JOIN `pages` p ON p.slug = d.slug
ON DUPLICATE KEY UPDATE
  `primary_keyword` = VALUES(`primary_keyword`),
  `secondary_keywords` = VALUES(`secondary_keywords`),
  `search_intent` = VALUES(`search_intent`),
  `seo_title` = VALUES(`seo_title`),
  `meta_description` = VALUES(`meta_description`),
  `canonical_url` = VALUES(`canonical_url`),
  `index_status` = VALUES(`index_status`),
  `og_title` = VALUES(`og_title`),
  `og_description` = VALUES(`og_description`),
  `og_image` = VALUES(`og_image`);

-- 4. Ensure Blog Categories table exists with standard categories
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO `blog_categories` (`name`, `slug`) VALUES
('Pedagogy & Curriculum', 'pedagogy-curriculum'),
('Technology & AI', 'technology-ai'),
('Parenting & Life Skills', 'parenting-life-skills'),
('School News & Announcements', 'school-news'),
('Awards & Recognition', 'awards-recognition');

-- 5. Ensure navigation_items table exists with complete hierarchy
CREATE TABLE IF NOT EXISTS `navigation_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `label` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`parent_id`),
  INDEX (`sort_order`)
);
-- 6. Ensure all global site_settings keys exist with appropriate defaults
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `description`) VALUES
('site_name', 'Zuvio Global School', 'Institution name shown in browser tabs and titles'),
('site_tagline', 'Learning Beyond Boundaries', 'Primary school motto and tagline'),
('logo_url', '/assets/images/logo.png', 'Global header and footer logo image URL'),
('favicon_url', '/assets/images/logo.png', 'Browser tab favicon icon URL'),
('phone', '7827262956', 'Main contact phone number'),
('general_email', 'info@zuvioglobalschool.com', 'General public contact email'),
('admissions_email', 'info@zuvioglobalschool.com', 'Dedicated admissions department email'),
('address', 'B-09, Lower Ground Floor, ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi - 110034', 'Full registered school campus and office address'),
('office_timings', 'Monday - Saturday: 10:00 AM - 7:00 PM IST', 'Working hours and counselor availability'),
('google_maps_link', 'https://maps.google.com/?q=ITL+Twin+Tower+Netaji+Subhash+Place+Delhi', 'Official Google Maps location link'),
('google_analytics_id', '', 'Google Analytics 4 (GA4) Measurement ID (e.g. G-XXXXXXXXXX)'),
('google_analytics_enabled', '0', 'Toggle Google Analytics tracking script (1=enabled, 0=disabled)'),
('facebook_pixel_id', '', 'Meta Facebook Pixel ID (e.g. 123456789012345)'),
('facebook_pixel_enabled', '0', 'Toggle Facebook Pixel tracking script (1=enabled, 0=disabled)'),
('whatsapp', '7827262956', 'WhatsApp phone number with country code'),
('whatsapp_enabled', '1', 'Toggle floating WhatsApp chat button (1=enabled, 0=disabled)'),
('whatsapp_message', 'Hello Zuvio Global School, I would like to enquire about admissions.', 'Pre-filled WhatsApp chat message'),
('maintenance_mode', '0', 'Emergency / Scheduled Maintenance mode switch (1=active, 0=inactive)'),
('maintenance_title', 'We’re Upgrading Our Learning Experience', 'Heading displayed on the maintenance screen'),
('maintenance_message', 'Zuvio Global School website is currently undergoing scheduled platform upgrades. We will be back online shortly. For admissions assistance or immediate inquiries, our counseling desk is available via phone and WhatsApp.', 'Message displayed on the maintenance screen'),
('copyright', '© 2026 Zuvio Global School. All rights reserved.', 'Standard footer copyright disclaimer'),
('social_instagram', 'https://www.instagram.com/thezuvio/', 'Instagram profile URL'),
('social_facebook', 'https://www.facebook.com/share/1XsYWDm3rt/', 'Facebook official page URL'),
('social_linkedin', 'https://www.linkedin.com/company/zuvio-global-school/', 'LinkedIn company page URL'),
('social_youtube', 'https://www.youtube.com/@zuvioglobalschool', 'YouTube official channel URL')
ON DUPLICATE KEY UPDATE
  `description` = VALUES(`description`);

SET FOREIGN_KEY_CHECKS = 1;
