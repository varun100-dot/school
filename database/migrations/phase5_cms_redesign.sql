-- Zuvio Global School - Phase 5 CMS Redesign Migration
-- Safe, idempotent SQL script for MySQL

-- 1. Create or Update Homepage Sections Table
CREATE TABLE IF NOT EXISTS `homepage_sections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `section_key` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` VARCHAR(255) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `video` VARCHAR(255) DEFAULT NULL,
  `cta_text` VARCHAR(100) DEFAULT NULL,
  `cta_url` VARCHAR(255) DEFAULT NULL,
  `cta_enabled` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Create Homepage Cards Table (Generic / Multi-purpose)
CREATE TABLE IF NOT EXISTS `homepage_cards` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `section_key` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` VARCHAR(255) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `link_url` VARCHAR(255) DEFAULT NULL,
  `badge_text` VARCHAR(100) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`section_key`),
  INDEX (`sort_order`)
);

-- 3. Create Parent Testimonials Table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `parent_name` VARCHAR(150) NOT NULL,
  `child_info` VARCHAR(150) DEFAULT NULL,
  `review_text` TEXT NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `rating` TINYINT DEFAULT 5,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 4. Create Parent FAQs Table (All 18 PDF Questions)
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `question` TEXT NOT NULL,
  `answer` LONGTEXT NOT NULL,
  `category` VARCHAR(100) DEFAULT 'General',
  `is_featured_home` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. Create Accreditations & Affiliations Table
CREATE TABLE IF NOT EXISTS `accreditations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `subtitle` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `certificate_url` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 6. Seed Hierarchical Navigation Items
DELETE FROM `navigation_items` WHERE `id` > 0;

-- Top-level navigation items
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES
(1, 'Home', '/', NULL, 1, 1),
(2, 'About Us', '/about', NULL, 2, 1),
(3, 'Academics', '/academics', NULL, 3, 1),
(4, 'Admissions', '/admissions', NULL, 4, 1),
(5, 'Beyond', '/beyond', NULL, 5, 1),
(6, 'Contact Us', '/contact', NULL, 6, 1);

-- Sub-items for About Us (parent_id = 2)
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES
(10, 'About Zuvio', '/about#about-zuvio', 2, 1, 1),
(11, 'Our Team', '/about#leadership', 2, 2, 1),
(12, 'Founder’s Message', '/founder-message', 2, 3, 1),
(13, 'Affiliations & Accreditations', '/about#accreditations', 2, 4, 1);

-- Sub-items for Academics (parent_id = 3)
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES
(20, 'Technology', '/academics#technology', 3, 1, 1),
(21, 'Curriculum', '/curriculum', 3, 2, 1),
(22, 'Special Education', '/academics#special-education', 3, 3, 1),
(23, 'Electives', '/academics#electives', 3, 4, 1),
(24, 'NEP 2020', '/academics#nep-2020', 3, 5, 1),
(25, 'Resources', '/academics#resources', 3, 6, 1);

-- Sub-items for Admissions (parent_id = 4)
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES
(30, 'Enrol Now', '/admissions#enrol', 4, 1, 1),
(31, 'Eligibility', '/admissions#eligibility', 4, 2, 1),
(32, 'Calendar', '/admissions#calendar', 4, 3, 1),
(33, 'Fees', '/admissions#fees', 4, 4, 1),
(34, 'FAQ', '/faq', 4, 5, 1);

-- Sub-items for Beyond (parent_id = 5)
INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES
(40, 'Co-curricular / Clubs', '/beyond#co-curricular', 5, 1, 1),
(41, 'Student Achievers', '/beyond#achievers', 5, 2, 1),
(42, 'Gallery', '/beyond#gallery', 5, 3, 1);

-- 7. Seed All 18 Parent FAQs (Verbatim from Zuvio_Parent_FAQ_Online_Schooling.pdf)
INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `is_featured_home`, `sort_order`, `is_active`) VALUES
(1, 'What are the timings of online classes?', 'Zuvio follows age-appropriate class durations. Classes are scheduled within the 9:00 AM-1:00 PM window, depending on the grade.\n\nPre-Primary: approximately 2 hours\nPrimary: approximately 2.5 hours\nMiddle School: approximately 3 hours\n\nThe timetable includes suitable breaks, interactive activities and a balanced mix of teacher-led and student-led learning.', 'Schedules & Structure', 1, 1, 1),

(2, 'How does online schooling work at Zuvio?', 'Zuvio Global School is a 100% online school where students attend structured live classes with teachers from home. Students follow a planned timetable and academic calendar and receive learning resources, assignments, projects, assessments and teacher support through the school''s digital learning ecosystem.', 'Online Schooling Model', 1, 2, 1),

(3, 'Is the Zuvio curriculum CBSE-mapped?', 'Yes. Zuvio''s academic curriculum is mapped to CBSE learning outcomes and designed with reference to NEP 2020 and NCF guidelines. This supports grade-appropriate concepts, competencies and structured academic progression.', 'Curriculum & Pedagogy', 1, 3, 1),

(4, 'What is the Oxford theme-based curriculum approach?', 'Zuvio incorporates an Oxford-based thematic learning approach, where concepts are connected through meaningful themes instead of always being taught as isolated topics. A theme can bring together language, environmental studies, creativity, research, communication and real-world applications, helping children understand connections across subjects.', 'Curriculum & Pedagogy', 1, 4, 1),

(5, 'What is Project-Based Learning?', 'Project-Based Learning allows students to learn by doing. Students explore a question, challenge or real-life topic and create a project, presentation, model, report or solution. This helps develop critical thinking, creativity, communication, research, collaboration and problem-solving skills.', 'Curriculum & Pedagogy', 1, 5, 1),

(6, 'How is Artificial Intelligence integrated into the curriculum?', 'AI learning is introduced in an age-appropriate manner as part of Zuvio''s future-ready learning approach. Students gradually learn AI concepts, responsible use of technology, problem-solving and practical applications. Zuvio''s AI learning approach uses IBM-supported/certified learning resources and frameworks, subject to the applicable programme and certification requirements.', 'Technology & AI', 1, 6, 1),

(7, 'How are examinations and assessments conducted?', 'Assessment includes formative assessments, projects, assignments, class participation, portfolios and summative assessments. Formal assessments are conducted online according to school examination guidelines. For Nursery-UKG, assessment is primarily activity- and observation-based rather than dependent on formal written examinations. Parents receive regular feedback and progress reports.', 'Assessments & Examinations', 1, 7, 1),

(8, 'Can parents speak directly with teachers?', 'Yes. Parents can communicate with teachers and the academic team through WhatsApp groups, email, scheduled virtual meetings and Parent-Teacher Meetings (PTMs), depending on the requirement.', 'Parent-Teacher Communication', 1, 8, 1),

(9, 'What is NIOS, and how does it relate to online schooling?', 'The National Institute of Open Schooling (NIOS) provides recognised open-schooling pathways in India. Where applicable, families seeking a formal open-schooling certification pathway can explore NIOS according to its prevailing eligibility, registration and examination requirements.\n\nImportant: NIOS and a CBSE-mapped curriculum are not the same. Curriculum mapping describes what and how a student learns, while NIOS is an examination/certification pathway.', 'Boards & Pathways', 1, 9, 1),

(10, 'Can my child move back to an offline school later?', 'Yes, students can transition from online learning to an offline school. Admission is governed by the receiving school''s admission policy, documentation, grade-level requirements and applicable board/regulatory rules. Zuvio can support parents with relevant academic records and progress documentation.', 'Transition & Portability', 1, 10, 1),

(11, 'Are co-curricular activities included in online schooling?', 'Yes. Co-curricular learning is planned in sync with the academic curriculum. Activities may include art, communication, storytelling, STEM, innovation, digital skills, presentations, competitions, projects and other age-appropriate enrichment experiences. The aim is to support academic, creative, social and emotional development.', 'Beyond Academics', 1, 11, 1),

(12, 'Will children get enough interaction in an online school?', 'Yes. Live classes can include discussions, quizzes, presentations, show-and-tell, collaborative activities, projects, peer interaction and teacher questioning. Students are encouraged to actively participate rather than simply watch a screen.', 'Student Life & Interaction', 1, 12, 1),

(13, 'How is student progress monitored?', 'Teachers monitor progress through classroom participation, assignments, projects, quizzes, assessments and portfolios. Regular reviews help identify learning gaps and determine where additional academic support may be required.', 'Student Support & Monitoring', 1, 13, 1),

(14, 'What happens if my child is struggling with a concept?', 'Teachers can identify learning gaps through continuous assessment and classroom interaction. Students may receive additional clarification, practice material and academic support according to their learning needs.', 'Student Support & Monitoring', 1, 14, 1),

(15, 'How much screen time will my child have?', 'Zuvio follows grade-appropriate live-class durations. Online lessons are complemented by offline activities such as reading, writing, worksheets, projects, art, research, experiments and hands-on learning. The objective is not to keep children continuously in front of a screen.', 'Well-being & Balance', 1, 15, 1),

(16, 'Is online schooling suitable for children living outside India?', 'Online schooling can be particularly useful for NRI and internationally mobile families seeking continuity in learning while living abroad or moving between countries. Families should separately check compulsory-schooling and recognition requirements applicable in their country of residence.', 'International & NRI Families', 1, 16, 1),

(17, 'What support is provided to students joining in Term 2?', 'Students joining mid-session can undergo a baseline/diagnostic assessment to understand their current learning level. Teachers can then identify gaps and provide a bridge learning plan to support a smooth transition into the ongoing curriculum.', 'Admissions & Transitions', 1, 17, 1),

(18, 'What makes Zuvio''s online learning approach different?', 'Zuvio combines structured academics, CBSE-mapped learning, thematic learning, project-based education, AI and digital skills, experiential learning, co-curricular activities and regular parent-teacher interaction in a flexible online environment. The focus is on developing confident, independent and future-ready learners.\n\nNote: Programme, curriculum, certification, examination and progression details are subject to applicable school policies and external provider/regulatory requirements. Partnership and certification references should be read according to the specific programme applicable to the learner.', 'Distinctive Approach', 1, 18, 1)
ON DUPLICATE KEY UPDATE `question` = VALUES(`question`), `answer` = VALUES(`answer`), `category` = VALUES(`category`);

-- 8. Seed Accreditations & Affiliations
INSERT INTO `accreditations` (`id`, `name`, `subtitle`, `description`, `logo`, `certificate_url`, `sort_order`, `is_active`) VALUES
(1, 'ISSO — International Schools Sports Organisation', 'Building Champions Beyond the Classroom', 'Through its association with ISSO, Zuvio aims to provide learners access to a structured school-sports ecosystem that promotes competition, teamwork, discipline, resilience and sporting excellence. ISSO connects international-curriculum schools and student-athletes through organised multi-sport opportunities and competitive pathways.', '/assets/images/isso-logo.png', 'https://www.issosports.org/', 1, 1),

(2, 'IAO — International Accreditation Organization', 'Committed to Global Quality Standards', 'Zuvio’s association with IAO reflects our focus on quality, continuous improvement and internationally benchmarked educational practices. IAO provides quality-assurance and accreditation services to educational institutions, including online and distance-learning providers.', '/assets/images/iao-logo.png', 'https://www.iao.org/India-Delhi/Zuvio-Global-School', 2, 1),

(3, 'Oxford Quality', 'Powered by the Excellence of Oxford University Press', 'As part of the Oxford Quality community, Zuvio strengthens learning through high-quality educational resources, teacher professional development and globally connected learning opportunities. Oxford Quality is an Oxford University Press programme designed to support institutions committed to continuously developing their teaching, learning methods and resources.', '/assets/images/oxford-logo.png', 'https://india.oup.com/', 3, 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `subtitle` = VALUES(`subtitle`), `description` = VALUES(`description`), `certificate_url` = VALUES(`certificate_url`);

-- 9. Seed Parent Testimonials
INSERT INTO `testimonials` (`id`, `parent_name`, `child_info`, `review_text`, `photo`, `rating`, `sort_order`, `is_active`) VALUES
(1, 'Priya & Rajesh Sharma', 'Parents of Aarav (Grade 4)', 'Transitioning to Zuvio Global School was the best decision for our son. The live teachers are incredibly engaging, and the Oxford theme-based curriculum connects concepts in a way that actually makes sense to him. He loves waking up for his classes!', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp', 5, 1, 1),
(2, 'Dr. Anandita Sen', 'Mother of Rhea (Grade 7)', 'As a family that frequently relocates between cities, Zuvio gave us uninterrupted, high-quality schooling. The coding, AI integration, and project-based approach ensure she stays far ahead of traditional schooling standards.', '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp', 5, 2, 1),
(3, 'Kavita & Vikram Mehta', 'Parents of Kabir (Kindergarten)', 'The Early Years programme is simply fantastic. The teachers use stories, music, and interactive activities that keep our 5-year-old engaged without excessive screen fatigue. Highly recommended for alternative learning!', '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp', 5, 3, 1)
ON DUPLICATE KEY UPDATE `parent_name` = VALUES(`parent_name`), `review_text` = VALUES(`review_text`);

-- 10. Seed Homepage Sections Content
INSERT INTO `homepage_sections` (`section_key`, `title`, `subtitle`, `content`, `image`, `video`, `cta_text`, `cta_url`, `cta_enabled`, `sort_order`, `is_active`) VALUES
('about_short', 'About Zuvio', 'Learning Beyond Boundaries', 'Zuvio Global School is an online school built on one belief: education should adapt to the child, not the child to the system.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere. Different ways of learning. One community. Equal opportunities.\n\nThat''s what learning beyond boundaries means.', '/assets/images/about_us_hero.jpg', NULL, 'Explore Our Story', '/about', 1, 1, 1),

('who_should_choose', 'Who Should Choose Zuvio', 'Tailored for Modern Learners', 'Zuvio is for families who want learning to fit their life — not their life to revolve around a timetable.\n\nIf you believe education should adapt to the child, Zuvio may be the right choice.', NULL, NULL, 'Check Eligibility', '/admissions#eligibility', 1, 2, 1),

('curriculum_intro', 'A Future-Ready Learning Journey — K to Grade 8', 'Our Academic Pathway', 'Aligned with CBSE, NEP 2020 and NCF — strong academic foundations blended with creativity, communication, digital fluency and real-world learning.\n\nEvery stage builds on the last: from stories, sounds and play in the Early Years to research, innovation and independent thinking in Middle School.', NULL, NULL, 'Explore the Full Curriculum →', '/curriculum', 1, 3, 1),

('learning_framework', 'The Zuvio Learning Framework', 'From Knowing to Doing', 'Knowledge → Understanding → Application → Innovation\n\nOur five-pillar framework bridges conceptual foundation with real-world application.', NULL, NULL, 'Learn About Our Methodology', '/academics', 1, 4, 1),

('beyond_textbook', 'Learning Beyond the Textbook', 'Because the world is the real classroom', 'Education that extends past the limits of written books into practical experimentation, global conversations, and modern creative technology.', NULL, NULL, 'Discover Co-Curriculars', '/beyond', 1, 5, 1),

('why_different', 'What Makes Zuvio Different', 'Distinctive by Design', 'We don''t just teach syllabus topics. We cultivate the habits of mind and adaptable skills needed for lifelong flourishing.', NULL, NULL, 'Compare Our Approach', '/academics', 1, 6, 1),

('inclusivity', 'Inclusivity & Beyond', 'Every Child. Every Mind. Every Possibility.', 'Learning built around how your child learns — not the other way round. A caring, small-group live ecosystem where diverse learning styles thrive.', NULL, NULL, 'Special Education Support', '/academics#special-education', 1, 7, 1),

('founder_message', 'Learning Without Boundaries. Growing With Purpose.', 'Founder’s Message', 'Dear Parents, Students and Members of the Zuvio Community,\n\nEducation today must prepare children not only for examinations, but for a world that is constantly evolving.\n\nAt Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.\n\nOur vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.\n\nAt Zuvio, strong academics form the foundation, but learning goes much further. We encourage our students to question, think critically, communicate confidently, collaborate, create and apply their knowledge to real-world situations. Technology enables our classrooms, but teachers, relationships and meaningful human interaction remain at the heart of the learning experience.\n\nWe recognise that every child is different. Their interests, abilities, pace and aspirations are unique. Our approach therefore aims to create a learning journey that gives students the flexibility to discover their strengths while developing the knowledge, skills and values required for the future.\n\nWe also believe education is a partnership. Parents, educators and students must work together to create an environment in which children feel supported, inspired and empowered to take ownership of their learning.\n\nZuvio Global School is not simply about bringing a traditional classroom online. We are reimagining how learning can happen when boundaries are removed and possibilities are expanded.\n\nOur aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.\n\nWelcome to Zuvio Global School — a global learning community where every child is encouraged to learn, explore, create and grow without boundaries.\n\nWarm regards,\nFounder\nZuvio Global School', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp', NULL, 'Read Her Story', '/founder-message', 1, 8, 1)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `subtitle` = VALUES(`subtitle`), `content` = VALUES(`content`);

-- 11. Seed Cards for Learning Journey, Framework, Beyond Textbook, and Inclusivity
INSERT INTO `homepage_cards` (`section_key`, `title`, `subtitle`, `content`, `badge_text`, `sort_order`, `is_active`) VALUES
-- 4 Stage Learning Journey Cards
('learning_journey', 'Early Years · K–KG', 'Explore • Play • Discover', 'Learning through stories, play, music and hands-on activities\n• Early literacy, phonics and numeracy\n• Communication, creativity and social-emotional growth\n• Outcome: Confidence, curiosity and strong foundations', 'K–KG', 1, 1),
('learning_journey', 'Foundation Stage · Grades 1–2', 'Build • Question • Create', 'Strengthening reading, writing and maths\n• Connecting classroom concepts to everyday life\n• Art, life skills and digital literacy\n• Outcome: Numeracy, communication and independent thinking', 'Grades 1–2', 2, 1),
('learning_journey', 'Preparatory Stage · Grades 3–5', 'Understand • Apply • Collaborate', 'Interdisciplinary, application-led learning\n• Science, coding and computational thinking\n• Communication, financial awareness and creativity\n• Outcome: Conceptual understanding, research and digital fluency', 'Grades 3–5', 3, 1),
('learning_journey', 'Middle School · Grades 6–8', 'Think • Apply • Innovate', 'Deeper analysis, research and discussion\n• Coding & AI awareness, entrepreneurship, design thinking\n• Leadership, global citizenship and career exploration\n• Outcome: Critical thinking, independence and real-world readiness', 'Grades 6–8', 4, 1),

-- 6 Who Should Choose Zuvio Cards (Section 4.2 of DOCX)
('who_should_choose', 'Globally Mobile Families', 'Moving Across Borders', 'Families who move between cities or countries and require uninterrupted academic continuity.', 'Global', 1, 1),
('who_should_choose', 'Homeschooling & Alternative Learners', 'Freedom with Structure', 'Homeschooling and alternative-learning families who want curriculum structure with daily freedom.', 'Flexible', 2, 1),
('who_should_choose', 'Young Athletes, Artists & Performers', 'Demanding Schedules', 'Talented young sports champions, musicians, and artists balancing rigorous training with high academic standards.', 'Talent', 3, 1),
('who_should_choose', 'Children Who Thrive Online', 'Digital Learners', 'Learners who excel in an interactive digital environment and learn best through modern technology.', 'Digital-First', 4, 1),
('who_should_choose', 'Personalised Approach Seekers', 'Individual Attention', 'Learners who need a more personalised approach, customized pacing, or 1:1 teacher attention.', 'Pacing', 5, 1),
('who_should_choose', 'Different Schooling Environment', 'Caring Alternative', 'Children who need a different schooling environment when traditional brick-and-mortar schools aren''t the right fit.', 'Support', 6, 1),

-- 5 Learning Framework Pillars (Section 5.3 of DOCX)
('learning_framework', 'Know', 'Foundation', 'Build the foundation of essential concepts and ideas.', 'Step 01', 1, 1),
('learning_framework', 'Think', 'Analysis & Reason', 'Question, analyse, reason and solve.', 'Step 02', 2, 1),
('learning_framework', 'Create', 'Design & Innovation', 'Imagine, experiment, design and innovate.', 'Step 03', 3, 1),
('learning_framework', 'Connect', 'Collaboration', 'Communicate, collaborate and understand other perspectives.', 'Step 04', 4, 1),
('learning_framework', 'Apply', 'Real-World Doing', 'Use knowledge confidently in projects and real life.', 'Step 05', 5, 1),

-- 6 Learning Beyond Textbook Cards (Section 5.4 of DOCX)
('beyond_textbook', 'Projects & Experiments', 'Hands-On Discovery', 'learning by doing, testing and discovering', 'Practical', 1, 1),
('beyond_textbook', 'Technology & Digital Learning', 'Future Fluency', 'used creatively and responsibly', 'Future-Ready', 2, 1),
('beyond_textbook', 'Communication & Collaboration', 'Expressive Confidence', 'presentations, teamwork, global interaction', 'Core Skill', 3, 1),
('beyond_textbook', 'Life Skills', 'Independence & Ownership', 'decision-making, independence and financial awareness', 'Life Prep', 4, 1),
('beyond_textbook', 'Creativity & Innovation', 'Art & Computation', 'art, coding and design thinking', 'Innovation', 5, 1),
('beyond_textbook', 'Global Exposure', 'Boundless Horizons', 'cultures and ideas beyond boundaries', 'Global', 6, 1),

-- 6 Inclusivity Value Propositions
('inclusivity', 'Inclusive by Design', 'Adaptive Learning', 'Learning built around how your child learns — not the other way round.', 'Adaptive', 1, 1),
('inclusivity', 'Learn From Anywhere', 'Mobile Schooling', 'A complete school experience that moves with your family, across cities or countries.', 'Portable', 2, 1),
('inclusivity', 'Personalised Attention', 'Small Group Format', 'Teacher-led, small-group classes where every child is known, seen and supported.', 'Personal', 3, 1),
('inclusivity', 'Flexible Pacing', 'Mastery-Focused', 'Space to move ahead, slow down or revisit — without the pressure to keep up.', 'Flexible', 4, 1),
('inclusivity', 'Beyond Academics', 'Holistic Flourishing', 'Confidence, communication, creativity and life skills — not just examination marks.', 'Holistic', 5, 1),
('inclusivity', 'Special Education Support', 'Specialized Care', 'A qualified Special Educator and personalised plans for diverse learning needs and styles.', 'Support', 6, 1);

-- 12. Seed Approved Accreditations (Section 7.2 of DOCX & content.docx)
DELETE FROM `accreditations` WHERE `id` > 0;
INSERT INTO `accreditations` (`id`, `name`, `subtitle`, `description`, `logo`, `certificate_url`, `sort_order`, `is_active`) VALUES
(1, 'IAO — International Accreditation Organization', 'Committed to Global Quality Standards', 'Zuvio’s association with IAO reflects our focus on quality, continuous improvement and internationally benchmarked educational practices. IAO provides quality-assurance and accreditation services to educational institutions, including online and distance-learning providers.', '/assets/images/iao-logo.png', 'https://www.iao.org/India-Delhi/Zuvio-Global-School', 1, 1),
(2, 'Oxford Quality', 'Powered by the Excellence of Oxford University Press', 'As part of the Oxford Quality community, Zuvio strengthens learning through high-quality educational resources, teacher professional development and globally connected learning opportunities. Oxford Quality is an Oxford University Press programme designed to support institutions committed to continuously developing their teaching, learning methods and resources.', '/assets/images/oxford-logo.png', 'https://india.oup.com/', 2, 1),
(3, 'ISSO — International Schools Sports Organisation', 'Building Champions Beyond the Classroom', 'Through its association with ISSO, Zuvio aims to provide learners access to a structured school-sports ecosystem that promotes competition, teamwork, discipline, resilience and sporting excellence. ISSO connects international-curriculum schools and student-athletes through organised multi-sport opportunities and competitive pathways.', '/assets/images/isso-logo.png', 'https://www.issosports.org/', 3, 1);

-- 12. Register Permissions for Phase 5 Modules
INSERT IGNORE INTO `permissions` (`name`, `description`) VALUES
('homepage.view', 'View homepage CMS manager'),
('homepage.edit', 'Edit homepage sections and cards'),
('faqs.view', 'View parent FAQs'),
('faqs.create', 'Create parent FAQ'),
('faqs.edit', 'Edit parent FAQ'),
('faqs.delete', 'Delete parent FAQ'),
('testimonials.view', 'View testimonials'),
('testimonials.create', 'Create testimonial'),
('testimonials.edit', 'Edit testimonial'),
('testimonials.delete', 'Delete testimonial'),
('accreditations.view', 'View accreditations'),
('accreditations.edit', 'Edit accreditations and certificate links');

-- Map permissions to super_admin and admin
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r CROSS JOIN `permissions` p 
WHERE r.name IN ('super_admin', 'admin') AND (p.name LIKE 'homepage.%' OR p.name LIKE 'faqs.%' OR p.name LIKE 'testimonials.%' OR p.name LIKE 'accreditations.%');
