<?php
// Zuvio Global School - Academics Hub Page Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize Academics CMS data from session or defaults
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

// 1. Technology Default Data
$tech = $ac_cms['technology'] ?? [
    'hero_title' => 'Technology Built for Real Learning',
    'hero_subtitle' => 'Our Digital Learning Ecosystem',
    'hero_desc' => 'At Zuvio Global School, technology is never a passive screen. It is an active workspace for curiosity, collaboration, and creative mastery powered by an enterprise-grade online learning environment.',
    'lms_video' => '/assets/images/03_Science_Experiment_Learning.mp4',
    'lms_features' => [
        [
            'id' => 1,
            'title' => 'Child-Friendly and User-Focused',
            'desc' => 'Intentionally designed for young and growing learners, ensuring every student can navigate timetables, classes, and tasks with ease.',
            'icon' => '🧒'
        ],
        [
            'id' => 2,
            'title' => 'Live, Interactive Classes',
            'desc' => 'Real-time daily connections with teachers and peers using live video, digital whiteboards, breakout rooms, and interactive polls.',
            'icon' => '💻'
        ],
        [
            'id' => 3,
            'title' => 'Safety and Security First',
            'desc' => 'End-to-end encrypted and moderated virtual classrooms ensuring a safe, respectful, and distraction-free online learning environment.',
            'icon' => '🛡️'
        ],
        [
            'id' => 4,
            'title' => 'Digital Library at Fingertips',
            'desc' => 'Instant access to curriculum textbooks in PDF, Oxford graded readers, interactive workbooks, and thematic research guides.',
            'icon' => '📚'
        ],
        [
            'id' => 5,
            'title' => 'All-in-One Convenience',
            'desc' => 'From live schedules and assignment submissions to formative gradebooks and progress reports — everything is one click away.',
            'icon' => '⚡'
        ],
        [
            'id' => 6,
            'title' => 'Diverse Learning Resources',
            'desc' => 'Catering to visual, auditory, and kinesthetic learners with video lessons, audio podcasts, and hands-on simulation modules.',
            'icon' => '🎨'
        ]
    ]
];

// 2. Teacher & Parent Tools
$teacher_tools = $ac_cms['teacher_tools'] ?? [
    [
        'category' => 'Interactive Learning Platforms',
        'tools' => [
            ['name' => 'Nearpod', 'desc' => 'Interactive lessons with quizzes, polls, and collaborative boards.'],
            ['name' => 'Pear Deck', 'desc' => 'Real-time formative assessments integrated directly with presentations.'],
            ['name' => 'Edpuzzle', 'desc' => 'Video lessons embedded with comprehension questions and audio notes.']
        ]
    ],
    [
        'category' => 'Collaborative Workspaces',
        'tools' => [
            ['name' => 'Google Workspace', 'desc' => 'Real-time co-authoring on Docs, Sheets, and interactive Slides.'],
            ['name' => 'Microsoft 365', 'desc' => 'Cloud document authoring, Teams communication, and OneNote portfolios.'],
            ['name' => 'Padlet', 'desc' => 'Collaborative online bulletin boards for student research and ideas.']
        ]
    ],
    [
        'category' => 'Game-Based Learning & Quizzing',
        'tools' => [
            ['name' => 'Kahoot!', 'desc' => 'Gamified quizzes and challenges reinforcing live lesson concepts.'],
            ['name' => 'Quizizz', 'desc' => 'Self-paced formative assessments with instantaneous feedback.'],
            ['name' => 'Gimkit', 'desc' => 'Interactive classroom games built to deepen knowledge retention.']
        ]
    ],
    [
        'category' => 'Visual Creation & Video',
        'tools' => [
            ['name' => 'Canva for Education', 'desc' => 'Graphic design, mind maps, infographics, and project posters.'],
            ['name' => 'Powtoon', 'desc' => 'Animated explanatory videos and dynamic digital storytelling.'],
            ['name' => 'Flipgrid', 'desc' => 'Video discussion responses fostering oral confidence and peer feedback.']
        ]
    ]
];

// 3. Parent Portal Insights
$parent_portal = $ac_cms['parent_portal'] ?? [
    [
        'title' => 'Real-Time Insights & AI Analytics',
        'desc' => 'Track your child’s academic progress, attendance streaks, and learning engagement effortlessly with automated progress metrics.',
        'icon' => '📊'
    ],
    [
        'title' => 'Seamless Information Access',
        'desc' => 'Access daily class timetables, upcoming holiday calendars, circulars, and teacher notices at any hour of the day.',
        'icon' => '🗓️'
    ],
    [
        'title' => 'Hassle-Free Fee Management',
        'desc' => 'Secure online fee payments, instant downloadable receipts, and transparent fee schedules without paperwork.',
        'icon' => '💳'
    ],
    [
        'title' => 'Direct Teacher Communication',
        'desc' => 'Open, constructive dialogue with mentors and subject teachers plus monthly Parent-Teacher Meetings (PTMs).',
        'icon' => '🤝'
    ]
];

// 4. Special Education Data
$special_ed = $ac_cms['special_ed'] ?? [
    'title' => 'Inclusive Learning & Special Education',
    'kicker' => 'Every Child Learns. Every Child Belongs.',
    'intro' => 'At Zuvio Global School, we believe education must adapt to the learner — never the child to the system. Our inclusive learning programme creates a supportive, flexible, and learner-centred environment where children with diverse needs can take part meaningfully, grow in confidence, and discover their unique strengths.',
    'pillars' => [
        ['title' => 'Personalised Learning', 'desc' => 'Flexible learning pace, individualised goals, and customized worksheets tailored to cognitive strengths.'],
        ['title' => 'Individual Attention', 'desc' => 'Small cohorts (1:15–1:20) and dedicated one-on-one check-ins ensuring no child feels overwhelmed.'],
        ['title' => 'Flexible Learning Rhythm', 'desc' => 'Learn from the comfort of home, free from sensory overload, peer pressure, or rigid traditional timetables.'],
        ['title' => 'Strength-Based Pedagogy', 'desc' => 'Focusing on what children love and do best, cultivating genuine self-esteem and independent agency.'],
        ['title' => 'Social & Emotional Growth', 'desc' => 'Gentle encouragement, empathy-driven teacher relationships, and a safe, inclusive peer atmosphere.'],
        ['title' => 'Close Family Partnership', 'desc' => 'Regular collaborative reviews with parents to calibrate IEP milestones and celebrate every win.']
    ]
];

// 5. Electives Data
$electives = $ac_cms['electives'] ?? [
    'regional_languages' => ['Hindi', 'Sanskrit', 'Urdu', 'Tamil', 'Telugu', 'Kannada', 'Marathi', 'Bengali'],
    'foreign_languages' => ['French', 'Spanish', 'German', 'Arabic', 'Mandarin'],
    'future_skills' => [
        ['name' => 'Coding & Robotics', 'desc' => 'Block programming, Python fundamentals, and logic by Discovery Education.'],
        ['name' => 'Abacus & Rubik\'s Cube', 'desc' => 'Mental arithmetic speed, spatial memory, and focus concentration.'],
        ['name' => 'Public Speaking & Debate', 'desc' => 'Articulating ideas with poise, persuasive rhetoric, and voice modulation.'],
        ['name' => 'Creative Writing & Media', 'desc' => 'Authoring short stories, journalistic reporting, and digital publishing.'],
        ['name' => 'Financial Literacy', 'desc' => 'Foundational concepts of money, saving, budgeting, and ethical commerce.'],
        ['name' => 'Yoga & Mindfulness', 'desc' => 'Breathing exercises, physical postures, and emotional regulation techniques.']
    ]
];

// 6. NEP 2020 & Resources Data
$nep_data = $ac_cms['nep_2020'] ?? [
    'title' => 'NEP 2020 & NCF Compliance',
    'subtitle' => 'National Education Policy 2020 Alignment',
    'desc' => 'In full alignment with the National Education Policy (NEP 2020) and National Curriculum Framework (NCF), Zuvio replaces rote memorization with experiential, discovery-based, and interdisciplinary learning.',
    'pdf_title' => 'National Education Policy 2020 — Ministry of Education, Govt. of India',
    'pdf_url' => '/assets/docs/NEP_2020_Policy_Document.pdf'
];

$resources_data = $ac_cms['resources'] ?? [
    'calendar_title' => 'Academic Calendar 2026–27',
    'calendar_desc' => 'Comprehensive term dates, assessment schedules, project submission deadlines, and school holidays.',
    'calendar_pdf' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
];

$page_slug = 'academics';
$seo = [
    'seo_title' => 'Academics & Technology | Zuvio Global School',
    'meta_description' => 'Comprehensive academics at Zuvio Global School: US-grade LMS, Indian & International curricula, dedicated Special Education, Electives, and NEP 2020 alignment.',
    'canonical_url' => 'https://zuvioglobalschool.com/academics',
    'og_title' => 'Academics & Technology — Zuvio Global School',
    'og_description' => 'Explore Zuvio’s complete academic ecosystem: LMS, CRM, ERP, teacher/student tools, inclusive SEN support, and future-ready curricula.',
    'og_image' => '/assets/images/about_us_hero.jpg',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Academics']
]);
?>

<!-- 1. Hero Banner -->
<section style="background-color: var(--pastel-blue); padding: 5.5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 880px;">
    <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
      Academic Architecture & Pedagogy
    </span>
    <h1 style="font-size: 3rem; color: var(--color-navy-dark); margin: 0 0 1rem 0; font-family: var(--font-primary); font-weight: 700; line-height: 1.2;">
      Future-Ready Academics & Learning Technology
    </h1>
    <p style="font-size: 1.12rem; color: var(--color-text); line-height: 1.7; margin: 0 auto; max-width: 760px;">
      Where rigorous curriculum standards meet progressive online teaching, dedicated Special Education, modern LMS tools, and personalized pathways designed for every child.
    </p>
  </div>
</section>

<!-- 2. Sticky Academics In-Page Navigation Strip -->
<nav class="academics-subnav-sticky">
  <div class="academics-subnav-container">
    <a href="/technology" class="academics-subnav-link">
      <span>💻</span> Technology & LMS
    </a>
    <a href="/curriculum" class="academics-subnav-link">
      <span>📖</span> Curriculum Pathways
    </a>
    <a href="/special-education" class="academics-subnav-link">
      <span>🤝</span> Special Education
    </a>
    <a href="/electives" class="academics-subnav-link">
      <span>🌐</span> Electives & Languages
    </a>
    <a href="/nep-2020" class="academics-subnav-link">
      <span>📜</span> NEP 2020
    </a>
    <a href="/resources" class="academics-subnav-link">
      <span>📁</span> Resources & Calendar
    </a>
    <a href="/curriculum" class="academics-subnav-link" style="background: var(--color-gold); color: var(--color-navy-dark); font-weight: 700;">
      Full Curriculum Guide &rarr;
    </a>
  </div>
</nav>

<!-- 3. Section: Technology Ecosystem (LMS, CRM, ERP, Tools) -->
<section id="technology" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 780px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Digital Ecosystem
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Next-Generation Learning Management System (LMS)
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Designed with your child's needs in mind, our LMS is not just a portal — it is a doorway to boundless learning possibilities.
      </p>
    </div>

    <!-- LMS Features Grid -->
    <div class="tech-lms-grid" style="margin-bottom: 4rem;">
      <?php foreach ($tech['lms_features'] as $feat): ?>
        <div class="tech-feature-card">
          <div style="font-size: 1.85rem; margin-bottom: 1rem;">
            <?php echo $feat['icon'] ?? '✨'; ?>
          </div>
          <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.6rem;">
            <?php echo h($feat['title']); ?>
          </h3>
          <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin: 0;">
            <?php echo h($feat['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- CRM & ERP Systems -->
    <div class="grid-2" style="gap: 2rem; margin-bottom: 4rem;">
      <div class="card" style="padding: 2.25rem; background: var(--pastel-blue); border-radius: var(--radius-md);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <div style="font-size: 1.5rem;">📱</div>
          <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">
            Enterprise CRM System
          </h3>
        </div>
        <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.7; margin-bottom: 1rem;">
          Our Customer Relationship Management (CRM) platform ensures seamless communication from first enquiry to graduation. Every parent receives personalized onboarding support, scheduled counseling, and transparent communication records.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: var(--color-navy);">
          <li>✓ Structured student admission & onboarding workflows</li>
          <li>✓ Prompt resolution of parental queries & counselling</li>
          <li>✓ Automated circulars and school announcements</li>
        </ul>
      </div>

      <div class="card" style="padding: 2.25rem; background: var(--color-surface-warm); border-radius: var(--radius-md);">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
          <div style="font-size: 1.5rem;">⚙️</div>
          <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0;">
            Integrated School ERP
          </h3>
        </div>
        <p style="color: var(--color-text); font-size: 0.92rem; line-height: 1.7; margin-bottom: 1rem;">
          Our Enterprise Resource Planning (ERP) platform seamlessly automates school administrative operations, timetable scheduling, academic records, and fee payment systems.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: var(--color-navy);">
          <li>✓ Secure online fee payment gateway & instant digital receipts</li>
          <li>✓ Real-time attendance logging and audit records</li>
          <li>✓ Official transfer certificate (TC) and transcript generation</li>
        </ul>
      </div>
    </div>

    <!-- Minimum Device Specifications Table -->
    <div style="margin-bottom: 4rem;">
      <div class="text-center" style="margin-bottom: 2rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Hardware & Connectivity
        </span>
        <h3 style="font-size: 1.85rem; color: var(--color-navy); margin-top: 0.35rem; font-family: var(--font-primary);">
          Recommended Device Specifications
        </h3>
      </div>

      <div class="spec-table-wrap">
        <table class="spec-table">
          <thead>
            <tr>
              <th>Specification</th>
              <th>Minimum Requirement</th>
              <th>Recommended Ideal</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Internet Speed</strong></td>
              <td>5–10 Mbps stable connection</td>
              <td>15+ Mbps broadband or high-speed fiber</td>
            </tr>
            <tr>
              <td><strong>Operating System</strong></td>
              <td>Windows 10, macOS 11+, Android 10+, iOS 13+</td>
              <td>Latest updated version of OS</td>
            </tr>
            <tr>
              <td><strong>Memory (RAM)</strong></td>
              <td>4 GB RAM</td>
              <td>8 GB RAM or higher for seamless live video</td>
            </tr>
            <tr>
              <td><strong>Browser Compatibility</strong></td>
              <td>Google Chrome (v90+), Apple Safari (v14+)</td>
              <td>Latest version of Chrome or Safari with camera permissions</td>
            </tr>
            <tr>
              <td><strong>Peripherals</strong></td>
              <td>Standard webcam, microphone, and speakers</td>
              <td>Noise-canceling headset with microphone + external mouse</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tools for Teachers (4 Categories) -->
    <div style="margin-bottom: 4rem;">
      <div class="text-center" style="margin-bottom: 2.5rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Instructional Arsenal
        </span>
        <h3 style="font-size: 1.85rem; color: var(--color-navy); margin-top: 0.35rem; font-family: var(--font-primary);">
          Essential Tools for Teaching & Collaboration
        </h3>
        <p style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">
          Our educators leverage best-in-class global digital tools to foster deep student engagement.
        </p>
      </div>

      <div class="grid-2" style="gap: 2rem;">
        <?php foreach ($teacher_tools as $cat): ?>
          <div class="tech-tools-category-card">
            <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
              <?php echo h($cat['category']); ?>
            </h4>
            <div class="tool-chip-grid">
              <?php foreach ($cat['tools'] as $tool): ?>
                <div class="tool-chip">
                  <strong style="color: var(--color-teal); font-size: 0.95rem;"><?php echo h($tool['name']); ?></strong>
                  <span style="font-size: 0.82rem; color: var(--color-text); line-height: 1.4;"><?php echo h($tool['desc']); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Parent Portal Section -->
    <div>
      <div class="text-center" style="margin-bottom: 2.5rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Parent Partnership
        </span>
        <h3 style="font-size: 1.85rem; color: var(--color-navy); margin-top: 0.35rem; font-family: var(--font-primary);">
          24x7 Parent Portal: Your Window Into Learning
        </h3>
        <p style="color: var(--color-muted); font-size: 0.95rem; margin-top: 0.25rem;">
          You are never in the dark. Our portal gives parents complete transparency and timely insights.
        </p>
      </div>

      <div class="grid-4" style="gap: 1.5rem;">
        <?php foreach ($parent_portal as $pp): ?>
          <div class="card" style="padding: 1.75rem; background: #FFFFFF; border-radius: var(--radius-md);">
            <div style="font-size: 1.75rem; margin-bottom: 0.75rem;">
              <?php echo $pp['icon'] ?? '📱'; ?>
            </div>
            <h4 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">
              <?php echo h($pp['title']); ?>
            </h4>
            <p style="color: var(--color-text); font-size: 0.85rem; line-height: 1.55; margin: 0;">
              <?php echo h($pp['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- 4. Section: Curriculum Foundations & Quick Facts -->
<section id="curriculum" class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 780px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Curriculum Foundations
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Globally Benchmarked Educational Pathways
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Rigorous academic curriculum mapped to international and national boards.
      </p>
    </div>

    <!-- Curriculum Pathways Grid (3 Cards) -->
    <div class="grid-3" style="gap: 2rem; margin-bottom: 3.5rem;">
      <div class="card" style="padding: 2.25rem; background: #FFFFFF; border-top: 4px solid var(--color-teal); border-radius: var(--radius-md);">
        <span style="display: inline-block; background: var(--pastel-blue); color: var(--color-teal); font-weight: 700; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.75rem;">
          Cambridge Pathway
        </span>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
          British-Cambridge
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1rem;">
          Offering five stages from age 5 to 19, leading seamlessly from early years to pre-university. Emphasizes global perspectives, deep conceptual mastery, and world-recognized examinations.
        </p>
        <span style="font-size: 0.82rem; font-weight: 600; color: var(--color-navy);">Stages: Cambridge Early Years, Primary, Lower & Upper Secondary</span>
      </div>

      <div class="card" style="padding: 2.25rem; background: #FFFFFF; border-top: 4px solid var(--color-gold); border-radius: var(--radius-md);">
        <span style="display: inline-block; background: var(--pastel-yellow); color: #B98721; font-weight: 700; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.75rem;">
          American Curriculum
        </span>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
          American High School Diploma
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1rem;">
          Aligned with US standards to develop critical academic thinking, creative problem-solving, and continuous credit accumulation recognized by leading global universities.
        </p>
        <span style="font-size: 0.82rem; font-weight: 600; color: var(--color-navy);">Focus: WASC-aligned standards, GPA credits & college readiness</span>
      </div>

      <div class="card" style="padding: 2.25rem; background: #FFFFFF; border-top: 4px solid var(--color-teal); border-radius: var(--radius-md);">
        <span style="display: inline-block; background: var(--pastel-green); color: #047857; font-weight: 700; font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 4px; text-transform: uppercase; margin-bottom: 0.75rem;">
          Indian Curriculum
        </span>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
          CBSE & NIOS Aligned
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1rem;">
          Structured according to the National Curriculum Framework of India under NEP 2020. Combines NCERT syllabus rigor with experiential, interactive live classrooms.
        </p>
        <span style="font-size: 0.82rem; font-weight: 600; color: var(--color-navy);">Stages: Foundation, Preparatory, Middle & Secondary levels</span>
      </div>
    </div>

    <!-- Online Primary School Quick Facts Table -->
    <div style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.16); border-radius: var(--radius-md); padding: 2.5rem; margin-bottom: 3.5rem; box-shadow: var(--shadow-sm);">
      <h3 style="font-size: 1.45rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.25rem;">
        Online Primary School: Quick Facts (Classes 1 to 5)
      </h3>
      <div class="spec-table-wrap" style="margin: 0;">
        <table class="spec-table">
          <tbody>
            <tr>
              <td style="width: 25%;"><strong>Target Age Group</strong></td>
              <td>Ages 6 to 11 (as of 1 April of the academic session)</td>
            </tr>
            <tr>
              <td><strong>Curriculum Framework</strong></td>
              <td>Mapped chapter-by-chapter to NCERT, NEP 2020 & NCF ready</td>
            </tr>
            <tr>
              <td><strong>Live Class Hours</strong></td>
              <td>3 to 4 engaging hours per day with short off-screen movement breaks</td>
            </tr>
            <tr>
              <td><strong>Learning Days & Batches</strong></td>
              <td>Monday to Friday. Morning, Afternoon, and Evening IST batch options</td>
            </tr>
            <tr>
              <td><strong>Class Size & Ratio</strong></td>
              <td>Strict maximum ratio of 1:20 students per qualified live teacher</td>
            </tr>
            <tr>
              <td><strong>Certificates & Recognition</strong></td>
              <td>Annual grade completion certificate + valid Transfer Certificate (TC)</td>
            </tr>
            <tr>
              <td><strong>Accreditations</strong></td>
              <td>IAO Accredited • ISSO Sports Member • Oxford Quality Partner</td>
            </tr>
            <tr>
              <td><strong>Admissions Status</strong></td>
              <td>Open for 2026–27 session. Mid-term admissions accepted for Classes 1 to 5.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- CTA to Deep Curriculum Page -->
    <div style="text-align: center;">
      <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.85rem 2.5rem;">
        Explore Full Stage-Wise Curriculum Guide (K to Grade 8) &rarr;
      </a>
    </div>

  </div>
</section>

<!-- 5. Section: Special Education (Inclusive Learning) -->
<section id="special-education" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 820px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Inclusive by Design
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        <?php echo h($special_ed['title']); ?>
      </h2>
      <p style="color: var(--color-gold); font-size: 1.15rem; font-weight: 700; margin-top: 0.35rem; font-family: var(--font-primary);">
        “<?php echo h($special_ed['kicker']); ?>”
      </p>
      <p style="color: var(--color-text); font-size: 1rem; line-height: 1.75; margin-top: 1rem;">
        <?php echo h($special_ed['intro']); ?>
      </p>
    </div>

    <!-- 6 Benefit Pillars Grid -->
    <div class="inclusive-pillar-grid" style="margin-bottom: 3.5rem;">
      <?php foreach ($special_ed['pillars'] as $pillar): ?>
        <div class="inclusive-pillar-card">
          <div style="font-size: 1.6rem; color: var(--color-teal); margin-bottom: 0.75rem;">🌱</div>
          <h3 style="font-size: 1.18rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">
            <?php echo h($pillar['title']); ?>
          </h3>
          <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
            <?php echo h($pillar['desc']); ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Educational Needs Learning Framework -->
    <div style="background: var(--pastel-blue); border: 1.5px solid rgba(6, 43, 99, 0.16); border-radius: var(--radius-lg); padding: 3rem 2.5rem; margin-bottom: 3.5rem;">
      <div class="grid-2" style="align-items: center; gap: 3rem;">
        <div>
          <span style="font-size: 0.82rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 0.5rem;">
            Tailored Support Systems
          </span>
          <h3 style="font-size: 2rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1rem; line-height: 1.3;">
            Educational Needs Learning Framework
          </h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.75; margin-bottom: 1.25rem;">
            Our certified Special Educators craft structured Individualised Education Plans (IEPs) for learners requiring differentiated support:
          </p>
          <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.92rem; color: var(--color-navy);">
            <li><strong>• ADHD Support:</strong> Short lesson intervals, movement breaks, high sensory engagement.</li>
            <li><strong>• Autism Spectrum:</strong> Predictable daily routines, clear visual cues, zero social overwhelm.</li>
            <li><strong>• Dyslexia & Delayed Learning:</strong> Multi-sensory reading tools, extra processing time, oral checks.</li>
            <li><strong>• Gifted Learners:</strong> Accelerated academic enrichment pathways and advanced projects.</li>
          </ul>
        </div>
        <div style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.16); border-radius: var(--radius-md); padding: 2rem; text-align: center;">
          <h4 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">
            Learning Without Labels
          </h4>
          <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem;">
            Every child deserves to be seen for who they are and what they can achieve. Speak directly with our inclusive education specialists.
          </p>
          <a href="/contact" class="btn btn-primary" style="background-color: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; width: 100%;">
            Book Special Educator Consultation
          </a>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- 6. Section: Electives & Global Languages -->
<section id="electives" class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 780px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Broadening Horizons
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Electives & Language Pathways
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Empowering students to explore cultural heritage, global tongues, and 21st-century skill clubs.
      </p>
    </div>

    <!-- Languages Grid -->
    <div class="electives-grid-wrap" style="margin-bottom: 3.5rem;">
      <div class="elective-language-card">
        <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">
          🇮🇳 Regional & Classical Languages
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1rem;">
          Preserving deep cultural connections and bilingual literacy through interactive conversational and grammar modules.
        </p>
        <div class="language-tags">
          <?php foreach ($electives['regional_languages'] as $rl): ?>
            <span class="lang-tag"><?php echo h($rl); ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="elective-language-card">
        <h3 style="font-size: 1.3rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">
          🌍 Modern Foreign Languages
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1rem;">
          Cultivating international fluency, cultural appreciation, and global readiness from early primary grades.
        </p>
        <div class="language-tags">
          <?php foreach ($electives['foreign_languages'] as $fl): ?>
            <span class="lang-tag"><?php echo h($fl); ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Future Skills Electives -->
    <div>
      <div class="text-center" style="margin-bottom: 2.5rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Co-Curricular Clubs
        </span>
        <h3 style="font-size: 1.85rem; color: var(--color-navy); margin-top: 0.35rem; font-family: var(--font-primary);">
          Skill Classes & Beyond Academics
        </h3>
      </div>

      <div class="grid-3" style="gap: 1.5rem;">
        <?php foreach ($electives['future_skills'] as $fs): ?>
          <div class="card" style="padding: 1.75rem; background: #FFFFFF; border-radius: var(--radius-md);">
            <h4 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.5rem;">
              ⭐ <?php echo h($fs['name']); ?>
            </h4>
            <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6; margin: 0;">
              <?php echo h($fs['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<!-- 7. Section: NEP 2020 & Policy Framework -->
<section id="nep-2020" class="section" style="background-color: #FFFFFF; border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 780px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        National Policy
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        <?php echo h($nep_data['title']); ?>
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        <?php echo h($nep_data['desc']); ?>
      </p>
    </div>

    <div class="grid-3" style="gap: 2rem; margin-bottom: 3.5rem;">
      <div class="card" style="padding: 2rem; background: var(--pastel-blue); border-radius: var(--radius-md);">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
          5+3+3+4 Pedagogical Structure
        </h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
          Restructured academic milestones replacing 10+2: Foundational (Ages 3–8), Preparatory (Ages 8–11), Middle (Ages 11–14), and Secondary (Ages 14–18).
        </p>
      </div>

      <div class="card" style="padding: 2rem; background: var(--pastel-yellow); border-radius: var(--radius-md);">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
          Competency-Based Learning
        </h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
          Evaluating conceptual understanding, critical application, and problem solving over mechanical rote testing and passive memorization.
        </p>
      </div>

      <div class="card" style="padding: 2rem; background: var(--pastel-green); border-radius: var(--radius-md);">
        <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin-bottom: 0.75rem;">
          Experiential & Thematic Inquiry
        </h3>
        <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.65; margin: 0;">
          Cross-cutting curriculum themes seamlessly linking science, mathematics, geography, literature, and art into real-world projects.
        </p>
      </div>
    </div>

    <!-- Official NEP 2020 PDF Resource Card -->
    <div class="resource-download-card">
      <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="font-size: 2.5rem;">📜</div>
        <div>
          <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin: 0 0 0.35rem 0;">
            <?php echo h($nep_data['pdf_title']); ?>
          </h3>
          <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
            Official policy publication detailing foundational literacy, virtual schooling standards, and assessment reform.
          </p>
        </div>
      </div>
      <div>
        <a href="<?php echo h($nep_data['pdf_url']); ?>" target="_blank" rel="noopener" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; white-space: nowrap;">
          View / Download PDF &darr;
        </a>
      </div>
    </div>

  </div>
</section>

<!-- 8. Section: Resources & Academic Calendar -->
<section id="resources" class="section" style="background-color: var(--color-surface-warm); border-bottom: 1px solid var(--color-border); padding: 5rem 0;">
  <div class="container">
    <div class="text-center" style="max-width: 780px; margin: 0 auto 3.5rem auto;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
        Academic Assets
      </span>
      <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
        Learning Resources & Calendar
      </h2>
      <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
        Curated reference materials, digital readers, and annual academic dates.
      </p>
    </div>

    <div class="grid-2" style="gap: 2rem;">
      <div class="card" style="padding: 2.25rem; background: #FFFFFF; border-radius: var(--radius-md);">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">📚</div>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
          Oxford Quality Content Books
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1.25rem;">
          In collaboration with Oxford University Press, our learners receive graded workbooks, thematic readers, and interactive digital activities benchmarked to world-class pedagogical criteria.
        </p>
        <span style="color: var(--color-teal); font-weight: 600; font-size: 0.88rem;">✓ Included in comprehensive student learning kit</span>
      </div>

      <div class="card" style="padding: 2.25rem; background: #FFFFFF; border-radius: var(--radius-md);">
        <div style="font-size: 2rem; margin-bottom: 0.75rem;">🗓️</div>
        <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
          <?php echo h($resources_data['calendar_title']); ?>
        </h3>
        <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1.25rem;">
          <?php echo h($resources_data['calendar_desc']); ?>
        </p>
        <a href="<?php echo h($resources_data['calendar_pdf']); ?>" target="_blank" rel="noopener" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; font-size: 0.85rem;">
          Download Academic Calendar (PDF) &darr;
        </a>
      </div>
    </div>

  </div>
</section>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
