-- Zuvio Global School - Production Database Backup
-- Exported on: 2026-08-19T07:10:08.499Z
-- Database: u869064717_school
-- Host: srv1113.hstgr.io

SET FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Schema for table: about_sections
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `about_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: about_sections (Row count: 2)
INSERT INTO `about_sections` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'our_story', 'Our Story', 'How Zuvio Began', 'Zuvio Global School began from a question: “What if education was designed for the child instead of expecting the child to fit the system?” As technology, AI and global connectivity changed the world, education also needed to evolve. Zuvio was conceived to provide flexible, personalised, globally connected learning that nurtures creativity, critical thinking, communication and adaptability.', '/assets/images/Hero image 2.png', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `about_sections` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'vision_mission', 'Vision, Mission & Beliefs', 'Our Compass', 'Vision: To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.\n\nMission: To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.\n\nEducational Philosophy: “Every Child. Every Mind. Every Possibility.”', NULL, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: about_timeline
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `about_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: about_timeline (Row count: 1)
INSERT INTO `about_timeline` (`id`, `year`, `title`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, '2026', 'Foundation', 'Zuvio Global School is officially established, introducing the 8C Philosophy™, Zuvio Compass™, and Learning Model™.', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: audit_logs
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL,
  `entity_type` varchar(100) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `old_data` longtext DEFAULT NULL,
  `new_data` longtext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Schema for table: beyond_gallery
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beyond_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Schema for table: beyond_sections
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beyond_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: beyond_sections (Row count: 2)
INSERT INTO `beyond_sections` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'intro', 'Beyond Academics', 'Holistic Development at Zuvio', 'Zuvio goes beyond textbooks and examinations, with a focus on curiosity, creativity, critical thinking, communication, collaboration, real-world learning, character and life skills. Technology, innovation, projects, and global opportunities are central themes.', NULL, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `beyond_sections` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'activities_placeholder', 'Our Extracurricular Programs', 'Sports, Arts & Clubs', 'Content pending - Specific program descriptions, grades, and schedules for Sports, Music, Dance, Theatre, Visual Arts, Clubs, and Trips will remain draft placeholders until finalized.', NULL, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: blog_categories
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: blog_categories (Row count: 10)
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (1, 'Education', 'education', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (2, 'Parenting', 'parenting', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (3, 'Student Life', 'student-life', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (4, 'Curriculum', 'curriculum', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (5, 'School News', 'school-news', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (6, 'Events', 'events', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (7, 'Achievements', 'achievements', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (8, 'Activities', 'activities', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (9, 'Career Guidance', 'career-guidance', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (10, 'Learning & Development', 'learning-development', '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: blog_tag_map
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_tag_map` (
  `blog_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `blog_tag_map_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_tag_map_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Schema for table: blog_tags
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: blog_tags (Row count: 4)
INSERT INTO `blog_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (1, 'E-learning', 'e-learning', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (2, 'Admissions', 'admissions', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (3, 'CBSE', 'cbse', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `blog_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES (4, 'Personalized Education', 'personalized-education', '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: blogs
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author` varchar(150) DEFAULT NULL,
  `author_designation` varchar(150) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `seo_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: blogs (Row count: 1)
INSERT INTO `blogs` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `author`, `author_designation`, `category_id`, `publish_date`, `status`, `seo_title`, `meta_description`, `canonical_url`, `og_image`, `created_at`, `updated_at`) VALUES (1, 'Welcome to Zuvio Global School: Learning Beyond Boundaries', 'welcome-to-zuvio-global-school', 'An introductory post explaining Zuvio\'s vision of a borderless classroom, CBSE-aligned paths, and child-centered systems.', 'At Zuvio Global School, we believe that education should adapt to the child, rather than the child fitting the system. Launched in 2026, Zuvio represents a new paradigm of digital-first school models. Over the coming weeks, we will explore our 8C Philosophy™, including the development of Curiosity, Creativity, Compassion, and Character alongside academic achievements. Read about our partnerships with IAO and Oxford, and join us on this global learning journey.', '/assets/images/Hero image 1.png', 'Zuvio Editorial', 'Content Writer', 5, '2026-08-17 18:30:00', 'draft', 'Welcome to Zuvio Global School | Blog', 'Discover our digital-first school model launched in 2026, combining CBSE alignment with personalized pathways.', NULL, NULL, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: curriculum_items
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curriculum_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stage_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `stage_id` (`stage_id`),
  CONSTRAINT `curriculum_items_ibfk_1` FOREIGN KEY (`stage_id`) REFERENCES `curriculum_stages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: curriculum_items (Row count: 4)
INSERT INTO `curriculum_items` (`id`, `stage_id`, `title`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 1, 'Curiosity and Discovery', 'Focus on building exploratory senses and baseline language abilities.', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `curriculum_items` (`id`, `stage_id`, `title`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 2, 'Core Foundations', 'Mathematics, Science, English, and Social Studies aligned with CBSE/NIOS.', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `curriculum_items` (`id`, `stage_id`, `title`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 3, 'Analytical Growth', 'Critical thinking, advanced science foundations, and initial technology exposure.', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `curriculum_items` (`id`, `stage_id`, `title`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (4, 3, 'Extracurricular Activities', 'Content pending - detailed grade-wise extracurricular activity lists will follow.', 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: curriculum_stages
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `curriculum_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: curriculum_stages (Row count: 3)
INSERT INTO `curriculum_stages` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Early Years', 'early-years', 'Introduction to fundamental social, cognitive, and physical development steps.', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `curriculum_stages` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Primary School', 'primary-school', 'Core subjects foundational study (Grades 1 to 5).', 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `curriculum_stages` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Middle School', 'middle-school', 'Analytical thinking and specialized modules alignment (Grades 6 to 8).', 3, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: enquiries
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_name` varchar(150) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `enquiries_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `enquiry_statuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: enquiries (Row count: 2)
INSERT INTO `enquiries` (`id`, `parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`, `created_at`, `updated_at`) VALUES (1, 'Test', 'Test', 'Primary (1-5)', '8700236209', 'test@gmail.com', 'Test', 'Contact Page', 1, '2026-08-19 00:48:45', '2026-08-19 00:48:45');
INSERT INTO `enquiries` (`id`, `parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`, `created_at`, `updated_at`) VALUES (2, 'Test', 'Test (Student)', 'Early Years', '8700236209', 'designer@kairali.com', 'Test', 'Home Banner', 1, '2026-08-19 00:59:44', '2026-08-19 00:59:44');

-- -----------------------------------------------------
-- Schema for table: enquiry_statuses
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `enquiry_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: enquiry_statuses (Row count: 5)
INSERT INTO `enquiry_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'new', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `enquiry_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'contacted', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `enquiry_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES (3, 'in_progress', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `enquiry_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES (4, 'resolved', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `enquiry_statuses` (`id`, `name`, `created_at`, `updated_at`) VALUES (5, 'archived', '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: hero_slide_versions
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_slide_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hero_slide_id` int(11) NOT NULL,
  `version_number` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `primary_cta_text` varchar(100) DEFAULT NULL,
  `primary_cta_url` varchar(255) DEFAULT NULL,
  `secondary_cta_text` varchar(100) DEFAULT NULL,
  `secondary_cta_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `change_summary` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hero_slide_id` (`hero_slide_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `hero_slide_versions_ibfk_1` FOREIGN KEY (`hero_slide_id`) REFERENCES `hero_slides` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hero_slide_versions_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Schema for table: hero_slides
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `mobile_image` varchar(255) DEFAULT NULL,
  `primary_cta_text` varchar(50) DEFAULT NULL,
  `primary_cta_url` varchar(255) DEFAULT NULL,
  `secondary_cta_text` varchar(50) DEFAULT NULL,
  `secondary_cta_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: hero_slides (Row count: 4)
INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `description`, `image`, `mobile_image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Learning Beyond Boundaries', 'A future-ready online school where academic excellence meets personalised learning.', 'At Zuvio Global School, we empower children to learn beyond boundaries and grow with confidence.', '/assets/images/Hero image 1.png', NULL, 'Explore Zuvio', '/our-curriculum', 'Enquire Now', '/contact', 1, 1, '2026-08-18 13:29:52', '2026-08-19 00:48:19');
INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `description`, `image`, `mobile_image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Academic & Global Partnerships', 'In Partnership with Globally Recognised Institutions', 'Zuvio Global School collaborates with Oxford and IAO, strengthening our commitment to globally benchmarked learning, academic excellence, and international standards.', '/assets/images/Hero image 2.png', NULL, 'Learn More', '/about', 'Enquire Now', '/contact', 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `description`, `image`, `mobile_image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Interactive Digital Classrooms', 'CBSE, NEP & NCF Aligned Curriculum', 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.', '/assets/images/Students learning in classroom.png', NULL, 'Our Curriculum', '/our-curriculum', 'Enquire Now', '/contact', 3, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `description`, `image`, `mobile_image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'World-Class Teachers', 'Experienced educators bringing diverse global perspectives', 'Personalised learning experiences that recognise every child\'s unique pace, strengths, interests, and potential.', '/assets/images/Teacher interacting with students.png', NULL, 'Our Philosophy', '/about', 'Enquire Now', '/contact', 4, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: homepage_features
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `homepage_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: homepage_features (Row count: 8)
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Global Presence', 'A globally connected learning community with an international outlook.', 'globe', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'International Credibility', 'Global standards, perspectives, and learning practices designed for a changing world.', 'award', 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'World-Class Teachers', 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.', 'users', 3, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'US-Based Learning Platform', 'A powerful, thoughtfully designed US-based LMS that brings learning, collaboration, resources, and progress tracking together.', 'monitor', 4, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'CBSE, NEP & NCF Aligned', 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.', 'check-circle', 5, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'Personalised Learning', 'Learning experiences that recognise every child\'s unique pace, strengths, interests, and potential.', 'user-check', 6, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (7, 'Inclusive Learning', 'A supportive environment with a special focus on special learners, ensuring every child feels included, valued, and empowered.', 'heart', 7, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_features` (`id`, `title`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (8, 'World-Class Experiences', 'Beyond academics—with technology, creativity, collaboration, projects, and real-world experiences.', 'zap', 8, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: homepage_sections
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `homepage_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: homepage_sections (Row count: 2)
INSERT INTO `homepage_sections` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'brand_promise', 'Every child deserves an education that prepares them for life, not just examinations.', 'Brand Promise & Philosophy', 'We are not building another school. We are building a future where every child has the opportunity to learn beyond boundaries.', NULL, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_sections` (`id`, `section_key`, `title`, `subtitle`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'academic_highlights', 'Key Academic Highlights', 'Our Learning Advantages', 'Zuvio offers CBSE, NEP & NCF Aligned Curriculum, a US-Based Learning Platform, Partnerships with Oxford & IAO, World-Class Educators, and Individualised Support for Every Learner.', NULL, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: homepage_stats
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `homepage_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `value` varchar(50) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: homepage_stats (Row count: 4)
INSERT INTO `homepage_stats` (`id`, `label`, `value`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Established', '2026', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_stats` (`id`, `label`, `value`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Student-Teacher Ratio', '15:1', 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_stats` (`id`, `label`, `value`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Students Enrolled', 'Content pending', 3, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `homepage_stats` (`id`, `label`, `value`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'World-Class Educators', 'Content pending', 4, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: leadership
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `leadership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: leadership (Row count: 2)
INSERT INTO `leadership` (`id`, `name`, `designation`, `image`, `bio`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Pragya Jain', 'Co-Founder & Director', NULL, 'Content pending - bio and background profile details will be provided.', 'Content pending - personal founder message to be updated.', 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `leadership` (`id`, `name`, `designation`, `image`, `bio`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Deepak Jain', 'Co-Founder & Director', NULL, 'Content pending - bio and background profile details will be provided.', 'Content pending - personal founder message to be updated.', 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: media
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `storage_path` varchar(255) NOT NULL,
  `public_url` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: media (Row count: 11)
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (1, 'zuvio-logo-extracted.png', 'uploads/be71b01c1366da18_1787120028.png', '/uploads/be71b01c1366da18_1787120028.png', NULL, 'image/png', 1910, 1837, 1065675, '2026-08-19 00:43:48', '2026-08-19 00:43:48');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (2, 'logo.png', 'assets/images/logo.png', '/assets/images/logo.png', 'Zuvio Global School logo', 'image/png', 240, 60, 20480, '2026-08-19 01:12:18', '2026-08-19 01:12:18');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (3, 'Hero image 1.png', 'assets/images/Hero image 1.png', '/assets/images/Hero image 1.png', 'Homepage Slide Banner 1', 'image/png', 1920, 800, 1048576, '2026-08-19 01:12:18', '2026-08-19 01:12:18');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (4, 'Hero image 2.png', 'assets/images/Hero image 2.png', '/assets/images/Hero image 2.png', 'Homepage Slide Banner 2', 'image/png', 1920, 800, 1048576, '2026-08-19 01:12:18', '2026-08-19 01:12:18');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (5, 'Students learning in classroom.png', 'assets/images/Students learning in classroom.png', '/assets/images/Students learning in classroom.png', 'Students in classroom', 'image/png', 800, 600, 800000, '2026-08-19 01:12:18', '2026-08-19 01:12:18');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (6, 'Teacher interacting with students.png', 'assets/images/Teacher interacting with students.png', '/assets/images/Teacher interacting with students.png', 'Teacher with students', 'image/png', 800, 600, 800000, '2026-08-19 01:12:18', '2026-08-19 01:12:18');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (7, 'logo.png', 'assets/images/logo.png', '/assets/images/logo.png', 'Zuvio Global School logo', 'image/png', 240, 60, 20480, '2026-08-19 01:13:27', '2026-08-19 01:13:27');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (8, 'Hero image 1.png', 'assets/images/Hero image 1.png', '/assets/images/Hero image 1.png', 'Homepage Slide Banner 1', 'image/png', 1920, 800, 1048576, '2026-08-19 01:13:27', '2026-08-19 01:13:27');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (9, 'Hero image 2.png', 'assets/images/Hero image 2.png', '/assets/images/Hero image 2.png', 'Homepage Slide Banner 2', 'image/png', 1920, 800, 1048576, '2026-08-19 01:13:27', '2026-08-19 01:13:27');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (10, 'Students learning in classroom.png', 'assets/images/Students learning in classroom.png', '/assets/images/Students learning in classroom.png', 'Students in classroom', 'image/png', 800, 600, 800000, '2026-08-19 01:13:27', '2026-08-19 01:13:27');
INSERT INTO `media` (`id`, `file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `width`, `height`, `file_size`, `created_at`, `updated_at`) VALUES (11, 'Teacher interacting with students.png', 'assets/images/Teacher interacting with students.png', '/assets/images/Teacher interacting with students.png', 'Teacher with students', 'image/png', 800, 600, 800000, '2026-08-19 01:13:27', '2026-08-19 01:13:27');

-- -----------------------------------------------------
-- Schema for table: media_versions
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `media_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `version_number` int(11) NOT NULL,
  `original_path` varchar(255) NOT NULL,
  `backup_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `replaced_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`),
  KEY `replaced_by` (`replaced_by`),
  CONSTRAINT `media_versions_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE,
  CONSTRAINT `media_versions_ibfk_2` FOREIGN KEY (`replaced_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Schema for table: navigation_items
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `navigation_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `navigation_items_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `navigation_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: navigation_items (Row count: 6)
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Home', '/', NULL, 1, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'About Us', '/about', NULL, 2, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Our Curriculum', '/our-curriculum', NULL, 3, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'Zuvio Beyond', '/zuvio-beyond', NULL, 4, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'Blogs', '/blogs', NULL, 5, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'Contact Us', '/contact', NULL, 6, 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: page_seo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `page_seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `primary_keyword` varchar(100) DEFAULT NULL,
  `secondary_keywords` text DEFAULT NULL,
  `search_intent` varchar(255) DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `index_status` varchar(50) DEFAULT 'index, follow',
  `og_title` varchar(200) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`),
  CONSTRAINT `page_seo_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: page_seo (Row count: 6)
INSERT INTO `page_seo` (`id`, `page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`, `created_at`, `updated_at`) VALUES (1, 1, 'Zuvio Global School', 'online school, K-8 school, future-ready learning', 'brand search', 'Zuvio Global School | Learning Beyond Boundaries', 'Zuvio Global School is a future-ready online school where academic excellence meets personalised learning. Empowering children to learn beyond boundaries.', 'https://zuvioglobalschool.com/', 'index, follow', 'Zuvio Global School', 'Learning Beyond Boundaries', '/assets/images/Hero image 1.png', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `page_seo` (`id`, `page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`, `created_at`, `updated_at`) VALUES (2, 2, 'About Zuvio Global School', 'school story, educational philosophy', 'informational', 'About Us | Zuvio Global School', 'Discover the story behind Zuvio Global School, our vision, mission, core values, and our academic founders.', 'https://zuvioglobalschool.com/about', 'index, follow', 'About Zuvio Global School', 'Our Vision, Mission and Story', '/assets/images/Hero image 2.png', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `page_seo` (`id`, `page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`, `created_at`, `updated_at`) VALUES (3, 3, 'Zuvio Curriculum', 'CBSE NIOS curriculum online school, K-8 subjects', 'educational research', 'Our Curriculum | Zuvio Global School', 'Explore Zuvio Global School curriculum aligned with CBSE, NEP 2020, and NCF supporting custom personalized pathways for students.', 'https://zuvioglobalschool.com/our-curriculum', 'index, follow', 'Our Curriculum', 'CBSE & NIOS Aligned Online Learning', NULL, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `page_seo` (`id`, `page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`, `created_at`, `updated_at`) VALUES (4, 4, 'Zuvio Beyond Academics', 'extracurricular activities, holistic student life', 'interest', 'Zuvio Beyond | Extracurricular & Activities', 'Holistic developmental program at Zuvio Global School spanning arts, STEM, character development and global perspectives.', 'https://zuvioglobalschool.com/zuvio-beyond', 'index, follow', 'Zuvio Beyond Academics', 'Holistic learning beyond the classroom.', NULL, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `page_seo` (`id`, `page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`, `created_at`, `updated_at`) VALUES (5, 5, 'Zuvio School Blog', 'education articles, parenting advice', 'informational', 'Blogs | Zuvio Global School', 'Read parenting guides, online education trends, student life articles, and school announcements.', 'https://zuvioglobalschool.com/blogs', 'index, follow', 'Blogs', 'Zuvio Global School Articles and Resources', NULL, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `page_seo` (`id`, `page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`, `created_at`, `updated_at`) VALUES (6, 6, 'Contact Zuvio Global School', 'school address, phone, admissions office', 'transactional', 'Contact Us | Zuvio Global School', 'Get in touch with Zuvio Global School. Find office timings, phone numbers, emails, and enquiry form.', 'https://zuvioglobalschool.com/contact', 'index, follow', 'Contact Us', 'Connect with Zuvio Global School', NULL, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: pages
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: pages (Row count: 6)
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Home', 'home', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'About Us', 'about', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Our Curriculum', 'our-curriculum', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'Zuvio Beyond', 'zuvio-beyond', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'Blogs', 'blogs', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'Contact Us', 'contact', 1, '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: permissions
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: permissions (Row count: 28)
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (1, 'dashboard.view', 'View admin dashboard statistics', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (2, 'blogs.view', 'View list of blogs', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (3, 'blogs.create', 'Create new blog post', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (4, 'blogs.edit', 'Edit blog post details', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (5, 'blogs.delete', 'Delete blog post', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (6, 'blogs.publish', 'Publish/Draft blog posts', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (7, 'hero.view', 'View homepage hero slides', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (8, 'hero.create', 'Add new hero slides', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (9, 'hero.edit', 'Edit hero slide contents', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (10, 'hero.delete', 'Delete hero slides', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (11, 'hero.publish', 'Enable/Disable hero slides', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (12, 'hero.restore', 'Restore old slide versions', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (13, 'hero.history', 'View hero version history', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (14, 'media.view', 'View media assets library', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (15, 'media.upload', 'Upload new media assets', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (16, 'media.replace', 'Overwrite existing image paths atomically', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (17, 'media.restore', 'Restore previous media asset versions', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (18, 'media.delete', 'Delete media assets', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (19, 'enquiries.view', 'View student/parent enquiries list', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (20, 'enquiries.update', 'Update enquiry pipeline statuses', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (21, 'settings.view', 'View system settings configurations', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (22, 'settings.edit', 'Edit site contact info and configurations', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (23, 'users.view', 'View users list and privileges', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (24, 'users.create', 'Add new user accounts', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (25, 'users.edit', 'Edit user accounts information', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (26, 'users.delete', 'Delete user accounts', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (27, 'users.roles', 'Change user roles and privileges', '2026-08-19 01:12:18');
INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES (28, 'audit.view', 'View system audit logs trail', '2026-08-19 01:12:18');

-- -----------------------------------------------------
-- Schema for table: role_permissions
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: role_permissions (Row count: 57)
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 1);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 1);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 1);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 2);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 2);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 2);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 3);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 3);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 3);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 4);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 4);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 4);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 5);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 5);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 6);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 6);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 6);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 7);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 7);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 8);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 8);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 9);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 9);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 10);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 10);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 11);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 11);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 12);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 12);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 13);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 13);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 14);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 14);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 14);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 15);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (2, 15);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 15);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 16);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 16);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 17);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 17);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 18);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 18);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 19);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 19);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 20);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 20);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 21);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 21);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (1, 22);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 22);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 23);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 24);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 25);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 26);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 27);
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES (3, 28);

-- -----------------------------------------------------
-- Schema for table: roles
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: roles (Row count: 3)
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES (1, 'admin', 'Full access to CRUD all components and user management', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES (2, 'editor', 'Access to manage page contents, blogs, media and settings', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES (3, 'super_admin', 'Full platform super administration access', '2026-08-19 01:12:18', '2026-08-19 01:12:18');

-- -----------------------------------------------------
-- Schema for table: site_settings
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: site_settings (Row count: 20)
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (1, 'school_name', 'Zuvio Global School', 'Official school name', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (2, 'logo_url', '/assets/images/logo.png', 'Logo path relative to frontend root', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (3, 'phone', '7827262956', 'Main contact number', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (4, 'whatsapp', '7827262956', 'WhatsApp contact (same as phone)', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (5, 'general_email', 'info@zuvioglobalschool.com', 'General queries email', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (6, 'admissions_email', 'info@zuvioglobalschool.com', 'Admissions queries email', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (7, 'address', 'B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi - 110034', 'Physical office address', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (8, 'office_timings', '10-7', 'Office work hours', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (9, 'social_instagram', 'https://www.instagram.com/thezuvio?igsh=cmUwZGV1YWI3eXc=', 'Instagram handle', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (10, 'social_facebook', 'https://www.facebook.com/share/1F77iQS86d/', 'Facebook page', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (11, 'social_linkedin', 'https://www.linkedin.com/company/142914253/admin/dashboard/', 'LinkedIn company page', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (12, 'google_maps_link', 'Content pending', 'Google Maps listing embed/link (pending)', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (13, 'copyright', '© 2026 Zuvio Global School. All rights reserved.', 'Footer copyright line', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (14, 'theme_color_navy', '#062B63', 'Primary theme color (Zuvio Navy)', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (15, 'theme_color_deep_navy', '#031B42', 'Dark theme background (Zuvio Deep Navy)', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (16, 'theme_color_teal', '#0A8998', 'Secondary teal highlights', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (17, 'theme_color_gold', '#D9A441', 'Metallic Gold accents', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (18, 'theme_color_white', '#FFFFFF', 'White background / surface color', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (19, 'font_primary', 'Playfair Display', 'Main typography font family', '2026-08-18 13:29:52', '2026-08-18 13:29:52');
INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES (20, 'font_secondary', 'Inter', 'Body typography font family', '2026-08-18 13:29:52', '2026-08-18 13:29:52');

-- -----------------------------------------------------
-- Schema for table: users
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: users (Row count: 1)
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role_id`, `created_at`, `updated_at`) VALUES (1, 'zuvioadmin', 'admin@zuvioglobalschool.com', '$2y$10$9s/PwDXrhqMmO3bM2ex82OWlRS65SOOAAcWIf26qphLJSuIMGg4Z.', 1, '2026-08-19 00:37:24', '2026-08-19 00:40:15');

SET FOREIGN_KEY_CHECKS=1;
