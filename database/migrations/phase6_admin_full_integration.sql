-- Zuvio Global School - Phase 6 Full Admin & CMS Production Integration
-- Safe, idempotent SQL script for MySQL

-- 1. Ensure site_settings can store rich JSON documents
ALTER TABLE `site_settings` MODIFY COLUMN `setting_value` LONGTEXT;

-- 2. Ensure all 25+ frontend pages exist in `pages`
INSERT IGNORE INTO `pages` (`id`, `name`, `slug`, `is_active`) VALUES
(1, 'Home', 'home', 1),
(2, 'About Zuvio', 'about-zuvio', 1),
(3, 'About Us (Overview)', 'about', 1),
(4, 'Our Team & Leadership', 'our-team', 1),
(5, 'Founder’s Message', 'founder-message', 1),
(6, 'Affiliations & Accreditations', 'affiliations-accreditations', 1),
(7, 'Curriculum Framework', 'curriculum', 1),
(8, 'Academics Overview', 'academics', 1),
(9, 'Technology & AI Labs', 'technology', 1),
(10, 'Special Education', 'special-education', 1),
(11, 'Electives & Languages', 'electives', 1),
(12, 'NEP 2020 Guidelines', 'nep-2020', 1),
(13, 'Academic Resources', 'resources', 1),
(14, 'Admissions Overview', 'admissions', 1),
(15, 'Enrol Now (5 Steps)', 'enrol-now', 1),
(16, 'Eligibility Matrix', 'eligibility', 1),
(17, 'Academic Calendar', 'calendar', 1),
(18, 'Fee Structure', 'fees', 1),
(19, 'Parent FAQs', 'faq', 1),
(20, 'Beyond Overview', 'beyond', 1),
(21, 'Co-curricular & Clubs', 'co-curricular', 1),
(22, 'Student Achievers', 'student-achievers', 1),
(23, 'Photo Gallery', 'gallery', 1),
(24, 'Virtual Classroom', 'virtual-classroom', 1),
(25, 'Blogs & Insights', 'blogs', 1),
(26, 'Contact Us', 'contact', 1);

-- 3. Ensure comprehensive page_seo entries exist for every page
INSERT INTO `page_seo` (`page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`)
VALUES
(1, 'Zuvio Global School', 'online school, K-8 homeschooling, live online classes', 'brand search', 'Zuvio Global School | Learning Beyond Boundaries', 'A future-ready online school combining CBSE curriculum with personalized pathways, Oxford thematic learning, and AI fluency for grades K to 8.', 'https://zuvioglobalschool.com/', 'index, follow', 'Zuvio Global School', 'Learning Beyond Boundaries', '/assets/images/logo.png'),
(2, 'About Zuvio', 'online school story, education philosophy, mission vision', 'informational', 'About Zuvio — Vision, Mission & Philosophy | Zuvio Global School', 'Discover why Zuvio Global School was founded: an adaptive online school designed around the child, blending CBSE alignment with global quality.', 'https://zuvioglobalschool.com/about-zuvio', 'index, follow', 'About Zuvio — Zuvio Global School', 'Learning Beyond Boundaries', '/assets/images/about_us_hero.jpg'),
(3, 'About Us', 'Zuvio overview, school values, approach', 'informational', 'About Us | Zuvio Global School', 'Explore Zuvio Global School: our origin story, core values, Z-U-V-I-O approach, and transformative leadership in online schooling.', 'https://zuvioglobalschool.com/about', 'index, follow', 'About Us | Zuvio Global School', 'Child-centric online schooling.', '/assets/images/about_us_hero.jpg'),
(4, 'Our Team', 'Zuvio leadership, Pragya Jain, Deepak Jain, Sharmin Habib', 'informational', 'Our Team & Leadership | Zuvio Global School', 'Meet the experienced founders, academic leadership, and advisory board guiding Zuvio Global School’s pedagogical excellence.', 'https://zuvioglobalschool.com/our-team', 'index, follow', 'Leadership Team — Zuvio Global School', 'Experienced educators and visionary founders.', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'),
(5, 'Founder Message', 'Pragya Jain letter, founder vision', 'informational', 'Founder’s Message — Pragya Jain | Zuvio Global School', 'Read the personal message from Co-Founder Pragya Jain on why education must adapt to the child, not the child to the system.', 'https://zuvioglobalschool.com/founder-message', 'index, follow', 'Founder’s Message | Zuvio Global School', 'A personal note from our Founder.', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'),
(6, 'Accreditations', 'IAO accredited, ISSO member, Oxford Quality', 'verification', 'Affiliations & Accreditations | Zuvio Global School', 'Verify our official accreditations: IAO (IA 4883), ISSO sports membership, and Oxford Quality education partnership.', 'https://zuvioglobalschool.com/affiliations-accreditations', 'index, follow', 'Affiliations & Accreditations — Zuvio Global School', 'IAO Accredited, ISSO Member, Oxford Quality Partner.', '/assets/images/iao-logo.png'),
(7, 'Curriculum Framework', 'CBSE curriculum online, Oxford thematic learning, K-8 subjects', 'educational research', 'Curriculum Framework — K to Grade 8 | Zuvio Global School', 'Detailed curriculum pathways mapped to CBSE, NEP 2020, and NCF across Early Years, Foundation, Preparatory, and Middle School stages.', 'https://zuvioglobalschool.com/curriculum', 'index, follow', 'Curriculum Framework | Zuvio Global School', 'CBSE, NEP 2020 & Oxford Thematic Learning.', '/assets/images/Students learning in classroom.png'),
(8, 'Academics Overview', 'online schooling methodology, assessment model', 'informational', 'Academics Overview | Zuvio Global School', 'Explore our comprehensive academic framework: interdisciplinary learning, continuous growth assessment, and digital literacy.', 'https://zuvioglobalschool.com/academics', 'index, follow', 'Academics | Zuvio Global School', 'Future-ready academic pathways.', '/assets/images/Students learning in classroom.png'),
(9, 'Technology AI Labs', 'IBM AI certified school, coding for kids, virtual LMS', 'feature search', 'Technology & AI Labs | Zuvio Global School', 'Interactive STEM, age-appropriate AI literacy, IBM-supported curriculum, and modern US-based LMS infrastructure.', 'https://zuvioglobalschool.com/technology', 'index, follow', 'Technology & AI Labs | Zuvio Global School', 'Empowering digital fluency from Grade 1.', '/assets/images/Hero image 2.png'),
(10, 'Special Education', 'inclusive online school, SEN educators, neurodivergent support', 'informational', 'Special Education & Inclusion | Zuvio Global School', 'Dedicated Special Educators and customized Individualized Education Plans (IEP) ensuring every learner flourishes.', 'https://zuvioglobalschool.com/special-education', 'index, follow', 'Special Education | Zuvio Global School', 'Inclusive by design for diverse minds.', '/assets/images/about_us_hero.jpg'),
(11, 'Electives Languages', 'foreign languages, coding, creative arts electives', 'exploratory', 'Electives & Foreign Languages | Zuvio Global School', 'Enrichment electives including French, Spanish, Sanskrit, Coding, Financial Literacy, and Speech & Drama.', 'https://zuvioglobalschool.com/electives', 'index, follow', 'Electives & Languages | Zuvio Global School', 'Beyond the textbook enrichment.', '/assets/images/Hero image 1.png'),
(12, 'NEP 2020', 'NEP 2020 compliance online school, 5+3+3+4 framework', 'compliance', 'NEP 2020 Pedagogical Alignment | Zuvio Global School', 'How Zuvio implements the National Education Policy 2020 with experiential learning, formative assessment, and multi-disciplinary pathways.', 'https://zuvioglobalschool.com/nep-2020', 'index, follow', 'NEP 2020 Guidelines | Zuvio Global School', 'Aligned with India’s National Education Policy.', '/assets/images/Hero image 2.png'),
(13, 'Academic Resources', 'school worksheets, digital textbooks, parent resource kit', 'resource download', 'Academic Resources & Downloads | Zuvio Global School', 'Access curated digital library resources, Oxford reading supplements, coding consoles, and diagnostic sample papers.', 'https://zuvioglobalschool.com/resources', 'index, follow', 'Academic Resources | Zuvio Global School', 'Curated learning resources and toolkits.', '/assets/images/Students learning in classroom.png'),
(14, 'Admissions', 'online school admission, enrol child K-8', 'transactional', 'Admissions & Onboarding | Zuvio Global School', 'Begin your child’s onboarding journey. Explore fee structures, eligibility criteria, academic calendar, and enrollment steps.', 'https://zuvioglobalschool.com/admissions', 'index, follow', 'Admissions | Zuvio Global School', 'Admissions Open for Academic Year 2026–2027.', '/assets/images/logo.png'),
(15, 'Enrol Now', 'school enrollment steps, register online school', 'transactional', 'Enrol Now — 5 Simple Steps | Zuvio Global School', 'Our simple 5-step admissions process: counselor discussion, application, diagnostic interaction, enrollment, and orientation.', 'https://zuvioglobalschool.com/admissions/enrol-now', 'index, follow', 'Enrol Now | Zuvio Global School', 'Seamless admissions in 5 simple steps.', '/assets/images/logo.png'),
(16, 'Eligibility Criteria', 'online school age criteria, nursery to grade 8', 'informational', 'Eligibility Criteria & Age Guidelines | Zuvio Global School', 'Age benchmarks, previous academic documentation requirements, and prerequisite tech setup for enrollment.', 'https://zuvioglobalschool.com/admissions/eligibility', 'index, follow', 'Eligibility Criteria | Zuvio Global School', 'Clear age and admission benchmarks.', '/assets/images/logo.png'),
(17, 'Academic Calendar', 'school terms, exam dates, vacation calendar', 'informational', 'Academic Calendar 2026–2027 | Zuvio Global School', 'Term schedules, examination windows, major holidays, and international student scheduling details.', 'https://zuvioglobalschool.com/admissions/calendar', 'index, follow', 'Academic Calendar | Zuvio Global School', 'Term dates and academic schedule.', '/assets/images/logo.png'),
(18, 'Fee Structure', 'online school fees, tuition cost K-8', 'transactional', 'Fee Structure & Transparent Pricing | Zuvio Global School', 'Transparent, all-inclusive annual and term fee breakdowns with sibling discounts and scholarship details.', 'https://zuvioglobalschool.com/admissions/fees', 'index, follow', 'Fee Structure | Zuvio Global School', 'Transparent and competitive tuition fees.', '/assets/images/logo.png'),
(19, 'Parent FAQs', 'frequently asked questions online schooling, 18 questions', 'informational', 'Parent FAQs — Online Schooling Answers | Zuvio Global School', 'Comprehensive answers to all 18 top parent questions: timings, screen time, CBSE alignment, offline transition, and teacher access.', 'https://zuvioglobalschool.com/faq', 'index, follow', 'Parent FAQs | Zuvio Global School', 'Clear answers to all parent questions.', '/assets/images/logo.png'),
(20, 'Beyond Overview', 'extracurricular programs, holistic development', 'informational', 'Zuvio Beyond — Holistic Development Programmes', 'Explore our signature non-academic programmes: AI Explorers, Young Authors, Global Citizenship, and Entrepreneurship.', 'https://zuvioglobalschool.com/beyond', 'index, follow', 'Zuvio Beyond Programmes', 'Preparing the complete graduate.', '/assets/images/about_us_hero.jpg'),
(21, 'Co-curricular Clubs', 'school clubs, debate club, coding club, art', 'activity search', 'Co-Curricular & Student Clubs | Zuvio Global School', 'Interactive Friday student clubs spanning STEM, Creative Writing, Public Speaking, Chess, and Environmental Stewardship.', 'https://zuvioglobalschool.com/beyond/co-curricular', 'index, follow', 'Co-Curricular & Clubs | Zuvio Global School', 'Discover passions through active student clubs.', '/assets/images/Hero image 1.png'),
(22, 'Student Achievers', 'student awards, olympiad winners, achievers', 'social proof', 'Student Achievers & Hall of Fame | Zuvio Global School', 'Celebrating distinguished student accomplishments across international competitions, Olympiads, and creative arts.', 'https://zuvioglobalschool.com/beyond/student-achievers', 'index, follow', 'Student Achievers | Zuvio Global School', 'Our students shine on global stages.', '/assets/images/Students learning in classroom.png'),
(23, 'Photo Gallery', 'school photos, virtual classroom photos, student projects', 'visual proof', 'Campus & Life Gallery | Zuvio Global School', 'Visual glimpse into life at Zuvio: interactive classes, student project showcases, and community celebrations.', 'https://zuvioglobalschool.com/beyond/gallery', 'index, follow', 'Gallery | Zuvio Global School', 'Life and learning at Zuvio.', '/assets/images/Students learning in classroom.png'),
(24, 'Virtual Classroom', 'inside online classroom, LMS preview', 'product preview', 'Inside the Virtual Classroom | Zuvio Global School', 'A guided walkthrough of our live interactive classroom environment, secure digital tools, and active student participation.', 'https://zuvioglobalschool.com/beyond/virtual-classroom', 'index, follow', 'Inside the Virtual Classroom | Zuvio Global School', 'Safe, engaging, and interactive live learning.', '/assets/images/Students learning in classroom.png'),
(25, 'Blogs Insights', 'education articles, parenting tips, schooling trends', 'content exploration', 'Blogs & Insights | Zuvio Global School', 'Expert perspectives, school announcements, pedagogical research, and parenting guides for modern online education.', 'https://zuvioglobalschool.com/blogs', 'index, follow', 'Blogs & Insights | Zuvio Global School', 'Perspectives for modern parents and learners.', '/assets/images/about_us_hero.jpg'),
(26, 'Contact Us', 'school address, phone, admissions office Netaji Subhash Place', 'transactional', 'Contact Us & Campus Enquiries | Zuvio Global School', 'Connect with our admissions office in Netaji Subhash Place, Delhi. Phone: +91 7827262956 | info@zuvioglobalschool.com', 'https://zuvioglobalschool.com/contact', 'index, follow', 'Contact Us | Zuvio Global School', 'We are here to support your child.', '/assets/images/logo.png')
ON DUPLICATE KEY UPDATE
  `seo_title` = VALUES(`seo_title`),
  `meta_description` = VALUES(`meta_description`),
  `canonical_url` = VALUES(`canonical_url`),
  `index_status` = VALUES(`index_status`),
  `og_title` = VALUES(`og_title`),
  `og_description` = VALUES(`og_description`);

-- 4. Ensure Blog Categories table exists with standard categories
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO `blog_categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Pedagogy & Curriculum', 'pedagogy-curriculum', 'Insights into Oxford thematic learning, CBSE mapping, and progressive education.'),
(2, 'Technology & AI', 'technology-ai', 'Artificial intelligence, coding, and future skills in online schooling.'),
(3, 'Parenting & Life Skills', 'parenting-life-skills', 'Guidance for homeschooling parents, emotional resilience, and work-life balance.'),
(4, 'School News & Announcements', 'school-news', 'Official updates, circulars, and announcements from Zuvio Global School.'),
(5, 'Awards & Recognition', 'awards-recognition', 'Achievements, accreditations, and media coverage highlighting school excellence.');

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
