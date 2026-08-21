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
INSERT INTO `leadership` (`name`, `slug`, `designation`, `image`, `short_description`, `bio`, `message`, `sort_order`, `is_active`) VALUES
('Sharmin Habib', 'sharmin-habib', 'Co-Founder & Director', '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp', 
'Sharmin Habib is a seasoned educationist and edtech growth expert with over 18 years of experience. She has successfully founded and scaled preschools and digital K–8 learning models in domestic and international markets.',
'Sharmin Habib is a seasoned education, business development, growth, and expansion professional with over 18 years of experience across early childhood education, online schooling, EdTech, and strategic business growth.\n\nShe served as the Head of Business at The Himalayan School until April 2026, where she played an instrumental role in growing and scaling the school’s online education vertical. Her responsibilities spanned business strategy, student acquisition, admissions, partnerships, market expansion, team development, and strengthening the overall positioning of the school in the online education space.\n\nOver the years, Sharmin has developed extensive expertise in:\n\n• Online School Growth & Expansion – Building strategies to expand the reach and presence of online schooling across markets.\n\n• Business Development & Revenue Growth – Identifying new opportunities, developing growth channels, and driving sustainable business performance.\n\n• Admissions & Student Acquisition – Developing outreach, counselling, conversion, and enrolment strategies to strengthen admissions.\n\n• Strategic Partnerships – Building relationships with education organisations, institutions, communities, and other strategic partners.\n\n• Team Building & Leadership – Recruiting, mentoring, and leading cross-functional teams across academics, admissions, operations, and business development.\n\n• Market Expansion & Brand Positioning – Creating strategies to enter new markets and strengthen an education brand\'s competitive positioning.\n\n• Franchise Development – Extensive experience in developing and expanding education franchise networks in domestic and international markets.\n\n• Education Operations – Understanding the complete learner journey and aligning academic, operational, and business functions for effective delivery.\n\n• EdTech & Digital Learning – Strong experience in technology-enabled education and developing scalable digital learning models for K–8 learners.\n\n• Entrepreneurship & Institution Building – Experience conceptualising, launching, operating, and scaling education ventures from the ground up.\n\nAs the founder of Kindercare Services Pvt. Ltd. and I3 Education Pvt. Ltd., she has successfully led ventures in preschool education and digital K–8 learning. Her extensive experience also includes international business and franchise development, including her role as Global Franchisee Head at K12 Education.\n\nSharmin brings together education expertise, entrepreneurial thinking, strategic leadership, and hands-on business execution. Her ability to understand both the academic and commercial dimensions of education has enabled her to build teams, develop markets, strengthen enrolments, establish partnerships, and contribute to the growth and scalability of education organisations.',
'', 1, 1),

('Deepak Jain', 'deepak-jain', 'Co-Founder & Director', '/assets/images/Profile_Images/Deepak_Professional_Profile.webp',
'Deepak Jain is an entrepreneur and business professional who brings a strategic, systems-oriented perspective to Zuvio Global School. He oversees Zuvio’s strategic direction, operations, and partnerships for sustainable, long-term growth.',
'With a strong entrepreneurial mindset and experience in business, Deepak Jain brings a practical, strategic and growth-oriented perspective to Zuvio Global School.\n\nHis belief is that building a meaningful education platform requires more than a good academic model—it requires strong systems, responsible leadership, innovation and a clear understanding of the changing needs of families and children.\n\nAs Co-Founder, Deepak plays an important role in shaping Zuvio’s strategic direction, operations, partnerships and long-term growth, helping transform the vision of Zuvio into a sustainable and accessible education platform.\n\nHis vision is to help build an institution that combines the values of education with the possibilities of technology, creating a learning ecosystem that can grow with the needs of the next generation.',
'For Deepak, Zuvio is not simply about creating another school. It is about building an education platform for the future—one that can create meaningful opportunities for children, families and educators.', 2, 1),

('Pragya Jain', 'pragya-jain', 'Co-Founder & Director', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
'Pragya Jain is an educationist dedicated to child-centric learning that prepares students for life. She conceptualized Zuvio to merge academic rigor with personalization, creativity, confidence, and future-ready skills.',
'I have always believed that education should do more than prepare a child for examinations—it should prepare them for life, change and the possibilities of tomorrow.\n\nMy vision for Zuvio was born from a simple question: Can we create a learning environment where every child feels understood, encouraged and inspired to discover their own potential?\n\nZuvio is my endeavour to build an education experience that brings together strong academics, personalised learning, creativity, confidence and future-ready skills—while giving children the freedom to learn beyond traditional boundaries.\n\nI envision Zuvio as a school where learning is not limited to textbooks or classrooms, but becomes a continuous journey of curiosity, exploration, application and growth.\n\nEvery child learns differently. Every mind has possibilities. And every possibility deserves the opportunity to grow.',
'Every child learns differently. Every mind has possibilities. And every possibility deserves the opportunity to grow.', 3, 1),

('Rashmi Bhasin', 'rashmi-bhasin', 'Academic Head', '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp',
'Rashmi Bhasin is the Academic Head of Zuvio Global School. She is a visionary curriculum thinker and mentor committed to designing personalized, interactive, and child-centered online homeschooling experiences for Nursery to Grade 8.',
'As an educationist, my central role as an Academic Head would be in shaping the academic vision, learning culture, and educational philosophy of an online homeschooling programme. The role is not limited to curriculum management or academic administration; it is about understanding how children learn, identifying their individual needs, empowering teachers, and creating meaningful learning experiences that prepare students for both academic success and life beyond school.\n\nAs an Academic Head I would have a clear understanding of child-centred, personalised, experiential, and competency-based education. In an online homeschooling environment in Zuvio Global School, this becomes especially important because students have different abilities, learning styles, interests, and academic backgrounds. I would therefore create a flexible academic structure that would maintain common learning standards while allowing teachers to personalise instruction according to each child\'s needs.\n\nAs an educationist, I would lead the development of a curriculum that balances academic rigour with creativity, critical thinking, communication, collaboration, problem-solving, technology, life skills, and character development.\n\nAs an Academic Head I would also act as a mentor and academic leader for teachers. Teachers need more than subject knowledge; they need the ability to understand children, facilitate discussions, use technology effectively, design engaging learning experiences, assess learning meaningfully, and provide individual support. Through induction, training, mentoring, classroom observations, peer learning, and constructive feedback, I as an Academic Head would build a strong professional teaching culture.\n\nAn educationist also recognises that parents are important partners in a child\'s education. In online homeschooling, parents need clarity about the child\'s learning journey, progress, strengths, challenges, and ways they can provide support at home. The Academic Head would therefore create transparent and meaningful communication systems that build trust and encourage collaboration between teachers, parents, and students.\n\nMost importantly, being the Academic Head I would be like the guardian of the institution\'s educational philosophy who would ensure that every academic decision reflects the school\'s core values and contributes to the development of the whole child. The focus would be on creating learners who are curious, confident, responsible, adaptable, creative, and capable of thinking independently.\n\nIn essence, an Academic Head as an educationist is a visionary academic leader, curriculum thinker, teacher mentor, child advocate, and learning-culture builder who ensures that education remains purposeful, personalised, engaging, and future-ready while maintaining high academic standards.\n\nAbout the Curriculum and Academics\n\nAs an Academic Head of a Zuvio Global online homeschooling programme I would be responsible for providing strong academic leadership and building a structured, personalised, and future-ready learning environment for students. The role goes beyond managing curriculum and teachers; it involves creating an educational ecosystem where every child receives meaningful learning experiences, individual attention, continuous support, and opportunities to develop both academic and life skills.\n\nI as an Academic Head would be responsible for setting the overall academic vision and ensuring that this vision is consistently translated into classroom practice. My key responsibility as an Academic Head would be to design and oversee a well-structured curriculum from Nursery to Grade 8 for Zuvio Global School. The curriculum would maintain strong academic standards which would be flexible enough to accommodate different learning abilities, interests, learning styles, and individual needs. It would integrate core subjects with areas such as critical thinking, creativity, communication, collaboration, problem-solving, technology, coding, financial literacy, entrepreneurship, life skills, arts, wellness, and global awareness. The objective is to ensure that students are not only prepared for examinations but also equipped with the knowledge, skills, confidence, and adaptability required for the future.\n\nAs an Academic Head I would also be responsible for developing effective academic systems and processes. These include the academic calendar, annual academic planner, lesson-planning framework, assessment system, student progress tracking, teacher onboarding, academic SOPs, quality assurance mechanisms, and parent communication systems. Clear systems are particularly important in online homeschooling because students come from different academic backgrounds and require consistency, structure, and personalised support. Teacher development is another major responsibility.\n\nBeing an Academic Head I would recruit teachers for Zuvio Global School who would demonstrate strong subject knowledge, communication skills, empathy, digital competence, adaptability, and the ability to engage children online. Teachers would receive structured induction, training, mentoring, classroom observations, and regular feedback. The aim would not be to micromanage teachers but to create a culture of professional growth, accountability, collaboration, and continuous improvement.\n\nIn Zuvio Global online schooling, student engagement would be especially important. As an Academic Head I would ensure that classes are interactive rather than lecture-based. Teachers should use questioning, discussions, demonstrations, activities, projects, digital tools, real-world examples, and collaborative tasks to keep students actively involved. Class duration, screen time, breaks, asynchronous learning, and independent activities should be thoughtfully planned according to the age and developmental needs of students. Assessment should also move beyond marks and examinations. I as an Academic Head would establish a balanced assessment framework that would include formative assessments, quizzes, projects, presentations, portfolios, practical activities, competency-based assessments, self-assessment, and periodic summative assessments. Student progress would be tracked regularly so that learning gaps can be identified early and appropriate interventions can be provided.\n\nParent partnership is equally important in online homeschooling. In Zuvio Global School as an Academic Head I would establish transparent and meaningful communication with parents through regular progress updates, academic reports, feedback meetings, and clear guidance on how parents can support learning at home. Parents should understand not only what their child has achieved, but also their strengths, areas for improvement, learning habits, and next steps.\n\nUltimately, being an Academic Head I would build a culture where academic excellence and holistic development would go hand in hand. The goal is to create an online homeschooling experience where children feel supported, challenged, motivated, and confident. The success of the Academic Head should therefore be measured not only by academic results, but by how effectively the programme develops curious, independent, responsible, creative, and future-ready learners.',
'My vision as the Academic Head of Zuvio Global School is to build a future-ready, child-centred, and globally relevant learning ecosystem where academic excellence goes hand in hand with curiosity, creativity, character, and real-world skills. I envision Zuvio as a school where children are not simply prepared to score well in examinations, but are empowered to think independently, communicate confidently, solve problems, embrace technology, and become responsible global citizens.', 4, 1);

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

-- 19. Seed Zuvio Beyond Images into Media Table
INSERT INTO `media` (`file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `file_size`, `width`, `height`)
SELECT * FROM (
  SELECT '01_AI_Explorers.jpg' AS f, 'assets/images/Zuvio_Beyond_Website_Images/01_AI_Explorers.jpg' AS s, '/assets/images/Zuvio_Beyond_Website_Images/01_AI_Explorers.jpg' AS p, 'AI Explorers' AS a, 'image/jpeg' AS m, 179137 AS sz, 1200 AS w, 1200 AS h UNION ALL
  SELECT '02_Coding.jpg', 'assets/images/Zuvio_Beyond_Website_Images/02_Coding.jpg', '/assets/images/Zuvio_Beyond_Website_Images/02_Coding.jpg', 'Coding', 'image/jpeg', 159039, 1200, 1200 UNION ALL
  SELECT '03_Robotics.jpg', 'assets/images/Zuvio_Beyond_Website_Images/03_Robotics.jpg', '/assets/images/Zuvio_Beyond_Website_Images/03_Robotics.jpg', 'Robotics', 'image/jpeg', 297239, 1200, 1200 UNION ALL
  SELECT '04_Rubiks_Cube.jpg', 'assets/images/Zuvio_Beyond_Website_Images/04_Rubiks_Cube.jpg', '/assets/images/Zuvio_Beyond_Website_Images/04_Rubiks_Cube.jpg', 'Rubik\'s Cube', 'image/jpeg', 185391, 1200, 1200 UNION ALL
  SELECT '05_Abacus.jpg', 'assets/images/Zuvio_Beyond_Website_Images/05_Abacus.jpg', '/assets/images/Zuvio_Beyond_Website_Images/05_Abacus.jpg', 'Abacus', 'image/jpeg', 180540, 1200, 1200 UNION ALL
  SELECT '06_Vedic_Maths.jpg', 'assets/images/Zuvio_Beyond_Website_Images/06_Vedic_Maths.jpg', '/assets/images/Zuvio_Beyond_Website_Images/06_Vedic_Maths.jpg', 'Vedic Maths', 'image/jpeg', 167855, 1200, 1200 UNION ALL
  SELECT '07_Financial_Literacy.jpg', 'assets/images/Zuvio_Beyond_Website_Images/07_Financial_Literacy.jpg', '/assets/images/Zuvio_Beyond_Website_Images/07_Financial_Literacy.jpg', 'Financial Literacy', 'image/jpeg', 177100, 1200, 1200 UNION ALL
  SELECT '08_Entrepreneurship.jpg', 'assets/images/Zuvio_Beyond_Website_Images/08_Entrepreneurship.jpg', '/assets/images/Zuvio_Beyond_Website_Images/08_Entrepreneurship.jpg', 'Entrepreneurship', 'image/jpeg', 206610, 1200, 1200 UNION ALL
  SELECT '09_Chess.jpg', 'assets/images/Zuvio_Beyond_Website_Images/09_Chess.jpg', '/assets/images/Zuvio_Beyond_Website_Images/09_Chess.jpg', 'Chess', 'image/jpeg', 175309, 1200, 1200 UNION ALL
  SELECT '10_Digital_Media_and_Arts.jpg', 'assets/images/Zuvio_Beyond_Website_Images/10_Digital_Media_and_Arts.jpg', '/assets/images/Zuvio_Beyond_Website_Images/10_Digital_Media_and_Arts.jpg', 'Digital Media & Arts', 'image/jpeg', 173146, 1200, 1200 UNION ALL
  SELECT '11_Dance.jpg', 'assets/images/Zuvio_Beyond_Website_Images/11_Dance.jpg', '/assets/images/Zuvio_Beyond_Website_Images/11_Dance.jpg', 'Dance', 'image/jpeg', 151212, 1200, 1200 UNION ALL
  SELECT '12_Art_and_Craft.jpg', 'assets/images/Zuvio_Beyond_Website_Images/12_Art_and_Craft.jpg', '/assets/images/Zuvio_Beyond_Website_Images/12_Art_and_Craft.jpg', 'Art & Craft', 'image/jpeg', 186219, 1200, 1200 UNION ALL
  SELECT '13_Academic_Support_and_Enrichment.jpg', 'assets/images/Zuvio_Beyond_Website_Images/13_Academic_Support_and_Enrichment.jpg', '/assets/images/Zuvio_Beyond_Website_Images/13_Academic_Support_and_Enrichment.jpg', 'Academic Support & Enrichment', 'image/jpeg', 222857, 1200, 1200 UNION ALL
  SELECT 'Sharmin_Professional_Profile.webp', 'assets/images/Profile_Images/Sharmin_Professional_Profile.webp', '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp', 'Sharmin Habib', 'image/webp', 83320, 800, 800 UNION ALL
  SELECT 'Deepak_Professional_Profile.webp', 'assets/images/Profile_Images/Deepak_Professional_Profile.webp', '/assets/images/Profile_Images/Deepak_Professional_Profile.webp', 'Deepak Jain', 'image/webp', 70294, 800, 800 UNION ALL
  SELECT 'Pragya_Professional_Profile.webp', 'assets/images/Profile_Images/Pragya_Professional_Profile.webp', '/assets/images/Profile_Images/Pragya_Professional_Profile.webp', 'Pragya Jain', 'image/webp', 78844, 800, 800 UNION ALL
  SELECT 'Rashmi_Professional_Profile.webp', 'assets/images/Profile_Images/Rashmi_Professional_Profile.webp', '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp', 'Rashmi Bhasin', 'image/webp', 66340, 800, 800
) AS tmp
WHERE NOT EXISTS (
  SELECT 1 FROM `media` WHERE `storage_path` = tmp.s
);
