-- Zuvio Global School Database Seed Data (Phase 1)

-- 1. Roles
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Full access to CRUD all components and user management'),
(2, 'editor', 'Access to manage page contents, blogs, media and settings');

-- 2. Users (Password hash is for 'zuvioadmin123' using bcrypt)
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role_id`) VALUES
(1, 'zuvioadmin', 'admin@zuvioglobalschool.com', '$2a$10$wK1k6xsk1pGusA.u7H7jcuLwQW8VigW4T/mO07m/b02y36H11q9lq', 1);

-- 3. Site Settings (Based on Requirements Document)
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `description`) VALUES
('school_name', 'Zuvio Global School', 'Official school name'),
('logo_url', '/assets/images/zuvio-logo.png', 'Logo path relative to frontend root'),
('phone', '7827262956', 'Main contact number'),
('whatsapp', '7827262956', 'WhatsApp contact (same as phone)'),
('general_email', 'info@zuvioglobalschool.com', 'General queries email'),
('admissions_email', 'info@zuvioglobalschool.com', 'Admissions queries email'),
('address', 'B-09, Lower Ground Floor, ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi - 110034', 'Physical office address'),
('office_timings', '10-7', 'Office work hours'),
('social_instagram', 'https://www.instagram.com/thezuvio?igsh=cmUwZGV1YWI3eXc=', 'Instagram handle'),
('social_facebook', 'https://www.facebook.com/share/1F77iQS86d/', 'Facebook page'),
('social_linkedin', 'https://www.linkedin.com/company/142914253/admin/dashboard/', 'LinkedIn company page'),
('google_maps_link', 'Content pending', 'Google Maps listing embed/link (pending)'),
('copyright', '© 2026 Zuvio Global School. All rights reserved.', 'Footer copyright line'),
('theme_color_navy', '#0F172A', 'Primary theme color'),
('theme_color_gold', '#D97706', 'Secondary highlight color'),
('theme_color_emerald', '#059669', 'Tertiary success/action color'),
('font_primary', 'Playfair Display', 'Main typography font family'),
('font_secondary', 'Inter', 'Body typography font family');

-- 4. Pages & Initial SEO Layout
INSERT INTO `pages` (`id`, `name`, `slug`, `is_active`) VALUES
(1, 'Home', 'home', 1),
(2, 'About Us', 'about-us', 1),
(3, 'Our Curriculum', 'our-curriculum', 1),
(4, 'Zuvio Beyond', 'zuvio-beyond', 1),
(5, 'Blogs', 'blogs', 1),
(6, 'Contact Us', 'contact-us', 1),
(7, 'Admissions', 'admissions', 1);

INSERT INTO `page_seo` (`page_id`, `primary_keyword`, `secondary_keywords`, `search_intent`, `seo_title`, `meta_description`, `canonical_url`, `index_status`, `og_title`, `og_description`, `og_image`) VALUES
(1, 'Zuvio Global School', 'online school, K-8 school, future-ready learning', 'brand search', 'Zuvio Global School | Learning Beyond Boundaries', 'Zuvio Global School is a future-ready online school where academic excellence meets personalised learning. Empowering children to learn beyond boundaries.', 'https://zuvioglobalschool.com/', 'index, follow', 'Zuvio Global School', 'Learning Beyond Boundaries', '/assets/images/Hero image 1.png'),
(2, 'About Zuvio Global School', 'school story, educational philosophy', 'informational', 'About Us | Zuvio Global School', 'Discover the story behind Zuvio Global School, our vision, mission, core values, and our academic founders.', 'https://zuvioglobalschool.com/about-us', 'index, follow', 'About Zuvio Global School', 'Our Vision, Mission and Story', '/assets/images/Hero image 2.png'),
(3, 'Zuvio Curriculum', 'CBSE NIOS curriculum online school, K-8 subjects', 'educational research', 'Our Curriculum | Zuvio Global School', 'Explore Zuvio Global School curriculum aligned with CBSE, NEP 2020, and NCF supporting custom personalized pathways for students.', 'https://zuvioglobalschool.com/our-curriculum', 'index, follow', 'Our Curriculum', 'CBSE & NIOS Aligned Online Learning', NULL),
(4, 'Zuvio Beyond Academics', 'extracurricular activities, holistic student life', 'interest', 'Zuvio Beyond | Extracurricular & Activities', 'Holistic developmental program at Zuvio Global School spanning arts, STEM, character development and global perspectives.', 'https://zuvioglobalschool.com/zuvio-beyond', 'index, follow', 'Zuvio Beyond Academics', 'Holistic learning beyond the classroom.', NULL),
(5, 'Zuvio School Blog', 'education articles, parenting advice', 'informational', 'Blogs | Zuvio Global School', 'Read parenting guides, online education trends, student life articles, and school announcements.', 'https://zuvioglobalschool.com/blogs', 'index, follow', 'Blogs', 'Zuvio Global School Articles and Resources', NULL),
(6, 'Contact Zuvio Global School', 'school address, phone, admissions office', 'transactional', 'Contact Us | Zuvio Global School', 'Get in touch with Zuvio Global School. Find office timings, phone numbers, emails, and enquiry form.', 'https://zuvioglobalschool.com/contact-us', 'index, follow', 'Contact Us', 'Connect with Zuvio Global School', NULL),
(7, 'Admissions Zuvio School', 'admissions process, online school registration', 'transactional', 'Admissions | Zuvio Global School', 'Start the admission process, read eligibility criteria, and submit student registration inquiries.', 'https://zuvioglobalschool.com/admissions', 'index, follow', 'Admissions', 'Admissions Open K-8', NULL);

-- 5. Hero Slides (Preserving and registering the 4 supplied images)
INSERT INTO `hero_slides` (`title`, `subtitle`, `description`, `image`, `mobile_image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`) VALUES
('Learning Beyond Boundaries', 'A future-ready online school where academic excellence meets personalised learning.', 'At Zuvio Global School, we empower children to learn beyond boundaries and grow with confidence.', '/assets/images/Hero image 1.png', NULL, 'Explore Zuvio', '/our-curriculum', 'Enquire Now', '/contact-us', 1, 1),
('Academic & Global Partnerships', 'In Partnership with Globally Recognised Institutions', 'Zuvio Global School collaborates with Oxford and IAO, strengthening our commitment to globally benchmarked learning, academic excellence, and international standards.', '/assets/images/Hero image 2.png', NULL, 'Learn More', '/about-us', 'Enquire Now', '/contact-us', 2, 1),
('Interactive Digital Classrooms', 'CBSE, NEP & NCF Aligned Curriculum', 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.', '/assets/images/Students learning in classroom.png', NULL, 'Our Curriculum', '/our-curriculum', 'Enquire Now', '/contact-us', 3, 1),
('World-Class Teachers', 'Experienced educators bringing diverse global perspectives', 'Personalised learning experiences that recognise every child''s unique pace, strengths, interests, and potential.', '/assets/images/Teacher interacting with students.png', NULL, 'Our Philosophy', '/about-us', 'Enquire Now', '/contact-us', 4, 1);

-- 6. Homepage Sections
INSERT INTO `homepage_sections` (`section_key`, `title`, `subtitle`, `content`, `image`, `is_active`) VALUES
('brand_promise', 'Every child deserves an education that prepares them for life, not just examinations.', 'Brand Promise & Philosophy', 'We are not building another school. We are building a future where every child has the opportunity to learn beyond boundaries.', NULL, 1),
('academic_highlights', 'Key Academic Highlights', 'Our Learning Advantages', 'Zuvio offers CBSE, NEP & NCF Aligned Curriculum, a US-Based Learning Platform, Partnerships with Oxford & IAO, World-Class Educators, and Individualised Support for Every Learner.', NULL, 1);

-- 7. Homepage Stats (Preserving empty state for Student/Teacher counts as requested)
INSERT INTO `homepage_stats` (`label`, `value`, `sort_order`, `is_active`) VALUES
('Established', '2026', 1, 1),
('Student-Teacher Ratio', '15:1', 2, 1),
('Students Enrolled', 'Content pending', 3, 1),
('World-Class Educators', 'Content pending', 4, 1);

-- 8. Homepage Features (8C Philosophy & USPs)
INSERT INTO `homepage_features` (`title`, `description`, `icon`, `sort_order`, `is_active`) VALUES
('Global Presence', 'A globally connected learning community with an international outlook.', 'globe', 1, 1),
('International Credibility', 'Global standards, perspectives, and learning practices designed for a changing world.', 'award', 2, 1),
('World-Class Teachers', 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.', 'users', 3, 1),
('US-Based Learning Platform', 'A powerful, thoughtfully designed US-based LMS that brings learning, collaboration, resources, and progress tracking together.', 'monitor', 4, 1),
('CBSE, NEP & NCF Aligned', 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.', 'check-circle', 5, 1),
('Personalised Learning', 'Learning experiences that recognise every child''s unique pace, strengths, interests, and potential.', 'user-check', 6, 1),
('Inclusive Learning', 'A supportive environment with a special focus on special learners, ensuring every child feels included, valued, and empowered.', 'heart', 7, 1),
('World-Class Experiences', 'Beyond academics—with technology, creativity, collaboration, projects, and real-world experiences.', 'zap', 8, 1);

-- 9. Curriculum Stages (K-8)
INSERT INTO `curriculum_stages` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`) VALUES
(1, 'Early Years', 'early-years', 'Introduction to fundamental social, cognitive, and physical development steps.', 1, 1),
(2, 'Primary School', 'primary-school', 'Core subjects foundational study (Grades 1 to 5).', 2, 1),
(3, 'Middle School', 'middle-school', 'Analytical thinking and specialized modules alignment (Grades 6 to 8).', 3, 1);

INSERT INTO `curriculum_items` (`stage_id`, `title`, `description`, `sort_order`, `is_active`) VALUES
(1, 'Curiosity and Discovery', 'Focus on building exploratory senses and baseline language abilities.', 1, 1),
(2, 'Core Foundations', 'Mathematics, Science, English, and Social Studies aligned with CBSE/NIOS.', 1, 1),
(3, 'Analytical Growth', 'Critical thinking, advanced science foundations, and initial technology exposure.', 1, 1),
(3, 'Extracurricular Activities', 'Content pending - detailed grade-wise extracurricular activity lists will follow.', 2, 1);

-- 10. About Us Page Sections
INSERT INTO `about_sections` (`section_key`, `title`, `subtitle`, `content`, `image`, `is_active`) VALUES
('our_story', 'Our Story', 'How Zuvio Began', 'Zuvio Global School began from a question: “What if education was designed for the child instead of expecting the child to fit the system?” As technology, AI and global connectivity changed the world, education also needed to evolve. Zuvio was conceived to provide flexible, personalised, globally connected learning that nurtures creativity, critical thinking, communication and adaptability.', '/assets/images/Hero image 2.png', 1),
('vision_mission', 'Vision, Mission & Beliefs', 'Our Compass', 'Vision: To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.\n\nMission: To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.\n\nEducational Philosophy: “Every Child. Every Mind. Every Possibility.”', NULL, 1);

-- 11. About Us Page Timeline
INSERT INTO `about_timeline` (`year`, `title`, `description`, `sort_order`, `is_active`) VALUES
('2026', 'Foundation', 'Zuvio Global School is officially established, introducing the 8C Philosophy™, Zuvio Compass™, and Learning Model™.', 1, 1);

-- 12. Leadership Team (Factual from brochure/requirements document)
INSERT INTO `leadership` (`name`, `designation`, `image`, `bio`, `message`, `sort_order`, `is_active`) VALUES
('Pragya Jain', 'Co-Founder & Director', NULL, 'Content pending - bio and background profile details will be provided.', 'Content pending - personal founder message to be updated.', 1, 1),
('Deepak Jain', 'Co-Founder & Director', NULL, 'Content pending - bio and background profile details will be provided.', 'Content pending - personal founder message to be updated.', 2, 1);

-- 13. Zuvio Beyond Sections
INSERT INTO `beyond_sections` (`section_key`, `title`, `subtitle`, `content`, `image`, `is_active`) VALUES
('intro', 'Beyond Academics', 'Holistic Development at Zuvio', 'Zuvio goes beyond textbooks and examinations, with a focus on curiosity, creativity, critical thinking, communication, collaboration, real-world learning, character and life skills. Technology, innovation, projects, and global opportunities are central themes.', NULL, 1),
('activities_placeholder', 'Our Extracurricular Programs', 'Sports, Arts & Clubs', 'Content pending - Specific program descriptions, grades, and schedules for Sports, Music, Dance, Theatre, Visual Arts, Clubs, and Trips will remain draft placeholders until finalized.', NULL, 1);

-- 14. Blog Categories (From suggested categories)
INSERT INTO `blog_categories` (`id`, `name`, `slug`) VALUES
(1, 'Education', 'education'),
(2, 'Parenting', 'parenting'),
(3, 'Student Life', 'student-life'),
(4, 'Curriculum', 'curriculum'),
(5, 'School News', 'school-news'),
(6, 'Events', 'events'),
(7, 'Achievements', 'achievements'),
(8, 'Activities', 'activities'),
(9, 'Career Guidance', 'career-guidance'),
(10, 'Learning & Development', 'learning-development');

-- 15. Blog Tags
INSERT INTO `blog_tags` (`id`, `name`, `slug`) VALUES
(1, 'E-learning', 'e-learning'),
(2, 'Admissions', 'admissions'),
(3, 'CBSE', 'cbse'),
(4, 'Personalized Education', 'personalized-education');

-- 16. Demo / Seed Blogs (Using factual foundations from guidelines)
INSERT INTO `blogs` (`title`, `slug`, `excerpt`, `content`, `featured_image`, `author`, `author_designation`, `category_id`, `publish_date`, `status`, `seo_title`, `meta_description`) VALUES
('Welcome to Zuvio Global School: Learning Beyond Boundaries', 'welcome-to-zuvio-global-school', 'An introductory post explaining Zuvio''s vision of a borderless classroom, CBSE-aligned paths, and child-centered systems.', 'At Zuvio Global School, we believe that education should adapt to the child, rather than the child fitting the system. Launched in 2026, Zuvio represents a new paradigm of digital-first school models. Over the coming weeks, we will explore our 8C Philosophy™, including the development of Curiosity, Creativity, Compassion, and Character alongside academic achievements. Read about our partnerships with IAO and Oxford, and join us on this global learning journey.', '/assets/images/Hero image 1.png', 'Zuvio Editorial', 'Content Writer', 5, '2026-08-18', 'published', 'Welcome to Zuvio Global School | Blog', 'Discover our digital-first school model launched in 2026, combining CBSE alignment with personalized pathways.');

-- 17. Enquiry Statuses
INSERT INTO `enquiry_statuses` (`id`, `name`) VALUES
(1, 'new'),
(2, 'contacted'),
(3, 'in_progress'),
(4, 'resolved'),
(5, 'archived');

-- 18. Navigation Menu Items
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES
(1, 'Home', '/', NULL, 1, 1),
(2, 'About Us', '/about-us', NULL, 2, 1),
(3, 'Our Curriculum', '/our-curriculum', NULL, 3, 1),
(4, 'Zuvio Beyond', '/zuvio-beyond', NULL, 4, 1),
(5, 'Blogs', '/blogs', NULL, 5, 1),
(6, 'Contact Us', '/contact-us', NULL, 6, 1);
