<?php
// Zuvio Global School - Dedicated Technology Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// CMS synchronization
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

$tech = $ac_cms['technology'] ?? [
    'hero_title' => 'Technology Built for Real Learning',
    'hero_subtitle' => 'Our Digital Learning Ecosystem',
    'hero_desc' => 'At Zuvio Global School, technology is never a passive screen. It is an active workspace for curiosity, collaboration, and creative mastery powered by an enterprise-grade online learning environment.',
    'lms_video' => '/assets/images/03_Science_Experiment_Learning.mp4',
    'lms_features' => [
        ['title' => 'Child-Friendly and User-Focused', 'desc' => 'Intentionally designed for young and growing learners, ensuring every student can navigate timetables, classes, and tasks with ease.', 'icon' => '🧒'],
        ['title' => 'Live, Interactive Classes', 'desc' => 'Real-time daily connections with teachers and peers using live video, digital whiteboards, breakout rooms, and interactive polls.', 'icon' => '💻'],
        ['title' => 'Safety and Security First', 'desc' => 'End-to-end encrypted and moderated virtual classrooms ensuring a safe, respectful, and distraction-free online learning environment.', 'icon' => '🛡️'],
        ['title' => 'Digital Library at Fingertips', 'desc' => 'Instant access to curriculum textbooks in PDF, Oxford graded readers, interactive workbooks, and thematic research guides.', 'icon' => '📚'],
        ['title' => 'All-in-One Convenience', 'desc' => 'From live schedules and assignment submissions to formative gradebooks and progress reports — everything is one click away.', 'icon' => '⚡'],
        ['title' => 'Diverse Learning Resources', 'desc' => 'Catering to visual, auditory, and kinesthetic learners with video lessons, audio podcasts, and hands-on simulation modules.', 'icon' => '🎨']
    ]
];

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

$parent_portal = $ac_cms['parent_portal'] ?? [
    ['title' => 'Real-Time Insights & AI Analytics', 'desc' => 'Track your child’s academic progress, attendance streaks, and learning engagement effortlessly with automated progress metrics.', 'icon' => '📊'],
    ['title' => 'Seamless Information Access', 'desc' => 'Access daily class timetables, upcoming holiday calendars, circulars, and teacher notices at any hour of the day.', 'icon' => '🗓️'],
    ['title' => 'Hassle-Free Fee Management', 'desc' => 'Secure online fee payments, instant downloadable receipts, and transparent fee schedules without paperwork.', 'icon' => '💳'],
    ['title' => 'Direct Teacher Communication', 'desc' => 'Open, constructive dialogue with mentors and subject teachers plus monthly Parent-Teacher Meetings (PTMs).', 'icon' => '🤝']
];

$page_slug = 'technology';
$seo = [
    'seo_title' => 'Learning Technology & LMS Ecosystem | Zuvio Global School',
    'meta_description' => 'Explore Zuvio’s cutting-edge digital learning ecosystem: intuitive LMS, safe virtual classrooms, teacher & student EdTech tools, and real-time parent portal.',
    'canonical_url' => BASE_URL . '/technology',
    'og_title' => 'Learning Technology & LMS Ecosystem — Zuvio Global School',
    'og_description' => 'Technology built for real learning. Interactive LMS, live classrooms, Google Workspace, EdTech suites, and transparent parent oversight.',
    'og_image' => '/assets/images/03_Science_Experiment_Learning.mp4',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Academics', 'url' => '/academics'],
    ['label' => 'Technology']
]);
?>

<main class="technology-page">
  <!-- Hero Section -->
  <section class="section-hero" style="background-color: var(--pastel-blue); padding: 5rem 1.5rem; text-align: center; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 850px;">
      <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">
        <?php echo h($tech['hero_subtitle'] ?? 'Our Digital Learning Ecosystem'); ?>
      </span>
      <h1 style="font-size: 2.85rem; font-family: var(--font-primary); margin-bottom: 1rem; color: var(--color-navy-dark); font-weight: 700; line-height: 1.2;">
        <?php echo h($tech['hero_title']); ?>
      </h1>
      <p style="font-size: 1.15rem; font-weight: 500; line-height: 1.6; color: var(--color-text); margin: 0 auto; max-width: 740px;">
        <?php echo h($tech['hero_desc']); ?>
      </p>
    </div>
  </section>

  <!-- LMS Video & Core Philosophy -->
  <section class="section" style="background-color: #FFFFFF; padding: 5.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: center;">
        <div>
          <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
            Learning Management System
          </span>
          <h2 style="font-size: 2.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.25rem; line-height: 1.3;">
            An Intuitive, Purpose-Built Digital Campus
          </h2>
          <p style="color: var(--color-text); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.5rem;">
            Unlike standard video-call platforms, Zuvio's unified LMS is engineered specifically for early and growing learners. It brings live sessions, digital whiteboards, assignments, reading materials, and teacher feedback into a single, uncluttered environment.
          </p>
          <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--color-navy); font-weight: 600;">
              <span style="color: var(--color-teal); font-size: 1.2rem;">✓</span> One-click login with single sign-on (SSO)
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--color-navy); font-weight: 600;">
              <span style="color: var(--color-teal); font-size: 1.2rem;">✓</span> Automated lesson recording for missed-class revision
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--color-navy); font-weight: 600;">
              <span style="color: var(--color-teal); font-size: 1.2rem;">✓</span> Built-in breakout rooms for peer collaboration
            </div>
          </div>
        </div>

        <div>
          <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); background: #000;">
            <video controls autoplay muted loop playsinline style="width: 100%; height: auto; display: block;">
              <source src="<?php echo h($tech['lms_video'] ?? '/assets/images/03_Science_Experiment_Learning.mp4'); ?>" type="video/mp4">
              Your browser does not support HTML5 video.
            </video>
          </div>
          <p style="font-size: 0.85rem; color: var(--color-muted); text-align: center; margin-top: 0.75rem;">
            Preview: Engaging live scientific exploration in the Zuvio digital classroom.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- 6 LMS Pillars -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          LMS Capabilities
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Core Features of the Learning Platform
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Carefully configured to support focus, active participation, and deep understanding.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.75rem;">
        <?php foreach ($tech['lms_features'] as $feat): ?>
          <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="font-size: 2.2rem; margin-bottom: 1rem;"><?php echo h($feat['icon']); ?></div>
              <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">
                <?php echo h($feat['title']); ?>
              </h3>
              <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                <?php echo h($feat['desc']); ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CRM & ERP Infrastructure -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Operational Backbone
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Integrated CRM &amp; Student ERP
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Institutional-grade administration ensuring transparency, data security, and seamless communications.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
        <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-teal);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">📋</div>
          <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">Admissions CRM</h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1rem;">
            From initial enquiry to enrollment, our admissions CRM provides families with instant updates, digital document verification, and dedicated counseling schedules.
          </p>
          <ul style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; padding-left: 1.25rem; margin: 0;">
            <li>Zero paperwork enrollment &amp; verification</li>
            <li>Direct WhatsApp &amp; Email status updates</li>
            <li>Personalized counseling appointments</li>
          </ul>
        </div>

        <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2.5rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold);">
          <div style="font-size: 2rem; margin-bottom: 0.75rem;">🏫</div>
          <h3 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.75rem;">Student Management ERP</h3>
          <p style="color: var(--color-text); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1rem;">
            A centralized student information system tracking attendance logs, term grades, report cards, and fee receipts with bank-level encryption.
          </p>
          <ul style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; padding-left: 1.25rem; margin: 0;">
            <li>Real-time automated attendance records</li>
            <li>Cumulative academic performance transcripts</li>
            <li>Encrypted student records &amp; privacy compliance</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- EdTech Tools for Teaching & Learning -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Curated EdTech Suite
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Tools for Students &amp; Educators
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          We integrate the world's most effective educational software to elevate engagement in every lesson.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem;">
        <?php foreach ($teacher_tools as $cat): ?>
          <div style="background-color: #FFFFFF; border-radius: var(--radius-md); padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 1.25rem; border-bottom: 2px solid var(--color-gold); padding-bottom: 0.5rem;">
              <?php echo h($cat['category']); ?>
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
              <?php foreach ($cat['tools'] as $tool): ?>
                <div>
                  <div style="font-weight: 700; color: var(--color-teal); font-size: 0.95rem;">
                    <?php echo h($tool['name']); ?>
                  </div>
                  <div style="color: var(--color-text); font-size: 0.85rem; line-height: 1.5;">
                    <?php echo h($tool['desc']); ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Parent Portal Insights -->
  <section class="section" style="background-color: #FFFFFF; padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div class="text-center" style="max-width: 750px; margin: 0 auto 3.5rem auto;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Family Oversight
        </span>
        <h2 style="font-size: 2.35rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          The Zuvio Parent Portal
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Parents are true partners in education with 24/7 visibility into their child’s learning growth.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
        <?php foreach ($parent_portal as $p): ?>
          <div style="background-color: var(--color-surface-warm); border-radius: var(--radius-md); padding: 2rem 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16); box-shadow: var(--shadow-sm);">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;"><?php echo h($p['icon']); ?></div>
            <h3 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-primary); margin-bottom: 0.5rem;">
              <?php echo h($p['title']); ?>
            </h3>
            <p style="color: var(--color-text); font-size: 0.9rem; line-height: 1.6; margin: 0;">
              <?php echo h($p['desc']); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Hardware & Device Specification Table -->
  <section class="section" style="background-color: var(--pastel-blue); padding: 5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container" style="max-width: 900px;">
      <div class="text-center" style="margin-bottom: 2.5rem;">
        <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">
          Setup Checklist
        </span>
        <h2 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">
          Recommended Hardware &amp; Internet Specs
        </h2>
        <p style="color: var(--color-muted); font-size: 1rem; margin-top: 0.5rem;">
          Straightforward setup requirements so any home can become a world-class learning space.
        </p>
      </div>

      <div style="background: #FFFFFF; border-radius: var(--radius-md); border: 1.5px solid rgba(6, 43, 99, 0.16); overflow: hidden; box-shadow: var(--shadow-sm);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
          <thead>
            <tr style="background-color: var(--color-navy); color: #FFFFFF;">
              <th style="padding: 1rem 1.5rem; font-family: var(--font-primary);">Component</th>
              <th style="padding: 1rem 1.5rem; font-family: var(--font-primary);">Minimum Requirement</th>
              <th style="padding: 1rem 1.5rem; font-family: var(--font-primary);">Recommended</th>
            </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid var(--color-border);">
              <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--color-navy);">Device</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-text);">Laptop / Desktop / iPad (10"+)</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-teal); font-weight: 600;">Laptop (Windows 11 or macOS, 8GB RAM)</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--color-border); background-color: var(--color-surface-warm);">
              <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--color-navy);">Internet Speed</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-text);">10 Mbps stable broadband</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-teal); font-weight: 600;">25+ Mbps high-speed fiber broadband</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--color-border);">
              <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--color-navy);">Audio &amp; Mic</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-text);">Built-in microphone &amp; speakers</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-teal); font-weight: 600;">Noise-canceling USB or 3.5mm headset</td>
            </tr>
            <tr>
              <td style="padding: 1rem 1.5rem; font-weight: 600; color: var(--color-navy);">Webcam</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-text);">720p HD integrated camera</td>
              <td style="padding: 1rem 1.5rem; color: var(--color-teal); font-weight: 600;">1080p Full HD external webcam</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Cross Navigation & Demo CTA -->
  <section class="section" style="background-color: #FFFFFF; padding: 4.5rem 0; border-bottom: 1px solid var(--color-border);">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); border-radius: var(--radius-lg); padding: 3.5rem 2.5rem; color: #FFFFFF; text-align: center; border: 1.5px solid rgba(212, 175, 55, 0.3); box-shadow: var(--shadow-lg);">
        <h2 style="font-size: 2.2rem; color: #FFFFFF; font-family: var(--font-primary); margin-bottom: 1rem;">
          Experience Our Virtual Classroom in Action
        </h2>
        <p style="max-width: 650px; margin: 0 auto 2rem auto; color: rgba(255, 255, 255, 0.9); font-size: 1.05rem; line-height: 1.7;">
          Book a personalized 1-on-1 walkthrough with our academic counselor to test drive the LMS and discover our curriculum.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="/curriculum" class="btn btn-primary" style="background-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; border: none; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Curriculum Pathways &rarr;
          </a>
          <a href="/special-education" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid #FFFFFF; font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Special Education
          </a>
          <a href="/resources" class="btn" style="background-color: transparent; color: #FFFFFF; border: 1.5px solid rgba(255, 255, 255, 0.6); font-weight: 600; padding: 0.85rem 1.75rem; border-radius: var(--radius-sm); text-decoration: none;">
            Academic Resources
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once dirname(__FILE__) . '/../includes/footer.php'; ?>
