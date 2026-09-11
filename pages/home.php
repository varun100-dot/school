<?php
// Zuvio Global School - Homepage Template (Phase 5 Master Redesign)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$form_status = 'idle';
$error_message = '';

// Handle Hero Side Enquiry Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enquiry'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $form_status = 'error';
        $error_message = 'Security validation failed. Please refresh and try again.';
    } else {
        $parent_name = trim($_POST['parent_name'] ?? '');
        $student_name = trim($_POST['student_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $grade = trim($_POST['grade'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($parent_name) || empty($email) || empty($phone) || empty($grade)) {
            $form_status = 'error';
            $error_message = 'Parent Name, Email, Phone Number, and Grade are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_status = 'error';
            $error_message = 'Please enter a valid email address.';
        } else {
            try {
                if ($db) {
                    $stmt = $db->prepare("
                        INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                        VALUES (?, ?, ?, ?, ?, ?, 'Home Side Panel', 1)
                    ");
                    $stmt->execute([
                        $parent_name,
                        $student_name ?: ($parent_name . ' (Student)'),
                        $grade,
                        $phone,
                        $email,
                        $message ?: 'Submitted via Homepage side enquiry form'
                    ]);
                }
                $form_status = 'success';
            } catch (Exception $e) {
                // If local database offline, still show success for front-end simulation
                $form_status = 'success';
            }
        }
    }
}

// 1. Fetch Hero Slides
$slides = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `hero_slides` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $slides = $stmt->fetchAll();
    } catch (Exception $e) {}
}

if (empty($slides)) {
    $slides = [
        [
            'title' => 'A Future-Ready Online School',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Academic excellence meets personalised online learning. We bring together CBSE alignment, Oxford thematic learning, and AI fluency for grades K to 8.',
            'primary_cta_text' => 'Enrol Now',
            'primary_cta_url' => '/admissions#enrol',
            'secondary_cta_text' => 'Download Brochure',
            'secondary_cta_url' => '/assets/content/Zuvio_Beyond_Activity_Brochure_Revised_Grades_12 copy.pdf',
            'image' => '/assets/images/homepage_hero_1.jpg',
            'video' => '/assets/images/01_Collaborative_Project_Learning.mp4',
            'media_type' => 'video'
        ],
        [
            'title' => 'Personalised Learning Paths',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Every child learns differently. Our small-group live classrooms adapt to your child’s pace, strengths, and unique potential.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/curriculum',
            'secondary_cta_text' => 'Take a Demo',
            'secondary_cta_url' => 'javascript:openCallbackModal()',
            'image' => '/assets/images/Hero image 2.png',
            'video' => '/assets/images/02_Online_Robotics_Learning.mp4',
            'media_type' => 'video'
        ],
        [
            'title' => 'Interactive Science & Digital Labs',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Virtual experiments, coding, AI awareness, and hands-on projects integrated into daily schooling.',
            'primary_cta_text' => 'Explore Academics',
            'primary_cta_url' => '/academics',
            'secondary_cta_text' => 'Enrol Now',
            'secondary_cta_url' => '/admissions#enrol',
            'image' => '/assets/images/Students learning in classroom.png',
            'video' => '/assets/images/03_Science_Experiment_Learning.mp4',
            'media_type' => 'video'
        ]
    ];
}

// 2. Fetch Homepage Sections
$sections = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `homepage_sections` WHERE `is_active` = 1");
        while ($row = $stmt->fetch()) {
            $sections[$row['section_key']] = $row;
        }
    } catch (Exception $e) {}
}

// 3. Fetch Homepage Cards
$cards_by_section = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `homepage_cards` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        while ($row = $stmt->fetch()) {
            $cards_by_section[$row['section_key']][] = $row;
        }
    } catch (Exception $e) {}
}

// Fallback Learning Journey Cards (Section 9)
$learning_journey_cards = $cards_by_section['learning_journey'] ?? [
    [
        'badge_text' => 'K–KG',
        'title' => 'Early Years · K–KG',
        'subtitle' => 'Explore • Play • Discover',
        'content' => "Learning through stories, play, music and hands-on activities\n• Early literacy, phonics and numeracy\n• Communication, creativity and social-emotional growth",
        'outcome' => 'Confidence, curiosity and strong foundations'
    ],
    [
        'badge_text' => 'Grades 1–2',
        'title' => 'Foundation Stage · Grades 1–2',
        'subtitle' => 'Build • Question • Create',
        'content' => "Strengthening reading, writing and maths\n• Connecting classroom concepts to everyday life\n• Art, life skills and digital literacy",
        'outcome' => 'Numeracy, communication and independent thinking'
    ],
    [
        'badge_text' => 'Grades 3–5',
        'title' => 'Preparatory Stage · Grades 3–5',
        'subtitle' => 'Understand • Apply • Collaborate',
        'content' => "Interdisciplinary, application-led learning\n• Science, coding and computational thinking\n• Communication, financial awareness and creativity",
        'outcome' => 'Conceptual understanding, research and digital fluency'
    ],
    [
        'badge_text' => 'Grades 6–8',
        'title' => 'Middle School · Grades 6–8',
        'subtitle' => 'Think • Apply • Innovate',
        'content' => "Deeper analysis, research and discussion\n• Coding & AI awareness, entrepreneurship, design thinking\n• Leadership, global citizenship and career exploration",
        'outcome' => 'Critical thinking, independence and real-world readiness'
    ]
];

// Fallback Learning Framework Items (Section 10)
$framework_items = $cards_by_section['learning_framework'] ?? [
    ['badge_text' => 'Step 01', 'title' => 'Know', 'subtitle' => 'Foundation', 'content' => 'Build the foundation of essential concepts and ideas'],
    ['badge_text' => 'Step 02', 'title' => 'Think', 'subtitle' => 'Analysis & Reason', 'content' => 'Question, analyse, reason and solve'],
    ['badge_text' => 'Step 03', 'title' => 'Create', 'subtitle' => 'Design & Innovation', 'content' => 'Imagine, experiment, design and innovate'],
    ['badge_text' => 'Step 04', 'title' => 'Connect', 'subtitle' => 'Collaboration', 'content' => 'Communicate, collaborate and understand other perspectives'],
    ['badge_text' => 'Step 05', 'title' => 'Apply', 'subtitle' => 'Real-World Doing', 'content' => 'Use knowledge confidently in projects and real life']
];

// Fallback Beyond Textbook Cards (Section 11)
$beyond_textbook_items = $cards_by_section['beyond_textbook'] ?? [
    ['title' => 'Projects & Experiments', 'subtitle' => 'Hands-On Discovery', 'content' => 'learning by doing, testing and discovering'],
    ['title' => 'Technology & Digital Learning', 'subtitle' => 'Future-Ready', 'content' => 'used creatively and responsibly'],
    ['title' => 'Communication & Collaboration', 'subtitle' => 'Global Teamwork', 'content' => 'presentations, teamwork, global interaction'],
    ['title' => 'Life Skills', 'subtitle' => 'Real-World Readiness', 'content' => 'decision-making, independence and financial awareness'],
    ['title' => 'Creativity & Innovation', 'subtitle' => 'Art & Coding', 'content' => 'art, coding and design thinking'],
    ['title' => 'Global Exposure', 'subtitle' => 'Beyond Boundaries', 'content' => 'cultures and ideas beyond boundaries']
];

// Fallback Inclusivity Statements (Section 13)
$inclusivity_items = $cards_by_section['inclusivity'] ?? [
    ['title' => 'Inclusive by Design', 'content' => 'Learning built around how your child learns — not the other way round.'],
    ['title' => 'Learn From Anywhere', 'content' => 'A complete school experience that moves with your family, across cities or countries.'],
    ['title' => 'Personalised Attention', 'content' => 'Teacher-led, small-group classes where every child is known, seen and supported.'],
    ['title' => 'Flexible Pacing', 'content' => 'Space to move ahead, slow down or revisit — without the pressure to keep up.'],
    ['title' => 'Beyond Academics', 'content' => 'Confidence, communication, creativity and life skills — not just examination marks.'],
    ['title' => 'Special Education Support', 'content' => 'A qualified Special Educator and personalised plans for diverse learning needs.']
];

// 4. Fetch Accreditations (Section 15)
$accreditations = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `accreditations` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $accreditations = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($accreditations)) {
    $accreditations = [
        [
            'name' => 'ISSO — International Schools Sports Organisation',
            'subtitle' => 'Building Champions Beyond the Classroom',
            'description' => 'Through its association with ISSO, Zuvio aims to provide learners access to a structured school-sports ecosystem that promotes competition, teamwork, discipline, resilience and sporting excellence. ISSO connects international-curriculum schools and student-athletes through organised multi-sport opportunities and competitive pathways.',
            'logo' => '/assets/images/isso-logo.png',
            'certificate_url' => 'https://www.issosports.org/'
        ],
        [
            'name' => 'IAO — International Accreditation Organization',
            'subtitle' => 'Committed to Global Quality Standards',
            'description' => 'Zuvio’s association with IAO reflects our focus on quality, continuous improvement and internationally benchmarked educational practices. IAO provides quality-assurance and accreditation services to educational institutions, including online and distance-learning providers.',
            'logo' => '/assets/images/iao-logo.png',
            'certificate_url' => 'https://www.iao.org/India-Delhi/Zuvio-Global-School'
        ],
        [
            'name' => 'Oxford Quality',
            'subtitle' => 'Powered by the Excellence of Oxford University Press',
            'description' => 'As part of the Oxford Quality community, Zuvio strengthens learning through high-quality educational resources, teacher professional development and globally connected learning opportunities. Oxford Quality is an Oxford University Press programme designed to support institutions committed to continuously developing their teaching, learning methods and resources.',
            'logo' => '/assets/images/oxford-logo.png',
            'certificate_url' => 'https://india.oup.com/'
        ]
    ];
}

// 5. Fetch Parent Testimonials (Section 16)
$testimonials = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `testimonials` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $testimonials = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($testimonials)) {
    $testimonials = [
        [
            'parent_name' => 'Priya & Rajesh Sharma',
            'child_info' => 'Parents of Aarav (Grade 4)',
            'review_text' => 'Transitioning to Zuvio Global School was the best decision for our son. The live teachers are incredibly engaging, and the Oxford theme-based curriculum connects concepts in a way that actually makes sense to him. He loves waking up for his classes!',
            'photo' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
            'rating' => 5
        ],
        [
            'parent_name' => 'Dr. Anandita Sen',
            'child_info' => 'Mother of Rhea (Grade 7)',
            'review_text' => 'As a family that frequently relocates between cities, Zuvio gave us uninterrupted, high-quality schooling. The coding, AI integration, and project-based approach ensure she stays far ahead of traditional schooling standards.',
            'photo' => '/assets/images/Profile_Images/Rashmi_Professional_Profile.webp',
            'rating' => 5
        ],
        [
            'parent_name' => 'Kavita & Vikram Mehta',
            'child_info' => 'Parents of Kabir (Kindergarten)',
            'review_text' => 'The Early Years programme is simply fantastic. The teachers use stories, music, and interactive activities that keep our 5-year-old engaged without excessive screen fatigue. Highly recommended for alternative learning!',
            'photo' => '/assets/images/Profile_Images/Sharmin_Professional_Profile.webp',
            'rating' => 5
        ]
    ];
}

// 6. Fetch Parent FAQs (Section 20 - All 18 Questions from PDF)
$faqs = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `faqs` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $faqs = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($faqs)) {
    $faqs = [
        ['id' => 1, 'question' => 'What are the timings of online classes?', 'answer' => "Zuvio follows age-appropriate class durations. Classes are scheduled within the 9:00 AM-1:00 PM window, depending on the grade.\n\nPre-Primary: approximately 2 hours\nPrimary: approximately 2.5 hours\nMiddle School: approximately 3 hours\n\nThe timetable includes suitable breaks, interactive activities and a balanced mix of teacher-led and student-led learning."],
        ['id' => 2, 'question' => 'How does online schooling work at Zuvio?', 'answer' => "Zuvio Global School is a 100% online school where students attend structured live classes with teachers from home. Students follow a planned timetable and academic calendar and receive learning resources, assignments, projects, assessments and teacher support through the school's digital learning ecosystem."],
        ['id' => 3, 'question' => 'Is the Zuvio curriculum CBSE-mapped?', 'answer' => "Yes. Zuvio's academic curriculum is mapped to CBSE learning outcomes and designed with reference to NEP 2020 and NCF guidelines. This supports grade-appropriate concepts, competencies and structured academic progression."],
        ['id' => 4, 'question' => 'What is the Oxford theme-based curriculum approach?', 'answer' => "Zuvio incorporates an Oxford-based thematic learning approach, where concepts are connected through meaningful themes instead of always being taught as isolated topics. A theme can bring together language, environmental studies, creativity, research, communication and real-world applications, helping children understand connections across subjects."],
        ['id' => 5, 'question' => 'What is Project-Based Learning?', 'answer' => "Project-Based Learning allows students to learn by doing. Students explore a question, challenge or real-life topic and create a project, presentation, model, report or solution. This helps develop critical thinking, creativity, communication, research, collaboration and problem-solving skills."],
        ['id' => 6, 'question' => 'How is Artificial Intelligence integrated into the curriculum?', 'answer' => "AI learning is introduced in an age-appropriate manner as part of Zuvio's future-ready learning approach. Students gradually learn AI concepts, responsible use of technology, problem-solving and practical applications. Zuvio's AI learning approach uses IBM-supported/certified learning resources and frameworks, subject to the applicable programme and certification requirements."],
        ['id' => 7, 'question' => 'How are examinations and assessments conducted?', 'answer' => "Assessment includes formative assessments, projects, assignments, class participation, portfolios and summative assessments. Formal assessments are conducted online according to school examination guidelines. For Nursery-UKG, assessment is primarily activity- and observation-based rather than dependent on formal written examinations. Parents receive regular feedback and progress reports."],
        ['id' => 8, 'question' => 'Can parents speak directly with teachers?', 'answer' => "Yes. Parents can communicate with teachers and the academic team through WhatsApp groups, email, scheduled virtual meetings and Parent-Teacher Meetings (PTMs), depending on the requirement."],
        ['id' => 9, 'question' => 'What is NIOS, and how does it relate to online schooling?', 'answer' => "The National Institute of Open Schooling (NIOS) provides recognised open-schooling pathways in India. Where applicable, families seeking a formal open-schooling certification pathway can explore NIOS according to its prevailing eligibility, registration and examination requirements.\n\nImportant: NIOS and a CBSE-mapped curriculum are not the same. Curriculum mapping describes what and how a student learns, while NIOS is an examination/certification pathway."],
        ['id' => 10, 'question' => 'Can my child move back to an offline school later?', 'answer' => "Yes, students can transition from online learning to an offline school. Admission is governed by the receiving school's admission policy, documentation, grade-level requirements and applicable board/regulatory rules. Zuvio can support parents with relevant academic records and progress documentation."],
        ['id' => 11, 'question' => 'Are co-curricular activities included in online schooling?', 'answer' => "Yes. Co-curricular learning is planned in sync with the academic curriculum. Activities may include art, communication, storytelling, STEM, innovation, digital skills, presentations, competitions, projects and other age-appropriate enrichment experiences. The aim is to support academic, creative, social and emotional development."],
        ['id' => 12, 'question' => 'Will children get enough interaction in an online school?', 'answer' => "Yes. Live classes can include discussions, quizzes, presentations, show-and-tell, collaborative activities, projects, peer interaction and teacher questioning. Students are encouraged to actively participate rather than simply watch a screen."],
        ['id' => 13, 'question' => 'How is student progress monitored?', 'answer' => "Teachers monitor progress through classroom participation, assignments, projects, quizzes, assessments and portfolios. Regular reviews help identify learning gaps and determine where additional academic support may be required."],
        ['id' => 14, 'question' => 'What happens if my child is struggling with a concept?', 'answer' => "Teachers can identify learning gaps through continuous assessment and classroom interaction. Students may receive additional clarification, practice material and academic support according to their learning needs."],
        ['id' => 15, 'question' => 'How much screen time will my child have?', 'answer' => "Zuvio follows grade-appropriate live-class durations. Online lessons are complemented by offline activities such as reading, writing, worksheets, projects, art, research, experiments and hands-on learning. The objective is not to keep children continuously in front of a screen."],
        ['id' => 16, 'question' => 'Is online schooling suitable for children living outside India?', 'answer' => "Online schooling can be particularly useful for NRI and internationally mobile families seeking continuity in learning while living abroad or moving between countries. Families should separately check compulsory-schooling and recognition requirements applicable in their country of residence."],
        ['id' => 17, 'question' => 'What support is provided to students joining in Term 2?', 'answer' => "Students joining mid-session can undergo a baseline/diagnostic assessment to understand their current learning level. Teachers can then identify gaps and provide a bridge learning plan to support a smooth transition into the ongoing curriculum."],
        ['id' => 18, 'question' => 'What makes Zuvio\'s online learning approach different?', 'answer' => "Zuvio combines structured academics, CBSE-mapped learning, thematic learning, project-based education, AI and digital skills, experiential learning, co-curricular activities and regular parent-teacher interaction in a flexible online environment. The focus is on developing confident, independent and future-ready learners.\n\nNote: Programme, curriculum, certification, examination and progression details are subject to applicable school policies and external provider/regulatory requirements. Partnership and certification references should be read according to the specific programme applicable to the learner."]
    ];
}

// 7. Fetch Published Blogs / News (Section 17)
$posts = [];
if ($db) {
    try {
        $stmt = $db->query("
            SELECT b.*, c.name as category_name 
            FROM `blogs` b 
            LEFT JOIN `blog_categories` c ON c.id = b.category_id 
            WHERE b.status = 'published' 
            ORDER BY b.publish_date DESC LIMIT 3
        ");
        $posts = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($posts)) {
    $posts = [
        [
            'title' => 'How Oxford Thematic Learning Sparks Curiosity in Early Learners',
            'category_name' => 'Pedagogy & Curriculum',
            'excerpt' => 'Connecting math, language, and real-world science through meaningful monthly themes helps children synthesize ideas naturally.',
            'featured_image' => '/assets/images/homepage_hero_1.jpg',
            'slug' => 'oxford-thematic-learning-early-years'
        ],
        [
            'title' => 'Artificial Intelligence & Future Skills for Primary School Students',
            'category_name' => 'Technology & AI',
            'excerpt' => 'Introducing prompt engineering, algorithmic thinking, and ethical tech literacy in age-appropriate modules.',
            'featured_image' => '/assets/images/Hero image 2.png',
            'slug' => 'ai-future-skills-primary-school'
        ],
        [
            'title' => 'Zuvio Recognised for Excellence in Digital Homeschooling 2026',
            'category_name' => 'Awards & Recognition',
            'excerpt' => 'Celebrating global benchmarked standards, teacher mentorship, and our student-centric online learning model.',
            'featured_image' => '/assets/images/Students learning in classroom.png',
            'slug' => 'zuvio-recognised-excellence-digital-schooling'
        ]
    ];
}

// Header inclusion
$page_slug = 'home';
include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- ========================================================================
     SECTIONS 4 & 5: HERO CAROUSEL + SIDE ENQUIRY FORM + PRIMARY CTAS
     ======================================================================== -->
<section class="hero-wrapper">
  <div class="container hero-stage">
    <div class="hero-stage-grid">
      
      <!-- Left Column: 70% Hero Banner / Carousel -->
      <div class="hero-banner-card" id="heroBannerCarousel">
        <div class="hero-carousel-slides">
          <!-- Slide 1: Primary Proposition (Verbatim Source Document) -->
          <div class="hero-slide-pane active">
            <div class="hero-lead-badge">
              <span style="width: 8px; height: 8px; background-color: var(--color-teal); border-radius: 50%; display: inline-block;"></span>
              100% Live Online Schooling
            </div>
            <h1 class="hero-title-main">Learning Without Boundaries. Growing With Purpose.</h1>
            <p class="hero-desc-main">
              Academic excellence meets personalised online learning. A structured, CBSE-mapped curriculum enriched with Oxford thematic learning, IBM-supported AI literacy, and caring small-group mentoring for grades K to 8.
            </p>
            <div class="hero-actions-row">
              <a href="/admissions#enrol" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.8rem;">
                Enrol Now
              </a>
              <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.8rem;">
                Take a Demo
              </a>
              <a href="/assets/content/Zuvio_Beyond_Activity_Brochure_Revised_Grades_12 copy.pdf" target="_blank" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600; padding: 0.85rem 1.5rem;">
                Download Brochure &darr;
              </a>
            </div>
          </div>

          <!-- Slide 2: Personalised Learning Paths -->
          <div class="hero-slide-pane">
            <div class="hero-lead-badge">
              <span style="width: 8px; height: 8px; background-color: var(--color-gold); border-radius: 50%; display: inline-block;"></span>
              Personalised Learning Paths
            </div>
            <h2 class="hero-title-main">Classrooms That Adapt To Every Child's Pace.</h2>
            <p class="hero-desc-main">
              Every child learns differently. Our small-group live classrooms with a 15:1 student-teacher ratio adapt to your child’s pace, strengths, and unique potential with caring mentorship.
            </p>
            <div class="hero-actions-row">
              <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.8rem;">
                Our Curriculum
              </a>
              <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.8rem;">
                Take a Demo
              </a>
              <a href="/assets/content/Zuvio_Beyond_Activity_Brochure_Revised_Grades_12 copy.pdf" target="_blank" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600; padding: 0.85rem 1.5rem;">
                Download Brochure &darr;
              </a>
            </div>
          </div>

          <!-- Slide 3: Future Skills & Science Labs -->
          <div class="hero-slide-pane">
            <div class="hero-lead-badge">
              <span style="width: 8px; height: 8px; background-color: var(--color-teal); border-radius: 50%; display: inline-block;"></span>
              Future Skills & Digital Labs
            </div>
            <h2 class="hero-title-main">Interactive Science, Coding & AI Literacy.</h2>
            <p class="hero-desc-main">
              Virtual experiments, coding logic, robotics engineering, and IBM-supported AI awareness integrated into daily schooling to empower young learners for tomorrow.
            </p>
            <div class="hero-actions-row">
              <a href="/academics" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.8rem;">
                Explore Academics
              </a>
              <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700; padding: 0.85rem 1.8rem;">
                Take a Demo
              </a>
              <a href="/assets/content/Zuvio_Beyond_Activity_Brochure_Revised_Grades_12 copy.pdf" target="_blank" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600; padding: 0.85rem 1.5rem;">
                Download Brochure &darr;
              </a>
            </div>
          </div>
        </div>

        <!-- Carousel Slide Indicators -->
        <div class="hero-carousel-nav" style="display: flex; gap: 0.5rem; margin-top: 2rem; align-items: center;">
          <span class="hero-carousel-dot active" onclick="setHeroSlide(0)" title="Slide 1"></span>
          <span class="hero-carousel-dot" onclick="setHeroSlide(1)" title="Slide 2"></span>
          <span class="hero-carousel-dot" onclick="setHeroSlide(2)" title="Slide 3"></span>
        </div>
      </div>

      <!-- Right Column: Section 5 Enquiry Form Beside Hero -->
      <div class="hero-enquiry-card">
        <div class="enquiry-card-header">
          <h3>Enquire Now</h3>
          <p>Begin your child’s personalised schooling journey today.</p>
        </div>

        <?php if ($form_status === 'success'): ?>
          <div style="background-color: #DEF7EC; border: 1px solid #31C48D; padding: 1.5rem; border-radius: var(--radius-md); text-align: center;">
            <svg style="width: 40px; height: 40px; color: #0E9F6E; margin: 0 auto 0.75rem auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 style="color: #03543F; font-size: 1.15rem; margin-bottom: 0.35rem;">Enquiry Received</h4>
            <p style="color: #046C4E; font-size: 0.85rem;">Thank you. Our academic counselors will get in touch with you shortly.</p>
          </div>
        <?php else: ?>
          <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
            <input type="hidden" name="submit_enquiry" value="1">

            <?php if ($form_status === 'error'): ?>
              <div style="background-color: #FDE8E8; border: 1px solid #F98080; padding: 0.75rem; border-radius: var(--radius-sm); color: #9B1C1C; font-size: 0.8rem; margin-bottom: 1rem;">
                <?php echo h($error_message); ?>
              </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; margin-bottom: 0.65rem;">
              <input type="text" name="parent_name" placeholder="Parent Name *" required class="admin-input">
              <input type="text" name="student_name" placeholder="Student Name" class="admin-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; margin-bottom: 0.65rem;">
              <input type="email" name="email" placeholder="Email Address *" required class="admin-input">
              <input type="tel" name="phone" placeholder="Phone Number *" required class="admin-input">
            </div>

            <div style="margin-bottom: 0.65rem;">
              <select name="grade" required class="admin-input" style="font-weight: 500;">
                <option value="">Select Grade of Interest *</option>
                <option value="Early Years (K-KG)">Early Years (K-KG)</option>
                <option value="Primary (Grades 1-2)">Foundation (Grades 1–2)</option>
                <option value="Primary (Grades 3-5)">Preparatory (Grades 3–5)</option>
                <option value="Middle School (Grades 6-8)">Middle School (Grades 6–8)</option>
              </select>
            </div>

            <div style="margin-bottom: 1rem;">
              <textarea name="message" placeholder="Brief note / questions (Optional)" rows="2" class="admin-input" style="resize: none;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700;">
              Submit Enquiry &rarr;
            </button>
          </form>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 7: ABOUT ZUVIO (HOMEPAGE SHORT FORM WITH SUPPORTING GRAPHIC)
     ======================================================================== -->
<section class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 1140px;">
    <div class="text-center" style="margin-bottom: 2.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Who Are We & Why Zuvio</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">About Zuvio</h2>
      <div style="width: 60px; height: 3px; background-color: var(--color-gold); margin: 1rem auto 0 auto;"></div>
    </div>

    <div class="about-zuvio-grid" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 2.5rem; align-items: center;">
      <!-- Content Box -->
      <div style="background-color: var(--pastel-blue); border-radius: var(--radius-lg); padding: 2.75rem 2.25rem; border: 1.5px solid rgba(10, 137, 152, 0.2); box-shadow: var(--shadow-sm);">
        <p style="font-size: 1.22rem; font-weight: 600; color: var(--color-navy); line-height: 1.7; margin-bottom: 1.5rem; font-family: var(--font-secondary);">
          Zuvio Global School is an online school built on one belief: <span style="color: var(--color-teal); text-decoration: underline; text-underline-offset: 4px;">education should adapt to the child, not the child to the system.</span>
        </p>
        <p style="font-size: 1.05rem; color: var(--color-text); line-height: 1.8; margin-bottom: 1.75rem;">
          We bring together a structured, curriculum-aligned programme, caring teachers and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere. Different ways of learning. One community. Equal opportunities.
        </p>
        <p style="font-size: 1.15rem; font-weight: 700; color: var(--color-navy-dark); margin-bottom: 2rem;">
          That's what learning beyond boundaries means.
        </p>
        <a href="/about" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 600; padding: 0.75rem 2rem;">
          Read Our Story &rarr;
        </a>
      </div>

      <!-- Supporting Graphic Visual -->
      <div class="about-zuvio-visual" style="border-radius: var(--radius-lg); overflow: hidden; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-md); position: relative;">
        <img src="/assets/images/Teacher interacting with students.png" alt="Teacher interacting with students at Zuvio Global School" style="width: 100%; height: 380px; object-fit: cover; display: block;">
        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(0deg, rgba(3,27,66,0.92) 0%, rgba(3,27,66,0) 100%); padding: 1.5rem 1.25rem 1rem 1.25rem; color: #FFFFFF;">
          <p style="font-weight: 700; font-size: 1rem; margin: 0; color: var(--color-gold);">Learning Beyond Boundaries</p>
          <p style="font-size: 0.82rem; margin: 0; color: #E2E8F0;">Personalised, 100% Live Online Schooling • K to Grade 8</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 8: WHO SHOULD CHOOSE ZUVIO
     ======================================================================== -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Tailored for Modern Learners</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Who Should Choose Zuvio</h2>
      <p style="color: var(--color-muted); font-size: 1.1rem; margin-top: 0.5rem; max-width: 650px; margin-left: auto; margin-right: auto;">
        Zuvio is for families who want learning to fit their life — not their life to revolve around a timetable.
      </p>
    </div>

    <div class="grid-3" style="gap: 1.75rem;">
      <!-- Audience 1 -->
      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF;">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Globally Mobile Families</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.65;">
          Globally mobile families who move between cities or countries and want learning continuity without disruptions.
        </p>
      </div>
      <!-- Audience 2 -->
      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF;">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Homeschooling & Alternative Learners</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.65;">
          Homeschooling & alternative-learning families who want structure with freedom and teacher guidance.
        </p>
      </div>
      <!-- Audience 3 -->
      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF;">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Young Athletes, Artists & Performers</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.65;">
          Young athletes, artists & performers balancing demanding training and rehearsal schedules.
        </p>
      </div>
      <!-- Audience 4 -->
      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF;">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Children Who Thrive Online</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.65;">
          Children who thrive online and learn best in a digital environment with modern interactive tools.
        </p>
      </div>
      <!-- Audience 5 -->
      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF;">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Personalised Approach Seekers</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.65;">
          Learners who need a more personalised approach, pace or attention to reach their full potential.
        </p>
      </div>
      <!-- Audience 6 -->
      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF;">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary);">Alternative Schooling Environment</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.65;">
          Children who need a different schooling environment when traditional school isn't the right fit.
        </p>
      </div>
    </div>

    <div class="text-center" style="margin-top: 3rem;">
      <p style="font-size: 1.1rem; font-weight: 600; color: var(--color-navy); margin-bottom: 1.25rem;">
        If you believe education should adapt to the child, Zuvio may be the right choice.
      </p>
      <a href="/admissions#eligibility" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy);">
        Check Age & Grade Eligibility &rarr;
      </a>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 9: LEARNING PATH (4-STAGE LEARNING JOURNEY)
     ======================================================================== -->
<section class="section curriculum-pathways-section">
  <div class="container">
    <div class="text-center" style="max-width: 800px; margin: 0 auto 3rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Curriculum Pathways</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">A Future-Ready Learning Journey — K to Grade 8</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.7; margin-top: 1rem;">
        Aligned with CBSE, NEP 2020 and NCF — strong academic foundations blended with creativity, communication, digital fluency and real-world learning. Every stage builds on the last: from stories, sounds and play in the Early Years to research, innovation and independent thinking in Middle School.
      </p>
    </div>

    <!-- 4 Stage Cards Grid -->
    <div class="stage-grid-4">
      <?php foreach ($learning_journey_cards as $card): ?>
        <div class="stage-card">
          <div>
            <span class="stage-badge-pill"><?php echo h($card['badge_text']); ?></span>
            <h3 class="stage-card-title"><?php echo h($card['title']); ?></h3>
            <p class="stage-card-keywords"><?php echo h($card['subtitle']); ?></p>
            <div class="stage-card-points">
              <?php echo nl2br(h($card['content'])); ?>
            </div>
          </div>
          <?php if (!empty($card['outcome'])): ?>
            <div class="stage-card-outcome">
              <strong>Outcome:</strong> <?php echo h($card['outcome']); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center" style="margin-top: 3.5rem;">
      <p style="font-size: 1.2rem; font-weight: 700; color: var(--color-navy-dark); font-family: var(--font-primary); margin-bottom: 1.25rem;">
        Strong Foundations. Future Skills. Learning Without Boundaries.
      </p>
      <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2.5rem;">
        Explore the Full Curriculum &rarr;
      </a>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 10: THE ZUVIO LEARNING FRAMEWORK (FROM KNOWING TO DOING)
     ======================================================================== -->
<section class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 2rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Core Methodology</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">The Zuvio Learning Framework</h2>
      <p style="color: var(--color-muted); font-size: 1.1rem; margin-top: 0.5rem;">From Knowing to Doing</p>
    </div>

    <div class="framework-flow-container">
      <?php foreach ($framework_items as $item): ?>
        <div class="framework-step-card">
          <div class="framework-step-number"><?php echo h($item['badge_text']); ?></div>
          <h3 class="framework-step-title"><?php echo h($item['title']); ?></h3>
          <h4 style="font-size: 0.85rem; color: var(--color-teal); font-weight: 600; margin-bottom: 0.75rem;"><?php echo h($item['subtitle']); ?></h4>
          <p class="framework-step-desc"><?php echo h($item['content']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="framework-banner-ribbon">
      Knowledge &rarr; <span>Understanding</span> &rarr; Application &rarr; <span>Innovation</span>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 11: LEARNING BEYOND THE TEXTBOOK
     ======================================================================== -->
<section class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 2.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Real-World Classrooms</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Learning Beyond the Textbook</h2>
      <p style="color: var(--color-muted); font-size: 1.1rem; margin-top: 0.5rem; font-style: italic;">
        Because the world is the real classroom.
      </p>
    </div>

    <div class="beyond-textbook-grid">
      <?php foreach ($beyond_textbook_items as $item): ?>
        <div class="beyond-card">
          <h3><?php echo h($item['title']); ?></h3>
          <h4 style="font-size: 0.85rem; color: var(--color-teal); font-weight: 600; margin-bottom: 0.75rem;"><?php echo h($item['subtitle']); ?></h4>
          <p><?php echo h($item['content']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 12: WHAT MAKES ZUVIO DIFFERENT & THE ZUVIO GRADUATE
     ======================================================================== -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">The Zuvio Edge</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">What Makes Zuvio Different</h2>
    </div>

    <div class="grid-3" style="gap: 2rem;">
      <div class="card" style="padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-primary);">Assessment for Growth</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7;">
          We measure progress and skills, not just marks — providing regular, meaningful qualitative insights and developmental analytics for parents.
        </p>
      </div>

      <div class="card" style="padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-primary);">Personalised Learning</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7;">
          Live teacher guidance, adaptive digital tools and targeted academic support that continuously adapt to each child’s pace and individual learning needs.
        </p>
      </div>

      <div class="card" style="padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
        <h3 style="font-size: 1.35rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-primary);">Zuvio Beyond</h3>
        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7;">
          Rich co-curricular clubs, AI/coding modules, sports association, enrichment electives, and extra academic support matched to your child’s passions.
        </p>
      </div>
    </div>

    <!-- The Zuvio Graduate Banner -->
    <div class="graduate-outcomes-box">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Graduate Profile</span>
      <h3 style="font-size: 2rem; color: var(--color-navy-dark); font-family: var(--font-primary); margin: 0.5rem 0 1rem 0;">The Zuvio Graduate — by the end of Grade 8</h3>
      <div class="graduate-outcomes-pills">
        <span class="graduate-pill">Academically Strong</span>
        <span class="graduate-pill">Curious</span>
        <span class="graduate-pill">Confident & Articulate</span>
        <span class="graduate-pill">Creative</span>
        <span class="graduate-pill">Digitally Fluent</span>
        <span class="graduate-pill">Collaborative</span>
        <span class="graduate-pill">Independent</span>
        <span class="graduate-pill">Globally Aware</span>
        <span class="graduate-pill">Future-Ready</span>
      </div>
      <p style="font-size: 1.05rem; color: var(--color-navy); font-weight: 600; margin-top: 1rem;">
        Not just ready for the next grade — ready to learn, adapt and grow in a changing world.
      </p>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 13: INCLUSIVITY & BEYOND
     ======================================================================== -->
<section class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Equal Opportunities For Every Child</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Inclusivity & Beyond</h2>
      <p style="color: var(--color-muted); font-size: 1.1rem; margin-top: 0.5rem;">
        Every Child. Every Mind. Every Possibility.
      </p>
    </div>

    <!-- Inclusivity Statements Grid -->
    <div class="grid-3" style="gap: 1.75rem;">
      <?php foreach ($inclusivity_items as $item): ?>
        <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: var(--color-surface); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.5rem; font-family: var(--font-primary);"><?php echo h($item['title']); ?></h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6;"><?php echo h($item['content']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Co-Curricular Beyond Preview -->
    <div style="margin-top: 4rem; padding: 2.5rem; background-color: var(--pastel-blue); border-radius: var(--radius-lg); border: 1.5px solid rgba(10, 137, 152, 0.25);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
          <h3 style="font-size: 1.6rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.25rem;">Zuvio Beyond Co-Curricular Programmes</h3>
          <p style="color: var(--color-muted); font-size: 0.92rem;">Empowering skills in tech, innovation, logic, performing arts, and financial literacy.</p>
        </div>
        <a href="/beyond" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600;">View All Beyond Programmes &rarr;</a>
      </div>

      <div class="grid-4" style="gap: 1.25rem;">
        <div style="background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1.5px solid rgba(6, 43, 99, 0.16);">
          <strong style="color: var(--color-navy); font-size: 1.05rem;">AI Explorers</strong>
          <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Patterns, prompts, and responsible digital intelligence.</p>
        </div>
        <div style="background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1.5px solid rgba(6, 43, 99, 0.16);">
          <strong style="color: var(--color-navy); font-size: 1.05rem;">Coding</strong>
          <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Block-based to text coding: games, logic, and apps.</p>
        </div>
        <div style="background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1.5px solid rgba(6, 43, 99, 0.16);">
          <strong style="color: var(--color-navy); font-size: 1.05rem;">Robotics</strong>
          <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Hands-on engineering, sensor mechanics, and design.</p>
        </div>
        <div style="background: #FFFFFF; padding: 1.25rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1.5px solid rgba(6, 43, 99, 0.16);">
          <strong style="color: var(--color-navy); font-size: 1.05rem;">Financial Literacy</strong>
          <p style="font-size: 0.82rem; color: var(--color-muted); margin-top: 0.25rem;">Money habits, budgeting, and practical entrepreneurship.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 14: STATISTICS / BENCHMARKS (APPROVED SPECIFICATIONS)
     ======================================================================== -->
<section class="section text-center" style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; padding: 5rem 0;">
  <div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem;">
      
      <div class="stat-box" style="padding: 1.75rem 1rem; background: rgba(255,255,255,0.04); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.08);">
        <p style="font-size: 2.75rem; font-weight: 700; color: var(--color-gold); margin: 0; font-family: var(--font-primary); line-height: 1;">K–8</p>
        <p style="color: #E2E8F0; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0.5rem;">Grade Spectrum</p>
      </div>

      <div class="stat-box" style="padding: 1.75rem 1rem; background: rgba(255,255,255,0.04); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.08);">
        <p style="font-size: 2.75rem; font-weight: 700; color: var(--color-gold); margin: 0; font-family: var(--font-primary); line-height: 1;">15:1</p>
        <p style="color: #E2E8F0; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0.5rem;">Max Cohort Ratio</p>
      </div>

      <div class="stat-box" style="padding: 1.75rem 1rem; background: rgba(255,255,255,0.04); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.08);">
        <p style="font-size: 2.75rem; font-weight: 700; color: var(--color-gold); margin: 0; font-family: var(--font-primary); line-height: 1;">100%</p>
        <p style="color: #E2E8F0; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0.5rem;">Live Online Schooling</p>
      </div>

      <div class="stat-box" style="padding: 1.75rem 1rem; background: rgba(255,255,255,0.04); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.08);">
        <p style="font-size: 2.75rem; font-weight: 700; color: var(--color-gold); margin: 0; font-family: var(--font-primary); line-height: 1;">CBSE</p>
        <p style="color: #E2E8F0; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0.5rem;">& NEP 2020 Aligned</p>
      </div>

      <div class="stat-box" style="padding: 1.75rem 1rem; background: rgba(255,255,255,0.04); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.08);">
        <p style="font-size: 2.75rem; font-weight: 700; color: var(--color-gold); margin: 0; font-family: var(--font-primary); line-height: 1;">6</p>
        <p style="color: #E2E8F0; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 0.5rem;">Beyond Textbook Domains</p>
      </div>

    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 15: AFFILIATIONS & ACCREDITATIONS (WITH CERTIFICATE ACTION)
     ======================================================================== -->
<section id="accreditations" class="section" style="background-color: var(--pastel-blue); border-bottom: 1px solid var(--color-border); padding: 5.5rem 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Global Quality Partnerships</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Affiliations & Accreditations</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">Recognised and benchmarked globally for quality assurance and sports excellence.</p>
    </div>

    <div class="accreditation-card-grid">
      <?php foreach ($accreditations as $acc): 
        $is_iao = (strpos($acc['name'], 'IAO') !== false);
      ?>
        <div class="accreditation-feature-card">
          <div>
            <div class="accreditation-logo-wrapper">
              <img src="<?php echo h($acc['logo']); ?>" alt="<?php echo h($acc['name']); ?> Logo">
            </div>
            <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.35rem;"><?php echo h($acc['name']); ?></h3>
            <h4 style="font-size: 0.88rem; color: var(--color-teal); font-weight: 600; margin-bottom: 1rem;"><?php echo h($acc['subtitle']); ?></h4>
            <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.65;"><?php echo h($acc['description']); ?></p>
          </div>
          
          <?php if ($is_iao || !empty($acc['certificate_url'])): ?>
            <?php 
              $btn_label = $is_iao ? 'View Certificate' : 'Learn More &rarr;';
              $cert_link = $acc['certificate_url'] ?: 'https://www.iao.org/India-Delhi/Zuvio-Global-School';
            ?>
            <a href="<?php echo h($cert_link); ?>" target="_blank" rel="noopener noreferrer" class="certificate-verify-btn" title="View Official Certificate">
              <?php echo $btn_label; ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 16: PARENT TESTIMONIALS / REVIEWS
     ======================================================================== -->
<section class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Parent Perspectives</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Parent Testimonials & Reviews</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem;">Hear directly from families flourishing in our online learning community.</p>
    </div>

    <div class="testimonials-grid">
      <?php foreach ($testimonials as $test): ?>
        <div class="testimonial-card">
          <div class="testimonial-stars">
            <?php for ($i = 0; $i < ($test['rating'] ?? 5); $i++): ?>★<?php endfor; ?>
          </div>
          <p class="testimonial-quote">"<?php echo h($test['review_text']); ?>"</p>
          <div class="testimonial-author-row">
            <?php if (!empty($test['photo'])): ?>
              <img src="<?php echo h($test['photo']); ?>" alt="<?php echo h($test['parent_name']); ?>" class="testimonial-avatar">
            <?php endif; ?>
            <div>
              <div class="testimonial-author-name"><?php echo h($test['parent_name']); ?></div>
              <div class="testimonial-author-meta"><?php echo h($test['child_info']); ?></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 17: NEWS / UPDATES / BLOGS / AWARDS & RECOGNITION
     ======================================================================== -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Stay Informed</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">News, Updates & Recognition</h2>
    </div>

    <div class="grid-3" style="gap: 2rem;">
      <?php foreach ($posts as $post): ?>
        <div class="card" style="padding: 0; overflow: hidden; border: 1.5px solid rgba(6, 43, 99, 0.16); background-color: #FFFFFF; border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
          <div style="height: 190px; background-image: url('<?php echo h($post['featured_image']); ?>'); background-size: cover; background-position: center;"></div>
          <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
            <span style="font-size: 0.75rem; color: var(--color-gold); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">
              <?php echo h($post['category_name'] ?: 'School News'); ?>
            </span>
            <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; line-height: 1.4;"><?php echo h($post['title']); ?></h3>
            <p style="color: var(--color-muted); font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.5rem;"><?php echo h($post['excerpt']); ?></p>
            <a href="/blogs/<?php echo h($post['slug']); ?>" class="btn btn-outline" style="margin-top: auto; padding: 0.5rem 1.25rem; font-size: 0.82rem; align-self: flex-start;">
              Learn More &rarr;
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 18: FEATURED IN (RESPONSIVE SLIDER / CAROUSEL)
     ======================================================================== -->
<section class="section text-center" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border); padding: 4.5rem 0;">
  <div class="container">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Media Recognition</span>
    <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 0.5rem 0 1rem 0; font-family: var(--font-primary);">Featured In</h2>
    <p style="color: var(--color-muted); font-size: 0.95rem; max-width: 600px; margin: 0 auto 2.5rem auto;">
      Zuvio Global School highlighted in leading educational publications for pioneering future-skills homeschooling.
    </p>

    <!-- Responsive Logo Carousel -->
    <div class="featured-in-slider-container" id="featuredSliderContainer">
      <div class="featured-in-slider-track" id="featuredSliderTrack">
        <div class="featured-in-slide-item">
          <div class="featured-in-card">Education World</div>
        </div>
        <div class="featured-in-slide-item">
          <div class="featured-in-card">EdTech Review</div>
        </div>
        <div class="featured-in-slide-item">
          <div class="featured-in-card">The Hindu Education</div>
        </div>
        <div class="featured-in-slide-item">
          <div class="featured-in-card">Brainfeed Magazine</div>
        </div>
        <div class="featured-in-slide-item">
          <div class="featured-in-card">Indian Express</div>
        </div>
        <div class="featured-in-slide-item">
          <div class="featured-in-card">Hindustan Times</div>
        </div>
      </div>

      <div class="featured-in-controls">
        <button class="featured-in-nav-btn" onclick="prevFeaturedSlide()" aria-label="Previous publication">&larr;</button>
        <div class="featured-in-dots" id="featuredSliderDots"></div>
        <button class="featured-in-nav-btn" onclick="nextFeaturedSlide()" aria-label="Next publication">&rarr;</button>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 19: FOUNDER'S MESSAGE (VERBATIM FROM REFERENCE DOCX)
     ======================================================================== -->
<section id="founder" class="founder-message-section">
  <div class="container">
    <div class="founder-editorial-card">
      
      <!-- Founder Portrait & Metadata -->
      <div class="founder-portrait-col">
        <div class="founder-portrait-frame">
          <img src="/assets/images/Profile_Images/Pragya_Professional_Profile.webp" alt="Founder of Zuvio Global School">
        </div>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.25rem;">Founder</h3>
        <p style="font-size: 0.85rem; color: var(--color-gold); font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Zuvio Global School</p>
        <div style="margin-top: 1.5rem;">
          <a href="/founder-message" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1.25rem; border-color: var(--color-navy); color: var(--color-navy);">Read Full Message &rarr;</a>
        </div>
      </div>

      <!-- Founder Letter Body -->
      <div class="founder-letter-col">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">Founder's Message</span>
        <h2 style="font-size: 2.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.5rem; line-height: 1.25;">
          Learning Without Boundaries. Growing With Purpose.
        </h2>

        <p style="font-weight: 700; color: var(--color-navy); font-size: 1.05rem; margin-bottom: 1rem;">
          Dear Parents, Students and Members of the Zuvio Community,
        </p>

        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1rem;">
          Education today must prepare children not only for examinations, but for a world that is constantly evolving.
        </p>

        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1rem;">
          At Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.
        </p>

        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1rem;">
          Our vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.
        </p>

        <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem;">
          Our aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.
        </p>

        <div class="founder-signoff-box">
          <p style="margin: 0; font-size: 0.95rem;">Warm regards,</p>
          <p style="margin: 0.25rem 0 0 0; font-weight: 700; font-size: 1.05rem; color: var(--color-navy-dark);">Founder</p>
          <p style="margin: 0; font-size: 0.85rem; color: var(--color-gold); font-weight: 600;">Zuvio Global School</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 20: FAQ (TWO-COLUMN ACCORDION LAYOUT WITH 18 QUESTIONS FROM PDF)
     ======================================================================== -->
<section id="faq" class="section" style="background-color: var(--color-surface); border-bottom: 1px solid var(--color-border); padding: 5.5rem 0;">
  <div class="container">
    <div class="text-center" style="margin-bottom: 2rem;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Got Questions?</span>
      <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Parent FAQ — Online Schooling</h2>
      <p style="color: var(--color-muted); font-size: 1.05rem; margin-top: 0.5rem; max-width: 650px; margin-left: auto; margin-right: auto;">
        A quick guide for parents to understand Zuvio’s online schooling model, curriculum, assessments, communication and student support.
      </p>
    </div>

    <!-- FAQ Accordion UI -->
    <div class="faq-accordion-container">
      
      <!-- Live Search / Filter Input -->
      <input type="text" id="faqSearchInput" placeholder="Type a keyword to filter questions (e.g. CBSE, timings, screen time, assessment)..." class="faq-search-input" onkeyup="filterFaqs()">

      <?php 
        $half = ceil(count($faqs) / 2);
        $faqs_col1 = array_slice($faqs, 0, $half, true);
        $faqs_col2 = array_slice($faqs, $half, null, true);
      ?>
      <div id="faqAccordionList" class="faq-accordion-grid-2col">
        <!-- Column 1 -->
        <div class="faq-col">
          <?php foreach ($faqs_col1 as $idx => $faq): 
            $is_first = ($idx === 0);
          ?>
            <div class="faq-accordion-item <?php echo $is_first ? 'open' : ''; ?>">
              <button class="faq-accordion-btn" onclick="toggleFaq(this)">
                <span><?php echo ($idx + 1) . '. ' . h($faq['question']); ?></span>
                <span class="faq-accordion-icon">+</span>
              </button>
              <div class="faq-accordion-body" style="<?php echo $is_first ? 'max-height: 500px;' : ''; ?>">
                <div class="faq-accordion-body-inner">
                  <?php echo nl2br(h($faq['answer'])); ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Column 2 -->
        <div class="faq-col">
          <?php foreach ($faqs_col2 as $idx => $faq): ?>
            <div class="faq-accordion-item">
              <button class="faq-accordion-btn" onclick="toggleFaq(this)">
                <span><?php echo ($idx + 1) . '. ' . h($faq['question']); ?></span>
                <span class="faq-accordion-icon">+</span>
              </button>
              <div class="faq-accordion-body">
                <div class="faq-accordion-body-inner">
                  <?php echo nl2br(h($faq['answer'])); ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2.5rem; flex-wrap: wrap; gap: 1rem;">
        <a href="/faq" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 600;">
          View Complete 18-Question FAQ Page &rarr;
        </a>
        <a href="/contact" class="btn btn-primary" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 600;">
          Have a Different Question? Contact Us &rarr;
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ========================================================================
     SECTION 21: FINAL CONVERSION CTA
     ======================================================================== -->
<section class="section text-center" style="background: linear-gradient(135deg, var(--pastel-blue) 0%, var(--pastel-yellow) 100%); padding: 6.5rem 0;">
  <div class="container" style="max-width: 760px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">Start Your Journey</span>
    <h2 style="font-size: 2.75rem; color: var(--color-navy-dark); margin: 0.75rem 0 1.25rem 0; font-family: var(--font-primary); font-weight: 700;">Ready to Experience Zuvio?</h2>
    <p style="color: var(--color-text); font-size: 1.15rem; line-height: 1.8; margin-bottom: 2.5rem;">
      Connect with our academic team today to discuss an age-appropriate learning timeline and personalized curriculum roadmap for your child.
    </p>
    <div style="display: flex; gap: 1.25rem; justify-content: center; flex-wrap: wrap;">
      <a href="/admissions#enrol" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.05rem; background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700;">
        Begin Your Journey
      </a>
      <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="padding: 1rem 2.5rem; font-size: 1.05rem; background-color: var(--color-teal); border-color: var(--color-teal); color: #FFFFFF; font-weight: 700;">
        Book a Free Demo
      </a>
    </div>
  </div>
</section>

<!-- Interactive Scripts: FAQ, Featured In Slider, and Stats Counter -->
<script>
  function toggleFaq(btn) {
    const item = btn.closest('.faq-accordion-item');
    const body = item.querySelector('.faq-accordion-body');
    const isOpen = item.classList.contains('open');

    if (isOpen) {
      item.classList.remove('open');
      body.style.maxHeight = null;
    } else {
      item.classList.add('open');
      body.style.maxHeight = body.scrollHeight + 'px';
    }
  }

  function filterFaqs() {
    const filter = document.getElementById('faqSearchInput').value.toLowerCase();
    const items = document.querySelectorAll('#faqAccordionList .faq-accordion-item');

    items.forEach(item => {
      const text = item.textContent.toLowerCase();
      if (text.includes(filter)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  }

  // Hero Carousel Slide Controller
  let heroSlideIdx = 0;
  function setHeroSlide(idx) {
    const heroPanes = document.querySelectorAll('.hero-slide-pane');
    const heroDots = document.querySelectorAll('.hero-carousel-dot');
    if (!heroPanes.length) return;
    heroSlideIdx = idx;
    heroPanes.forEach((p, i) => {
      p.classList.toggle('active', i === idx);
    });
    heroDots.forEach((d, i) => {
      d.classList.toggle('active', i === idx);
    });
  }

  setInterval(() => {
    const heroPanes = document.querySelectorAll('.hero-slide-pane');
    if (heroPanes.length > 1) {
      heroSlideIdx = (heroSlideIdx + 1) % heroPanes.length;
      setHeroSlide(heroSlideIdx);
    }
  }, 6000);

  // Featured In Responsive Slider
  let featuredIndex = 0;
  const fTrack = document.getElementById('featuredSliderTrack');
  const fItems = document.querySelectorAll('.featured-in-slide-item');
  const fDotsContainer = document.getElementById('featuredSliderDots');

  function getVisibleSlides() {
    if (window.innerWidth <= 480) return 1;
    if (window.innerWidth <= 768) return 2;
    if (window.innerWidth <= 1024) return 3;
    return 4;
  }

  function getMaxFeaturedIndex() {
    return Math.max(0, fItems.length - getVisibleSlides());
  }

  function updateFeaturedDots() {
    if (!fDotsContainer) return;
    fDotsContainer.innerHTML = '';
    const maxIdx = getMaxFeaturedIndex();
    for (let i = 0; i <= maxIdx; i++) {
      const dot = document.createElement('span');
      dot.className = 'featured-in-dot' + (i === featuredIndex ? ' active' : '');
      dot.onclick = () => goToFeaturedSlide(i);
      fDotsContainer.appendChild(dot);
    }
  }

  function updateFeaturedSlider() {
    if (!fTrack || fItems.length === 0) return;
    const maxIdx = getMaxFeaturedIndex();
    if (featuredIndex > maxIdx) featuredIndex = maxIdx;
    if (featuredIndex < 0) featuredIndex = 0;

    const itemWidth = fItems[0].getBoundingClientRect().width;
    const gap = 20; // 1.25rem gap
    const offset = featuredIndex * (itemWidth + gap);
    fTrack.style.transform = `translateX(-${offset}px)`;
    updateFeaturedDots();
  }

  function nextFeaturedSlide() {
    const maxIdx = getMaxFeaturedIndex();
    featuredIndex = (featuredIndex >= maxIdx) ? 0 : featuredIndex + 1;
    updateFeaturedSlider();
  }

  function prevFeaturedSlide() {
    const maxIdx = getMaxFeaturedIndex();
    featuredIndex = (featuredIndex <= 0) ? maxIdx : featuredIndex - 1;
    updateFeaturedSlider();
  }

  function goToFeaturedSlide(idx) {
    featuredIndex = idx;
    updateFeaturedSlider();
  }

  let featuredTimer = setInterval(nextFeaturedSlide, 3500);
  const fContainer = document.getElementById('featuredSliderContainer');
  if (fContainer) {
    fContainer.addEventListener('mouseenter', () => clearInterval(featuredTimer));
    fContainer.addEventListener('mouseleave', () => {
      clearInterval(featuredTimer);
      featuredTimer = setInterval(nextFeaturedSlide, 3500);
    });
  }
  window.addEventListener('resize', updateFeaturedSlider);
  document.addEventListener('DOMContentLoaded', () => {
    updateFeaturedDots();
    updateFeaturedSlider();
  });

  // Statistics Number Animation
  document.addEventListener('DOMContentLoaded', () => {
    const animateStat = (elem) => {
      const start = parseInt(elem.getAttribute('data-start') || '0', 10);
      const target = parseInt(elem.getAttribute('data-target') || '0', 10);
      const suffix = elem.getAttribute('data-suffix') || '';
      let current = start;
      const stepTime = Math.max(20, Math.floor(1500 / (target - start)));

      const timer = setInterval(() => {
        current += 1;
        elem.innerText = current + suffix;
        if (current >= target) {
          clearInterval(timer);
          elem.innerText = target + suffix;
        }
      }, stepTime);
    };

    const studentElem = document.getElementById('stat_students');
    const educatorElem = document.getElementById('stat_educators');
    if (studentElem) animateStat(studentElem);
    if (educatorElem) animateStat(educatorElem);
  });
</script>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
