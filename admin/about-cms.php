<?php
// Zuvio Global School - Admin About Us CMS Manager (2-Column Page Section Editor)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'story';

// Persistent CMS Storage (MySQL database with session fallback)
$db_saved = get_json_setting('cms_about', []);
if (!isset($_SESSION['mock_about_cms']) || !empty($db_saved)) {
    $_SESSION['mock_about_cms'] = !empty($db_saved) ? $db_saved : [];
}
$cms = &$_SESSION['mock_about_cms'];

// 1. Defaults if empty
if (!isset($cms['story'])) {
    $cms['story'] = [
        'is_active' => 1,
        'title' => 'Learning Without Boundaries, Growing With Purpose',
        'subtitle' => 'About Zuvio',
        'content' => "Zuvio began with a simple observation: too many children are asked to fit into a system, rather than the system being designed to fit the child.\n\nTraditional schooling often requires conformity over curiosity, rigid schedules over natural rhythms, and a one-size-fits-all approach that leaves many students underserved — whether they need more time to master a concept, more room to run ahead, or simply an environment where they feel safe and understood.\n\nZuvio Global School was founded to offer an alternative — not an alternative that compromises on quality, but one that raises the bar for what education can be.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers, and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere in the world.",
        'image' => '/assets/images/about_us_hero.webp',
        'vision' => 'To redefine the future of education by creating a dynamic, borderless learning environment where students from every corner of the world can thrive academically, think critically, and evolve into compassionate, future-ready global leaders.',
        'mission' => 'Our mission is to revolutionize education through a cutting-edge online learning platform that integrates futuristic teaching methods, personalized pathways, and holistic development to unlock the unique potential of every child.'
    ];
}
if (!isset($cms['vision_mission'])) {
    $cms['vision_mission'] = [
        'is_active' => 1,
        'vision' => $cms['story']['vision'] ?? 'To redefine the future of education by creating a dynamic, borderless learning environment...',
        'mission' => $cms['story']['mission'] ?? 'Our mission is to revolutionize education through a cutting-edge online learning platform...'
    ];
}

if (!isset($cms['values'])) {
    $cms['values'] = [
        'is_active' => 1,
        'badge' => 'Our Guiding Principles',
        'title' => 'What Matters Most at Zuvio',
        'items' => [
            ['id' => 1, 'title' => 'Child at the Centre', 'desc' => 'Every child is an individual, not a cohort. Their strengths, pace, and interests shape the journey.', 'icon' => '🎯', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Inclusion by Design', 'desc' => 'An environment built from day one to welcome every kind of mind — neurotypical, neurodivergent, gifted, or simply different.', 'icon' => '🤝', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Personalised Learning', 'desc' => 'Learning pathways that flex to fit the student, not rigid timetables that force students into a mould.', 'icon' => '🌱', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Growth Not Just Marks', 'desc' => 'Academic achievement matters deeply, but so does confidence, critical thinking, emotional resilience, and character.', 'icon' => '📈', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'Beyond Academics', 'desc' => 'A complete school experience — clubs, sports, competitions, exhibitions, and real-world life skills.', 'icon' => '🎨', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Learning Without Boundaries', 'desc' => 'Quality education that travels with the child. Accessible from anywhere in the world.', 'icon' => '🌍', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

if (!isset($cms['apart'])) {
    $cms['apart'] = [
        'is_active' => 1,
        'badge' => 'The Zuvio Difference',
        'title' => 'What Sets Us Apart',
        'items' => [
            ['id' => 1, 'title' => 'Online but Deeply Human', 'desc' => 'Small interactive live classes, dedicated mentors, and real relationships — never pre-recorded video lectures.', 'tag' => 'Human Touch', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Personalised by Default', 'desc' => 'Customised pace, targeted support, and pathways tailored to each child’s unique learning style and needs.', 'tag' => 'Tailored Pace', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Inclusive by Design', 'desc' => 'Specialised support, SEN certified educators, and a culture where every learner belongs and flourishes.', 'tag' => 'Neuroinclusive', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Flexible for Real Life', 'desc' => 'Timetables and structures that support families traveling, student athletes, artists, and homeschooling paths.', 'tag' => 'Anytime Anywhere', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'Beyond Academics', 'desc' => 'Holistic co-curricular programmes, leadership clubs, debate, coding, and sports integration through ISSO.', 'tag' => '360° Growth', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Parents as Partners', 'desc' => 'Transparent progress tracking, regular open dialogues, and collaborative goal setting for student success.', 'tag' => 'Collaborative', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

if (!isset($cms['approach'])) {
    $cms['approach'] = [
        'is_active' => 1,
        'badge' => 'Educational Framework',
        'title' => 'The ZUVIO Approach',
        'items' => [
            ['letter' => 'Z', 'title' => 'Zoomed-In Attention', 'desc' => 'Small cohorts, frequent individual check-ins, and dedicated teacher focus ensuring no child is overlooked.', 'sort_order' => 1, 'is_published' => 1],
            ['letter' => 'U', 'title' => 'Understand Every Learner', 'desc' => 'Diagnostic assessments that recognise cognitive strengths, emotional needs, and individual learning preferences.', 'sort_order' => 2, 'is_published' => 1],
            ['letter' => 'V', 'title' => 'Versatile Pathways', 'desc' => 'Flexible curriculum choices, customizable pacing, and elective enrichment tailored to future aspirations.', 'sort_order' => 3, 'is_published' => 1],
            ['letter' => 'I', 'title' => 'Inclusive by Design', 'desc' => 'Neurodivergent support, SEN-trained educators, and differentiated instruction welcoming all minds.', 'sort_order' => 4, 'is_published' => 1],
            ['letter' => 'O', 'title' => 'Opportunities Without Boundaries', 'desc' => 'Global student peers, international olympiads, and borderless learning accessible anywhere on Earth.', 'sort_order' => 5, 'is_published' => 1]
        ]
    ];
}

if (!isset($cms['audiences'])) {
    $cms['audiences'] = [
        'is_active' => 1,
        'badge' => 'Target Learners',
        'title' => 'Who Should Choose Zuvio',
        'items' => [
            ['id' => 1, 'title' => 'Flexible Learning Families', 'desc' => 'Families seeking flexible learning schedules that adapt seamlessly to family lifestyle, commitments, and relocation.', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Homeschooling Families', 'desc' => 'Alternative-learning and homeschooling families looking for structured, recognized international curriculum accreditation.', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Globally Mobile & Expats', 'desc' => 'Expat, diplomatic, and traveling families requiring continuous, uninterrupted schooling with recognized global credentials.', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Young Athletes & Performers', 'desc' => 'Students pursuing competitive sports, fine arts, music, or performance careers needing rigorous yet adaptable academics.', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'Calm-Environment Learners', 'desc' => 'Children who flourish better in calm, distraction-free, supportive online settings free from traditional classroom anxiety.', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Personalised Pace Seekers', 'desc' => 'Students who want to accelerate in areas of strength or take measured, dedicated time to master challenging concepts.', 'sort_order' => 6, 'is_published' => 1],
            ['id' => 7, 'title' => 'Future-Ready Seekers', 'desc' => 'Parents prioritising 21st-century critical thinking, ethical digital literacy, communication, and emotional resilience.', 'sort_order' => 7, 'is_published' => 1],
            ['id' => 8, 'title' => 'Neuroinclusive Needs', 'desc' => 'Children with ADHD, autism, or delayed learning who thrive with individualized attention, patience, and expert guidance.', 'sort_order' => 8, 'is_published' => 1]
        ]
    ];
}

if (!isset($cms['founder_msg'])) {
    $cms['founder_msg'] = [
        'is_active' => 1,
        'badge' => "Leadership Thought",
        'title' => 'Learning Without Boundaries. Growing With Purpose.',
        'salutation' => 'Dear Parents, Students and Members of the Zuvio Community,',
        'paragraphs' => "Education today must prepare children not only for examinations, but for a world that is constantly evolving.\n\nAt Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.\n\nOur vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.\n\nAt Zuvio, strong academics form the foundation, but learning goes much further. We encourage our students to question, think critically, communicate confidently, collaborate, create and apply their knowledge to real-world situations. Technology enables our classrooms, but teachers, relationships and meaningful human interaction remain at the heart of the learning experience.\n\nWe recognise that every child is different. Their interests, abilities, pace and aspirations are unique. Our approach therefore aims to create a learning journey that gives students the flexibility to discover their strengths while developing the knowledge, skills and values required for the future.\n\nWe also believe education is a partnership. Parents, educators and students must work together to create an environment in which children feel supported, inspired and empowered to take ownership of their learning.\n\nZuvio Global School is not simply about bringing a traditional classroom online. We are reimagining how learning can happen when boundaries are removed and possibilities are expanded.\n\nOur aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.\n\nWelcome to Zuvio Global School — a global learning community where every child is encouraged to learn, explore, create and grow without boundaries.",
        'signoff_name' => 'Founder',
        'signoff_org' => 'Zuvio Global School',
        'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'
    ];
}

if (!isset($cms['awards'])) {
    $cms['awards'] = [
        'is_active' => 1,
        'badge' => 'Excellence Benchmarks',
        'title' => 'Awards & Global Recognition',
        'items' => [
            ['id' => 1, 'title' => 'Best E-School of 2023', 'org' => 'Global Education Summit', 'desc' => 'Recognized for pioneering digital school infrastructure and interactive cohort pedagogy.', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'National School Award', 'org' => 'Education Excellence Forum', 'desc' => 'Awarded for exceptional commitment to student-centric online learning and curriculum rigor.', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'International Icon Awards 2025', 'org' => 'Global EdTech Leadership', 'desc' => 'Honored for transformative leadership in inclusive and neurodivergent-friendly education.', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Featured in Global Media', 'org' => 'Education World & Top Portals', 'desc' => 'Celebrated across leading publications for breaking geographical barriers in K–12 education.', 'sort_order' => 4, 'is_published' => 1]
        ]
    ];
}

if (!isset($cms['cta'])) {
    $cms['cta'] = [
        'is_active' => 1,
        'badge' => 'Take the Next Step',
        'title' => 'Ready to Explore Zuvio for Your Child?',
        'subtitle' => 'Schedule an online interaction with our academic advisors to understand our personalized learning pathways.',
        'btn_primary_text' => 'Book Free Counselling',
        'btn_primary_url' => '/contact',
        'btn_secondary_text' => 'Explore Academics',
        'btn_secondary_url' => '/academics'
    ];
}

// 8 Sections Ordered Exactly as on About Page
$sections_nav = [
    'story' => ['num' => 1, 'name' => 'About Zuvio Story & Vision', 'icon' => '📖'],
    'values' => ['num' => 2, 'name' => 'Core Values (6)', 'icon' => '💎'],
    'apart' => ['num' => 3, 'name' => 'What Sets Us Apart (6)', 'icon' => '✨'],
    'approach' => ['num' => 4, 'name' => 'The ZUVIO Approach (5)', 'icon' => '🎯'],
    'audiences' => ['num' => 5, 'name' => 'Who Should Choose (8)', 'icon' => '👥'],
    'founder_msg' => ['num' => 6, 'name' => "Founder's Message", 'icon' => '🖋️'],
    'awards' => ['num' => 7, 'name' => 'Awards & Recognition', 'icon' => '🏆'],
    'cta' => ['num' => 8, 'name' => 'Conversion CTA Banner', 'icon' => '🚀'],
];

if (!isset($sections_nav[$tab])) {
    $tab = 'story';
}
$current_sec = $cms[$tab] ?? [];

// POST Action Handlers (Section Save, Remove, Restore)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_section'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $s_key = trim($_POST['section_key'] ?? $tab);
        $pending_action = trim($_POST['pending_action'] ?? 'save');
        
        if (isset($cms[$s_key])) {
            if ($pending_action === 'remove') {
                $cms[$s_key]['is_removed'] = 1;
                $cms[$s_key]['is_active'] = 0;
            } elseif ($pending_action === 'restore') {
                $cms[$s_key]['is_removed'] = 0;
                $cms[$s_key]['is_active'] = 1;
            } else {
                $cms[$s_key]['is_active'] = isset($_POST['is_active']) ? 1 : 0;
                $cms[$s_key]['is_removed'] = 0;
            }

            // Save Specific Section Content
            if ($s_key === 'story') {
                $cms['story']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $cms['story']['title'] = trim($_POST['title'] ?? '');
                $cms['story']['content'] = trim($_POST['content'] ?? '');
                $cms['story']['image'] = trim($_POST['image'] ?? '');
                $cms['story']['vision'] = trim($_POST['vision'] ?? '');
                $cms['story']['mission'] = trim($_POST['mission'] ?? '');
                $cms['vision_mission']['vision'] = $cms['story']['vision'];
                $cms['vision_mission']['mission'] = $cms['story']['mission'];
            } elseif ($s_key === 'values') {
                $cms['values']['badge'] = trim($_POST['badge'] ?? '');
                $cms['values']['title'] = trim($_POST['title'] ?? '');
            } elseif ($s_key === 'apart') {
                $cms['apart']['badge'] = trim($_POST['badge'] ?? '');
                $cms['apart']['title'] = trim($_POST['title'] ?? '');
            } elseif ($s_key === 'approach') {
                $cms['approach']['badge'] = trim($_POST['badge'] ?? '');
                $cms['approach']['title'] = trim($_POST['title'] ?? '');
            } elseif ($s_key === 'audiences') {
                $cms['audiences']['badge'] = trim($_POST['badge'] ?? '');
                $cms['audiences']['title'] = trim($_POST['title'] ?? '');
            } elseif ($s_key === 'founder_msg') {
                $cms['founder_msg']['badge'] = trim($_POST['badge'] ?? '');
                $cms['founder_msg']['title'] = trim($_POST['title'] ?? '');
                $cms['founder_msg']['salutation'] = trim($_POST['salutation'] ?? '');
                $cms['founder_msg']['paragraphs'] = trim($_POST['paragraphs'] ?? '');
                $cms['founder_msg']['signoff_name'] = trim($_POST['signoff_name'] ?? '');
                $cms['founder_msg']['signoff_org'] = trim($_POST['signoff_org'] ?? '');
                $cms['founder_msg']['image'] = trim($_POST['image'] ?? '');
            } elseif ($s_key === 'awards') {
                $cms['awards']['badge'] = trim($_POST['badge'] ?? '');
                $cms['awards']['title'] = trim($_POST['title'] ?? '');
            } elseif ($s_key === 'cta') {
                $cms['cta']['badge'] = trim($_POST['badge'] ?? '');
                $cms['cta']['title'] = trim($_POST['title'] ?? '');
                $cms['cta']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $cms['cta']['btn_primary_text'] = trim($_POST['btn_primary_text'] ?? '');
                $cms['cta']['btn_primary_url'] = trim($_POST['btn_primary_url'] ?? '');
                $cms['cta']['btn_secondary_text'] = trim($_POST['btn_secondary_text'] ?? '');
                $cms['cta']['btn_secondary_url'] = trim($_POST['btn_secondary_url'] ?? '');
            }

            set_json_setting('cms_about', $cms, 'About Us Page CMS Content');
            $_SESSION['mock_about_cms'] = $cms;
            $redirect_msg = ($pending_action === 'remove') ? 'removed' : (($pending_action === 'restore') ? 'restored' : 'saved');
            header("Location: /admin/about-cms.php?tab=" . urlencode($s_key) . "&msg=" . $redirect_msg);
            exit;
        }
    }
}

$page_slug = 'admin-about-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="max-width: 1200px; margin: 0 auto;">

  <!-- Page Header -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
        About Us Page Sections CMS
      </h1>
      <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
        Manage all 8 sections of the About Us page in the exact visual sequence they appear. Enable, disable, remove, and save each section independently.
      </p>
    </div>

    <div style="display: flex; gap: 0.75rem;">
      <a href="/admin/profiles.php" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem; border-color: var(--color-navy); color: var(--color-navy);">
        👥 Manage Team Profiles
      </a>
      <a href="/about" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        View Live About Page ↗
      </a>
    </div>
  </div>

  <!-- Notices -->
  <?php if ($msg === 'saved'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Saved!</strong> Section <strong>"<?php echo h($sections_nav[$tab]['name']); ?>"</strong> has been successfully updated and synced to the website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php elseif ($msg === 'removed'): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Section Removed:</strong> <strong>"<?php echo h($sections_nav[$tab]['name']); ?>"</strong> has been removed from the live website. Click "Restore Section" anytime to bring it back.</span>
      <span style="font-size: 0.75rem; color: #DC2626;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php elseif ($msg === 'restored'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Section Restored:</strong> <strong>"<?php echo h($sections_nav[$tab]['name']); ?>"</strong> has been restored and made available on the live website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Error:</strong> <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <!-- 2-Column Layout: Left Sidebar + Right Section Editor -->
  <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: flex-start;">
    
    <!-- LEFT SIDEBAR: 8 ORDERED SECTIONS -->
    <div class="card" style="padding: 1rem; border: 1.5px solid rgba(6, 43, 99, 0.12); background: #FFFFFF; border-radius: var(--radius-md); position: sticky; top: 1.5rem;">
      <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--color-gold); letter-spacing: 1px; padding: 0.5rem 0.75rem 0.75rem 0.75rem; border-bottom: 1px solid var(--color-border); margin-bottom: 0.5rem;">
        About Page Sequence (1–8)
      </div>

      <div style="display: flex; flex-direction: column; gap: 0.25rem; max-height: calc(100vh - 180px); overflow-y: auto;">
        <?php foreach ($sections_nav as $s_k => $s_meta): 
          $is_current = ($tab === $s_k);
          $s_removed = !empty($cms[$s_k]['is_removed']);
          $s_active = !empty($cms[$s_k]['is_active']) && !$s_removed;
        ?>
          <a href="/admin/about-cms.php?tab=<?php echo urlencode($s_k); ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; border-radius: 6px; text-decoration: none; font-size: 0.82rem; transition: all 0.15s ease; <?php echo $is_current ? 'background: var(--color-navy); color: #FFFFFF; font-weight: 600;' : 'color: var(--color-text); background: transparent;'; ?>">
            <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 0;">
              <span style="font-size: 0.75rem; opacity: 0.8;"><?php echo $s_meta['num']; ?>.</span>
              <span style="font-size: 0.95rem;"><?php echo $s_meta['icon']; ?></span>
              <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo h($s_meta['name']); ?></span>
            </div>
            <?php if ($s_removed): ?>
              <span style="font-size: 0.65rem; border-radius: 8px; padding: 1px 6px; background: #FEE2E2; color: #DC2626; font-weight: 700;">REMOVED</span>
            <?php elseif ($s_active): ?>
              <span style="font-size: 0.65rem; border-radius: 8px; padding: 1px 6px; <?php echo $is_current ? 'background: #10B981; color:#fff;' : 'background: #DEF7EC; color: #03543F;'; ?>">ON</span>
            <?php else: ?>
              <span style="font-size: 0.65rem; border-radius: 8px; padding: 1px 6px; background: #F1F5F9; color: #94A3B8;">OFF</span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT MAIN: SECTION EDITOR -->
    <div class="card" style="padding: 2.25rem; border: 1.5px solid rgba(6, 43, 99, 0.12); background: #FFFFFF; border-radius: var(--radius-md);">
      
      <form method="POST" action="/admin/about-cms.php" enctype="multipart/form-data" id="aboutSectionForm">
        <input type="hidden" name="section_key" value="<?php echo h($tab); ?>">
        <input type="hidden" name="save_section" value="1">
        <input type="hidden" name="pending_action" id="pendingActionInput" value="save">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

        <!-- Section Header with Active Toggle & Remove Action -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.25rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
          <div>
            <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1px;">
              Section <?php echo $sections_nav[$tab]['num']; ?> of 8
            </span>
            <h2 style="font-size: 1.4rem; color: var(--color-navy); margin: 0.25rem 0 0 0; font-family: var(--font-secondary);">
              <?php echo $sections_nav[$tab]['icon'] . ' ' . h($sections_nav[$tab]['name']); ?>
            </h2>
          </div>

          <!-- Controls: Visibility Toggle + Remove/Restore Button -->
          <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.5rem 0.85rem; border-radius: var(--radius-sm);">
              <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer; margin: 0;">
                <input type="checkbox" name="is_active" value="1" <?php echo (!empty($current_sec['is_active']) && empty($current_sec['is_removed'])) ? 'checked' : ''; ?>>
                <span>Visible on Page</span>
              </label>
            </div>

            <?php if (!empty($current_sec['is_removed'])): ?>
              <button type="button" class="btn" style="background: #10B981; color: #FFFFFF; font-size: 0.82rem; padding: 0.5rem 0.95rem; font-weight: 600;" onclick="setSectionAction('restore')">
                ↩️ Restore Section
              </button>
            <?php else: ?>
              <button type="button" class="btn btn-outline" style="border-color: #EF4444; color: #EF4444; font-size: 0.82rem; padding: 0.5rem 0.95rem; font-weight: 600;" onclick="setSectionAction('remove')">
                🗑️ Remove Section
              </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pending Removal Alert -->
        <div id="pendingRemovalAlert" style="display: none; background: #FEF2F2; border: 1.5px solid #EF4444; border-radius: var(--radius-sm); padding: 1rem 1.25rem; color: #991B1B; font-size: 0.88rem; margin-bottom: 1.75rem;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <span>
              <strong>⚠️ PENDING REMOVAL:</strong> This section is marked for removal from the live About page. It is <strong>NOT yet removed</strong> until you click <strong>"Confirm Removal & Save"</strong> below.
            </span>
            <button type="button" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.35rem 0.85rem; background: #FFFFFF; color: #991B1B; border-color: #EF4444;" onclick="cancelSectionAction()">
              Cancel Removal
            </button>
          </div>
        </div>

        <?php if (!empty($current_sec['is_removed'])): ?>
          <div style="background: #FFFBEB; border-left: 4px solid #F59E0B; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #92400E; font-size: 0.88rem; margin-bottom: 1.75rem;">
            <strong>Section Status:</strong> This section is currently <strong>REMOVED</strong> from the website. To display it again, click <strong>"Restore Section"</strong> above and then save.
          </div>
        <?php endif; ?>

        <!-- SECTION FORM FIELDS -->

        <?php if ($tab === 'story'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Kicker / Eyebrow Subtitle</label>
            <input type="text" name="subtitle" value="<?php echo h($current_sec['subtitle'] ?? 'About Zuvio'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Main Story Headline *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? ''); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Story Content (Paragraphs)</label>
            <textarea name="content" rows="6" class="admin-input" style="line-height: 1.6;"><?php echo h($current_sec['content'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Feature Hero Image URL</label>
            <input type="text" name="image" value="<?php echo h($current_sec['image'] ?? ''); ?>" class="admin-input">
          </div>
          <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem; margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Institutional Vision & Mission</h4>
            <div class="admin-form-group">
              <label class="admin-label">Our Vision</label>
              <textarea name="vision" rows="3" class="admin-input"><?php echo h($current_sec['vision'] ?? ''); ?></textarea>
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Our Mission</label>
              <textarea name="mission" rows="3" class="admin-input"><?php echo h($current_sec['mission'] ?? ''); ?></textarea>
            </div>
          </div>

        <?php elseif ($tab === 'values'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Our Guiding Principles'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'What Matters Most at Zuvio'); ?>" required class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Active Values Cards (<?php echo count($current_sec['items'] ?? []); ?> Total)</h4>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              <?php foreach (($current_sec['items'] ?? []) as $v): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm); flex-wrap: wrap; gap: 0.5rem;">
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.4rem;"><?php echo h($v['icon'] ?? '💎'); ?></span>
                    <div>
                      <strong style="color: var(--color-navy);"><?php echo h($v['title']); ?></strong>
                      <p style="margin: 0; font-size: 0.8rem; color: var(--color-muted);"><?php echo h($v['desc']); ?></p>
                    </div>
                  </div>
                  <span style="font-size: 0.7rem; background: #DEF7EC; color: #03543F; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Active</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'apart'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'The Zuvio Difference'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'What Sets Us Apart'); ?>" required class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Differentiator Cards (<?php echo count($current_sec['items'] ?? []); ?> Total)</h4>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              <?php foreach (($current_sec['items'] ?? []) as $a): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm); flex-wrap: wrap; gap: 0.5rem;">
                  <div>
                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--color-teal); text-transform: uppercase;"><?php echo h($a['tag'] ?? 'Edge'); ?></span>
                    <h5 style="margin: 0.2rem 0; color: var(--color-navy);"><?php echo h($a['title']); ?></h5>
                    <p style="margin: 0; font-size: 0.8rem; color: var(--color-muted);"><?php echo h($a['desc']); ?></p>
                  </div>
                  <span style="font-size: 0.7rem; background: #DEF7EC; color: #03543F; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Active</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'approach'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Educational Framework'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'The ZUVIO Approach'); ?>" required class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Z-U-V-I-O Pillars</h4>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              <?php foreach (($current_sec['items'] ?? []) as $app): ?>
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm);">
                  <span style="font-size: 1.5rem; font-weight: 800; color: var(--color-gold);"><?php echo h($app['letter']); ?></span>
                  <div>
                    <strong style="color: var(--color-navy);"><?php echo h($app['title']); ?></strong>
                    <p style="margin: 0; font-size: 0.8rem; color: var(--color-muted);"><?php echo h($app['desc']); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'audiences'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Target Learners'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Who Should Choose Zuvio'); ?>" required class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Learner Profiles (<?php echo count($current_sec['items'] ?? []); ?> Total)</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
              <?php foreach (($current_sec['items'] ?? []) as $aud): ?>
                <div style="padding: 0.85rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm);">
                  <strong style="color: var(--color-navy); font-size: 0.9rem;"><?php echo h($aud['title']); ?></strong>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.78rem; color: var(--color-muted); line-height: 1.4;"><?php echo h($aud['desc']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'founder_msg'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? "Founder's Message"); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Message Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Learning Without Boundaries. Growing With Purpose.'); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Salutation</label>
            <input type="text" name="salutation" value="<?php echo h($current_sec['salutation'] ?? 'Dear Parents, Students and Members of the Zuvio Community,'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Letter Body Paragraphs</label>
            <textarea name="paragraphs" rows="8" class="admin-input" style="line-height: 1.6;"><?php echo h($current_sec['paragraphs'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Signoff Name</label>
              <input type="text" name="signoff_name" value="<?php echo h($current_sec['signoff_name'] ?? 'Founder'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Signoff Title / Org</label>
              <input type="text" name="signoff_org" value="<?php echo h($current_sec['signoff_org'] ?? 'Zuvio Global School'); ?>" class="admin-input">
            </div>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Founder Portrait URL</label>
            <input type="text" name="image" value="<?php echo h($current_sec['image'] ?? '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'); ?>" class="admin-input">
          </div>

        <?php elseif ($tab === 'awards'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Excellence Benchmarks'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Awards & Global Recognition'); ?>" required class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Recognitions & Awards (<?php echo count($current_sec['items'] ?? []); ?> Total)</h4>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              <?php foreach (($current_sec['items'] ?? []) as $aw): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm); flex-wrap: wrap; gap: 0.5rem;">
                  <div>
                    <h5 style="margin: 0; color: var(--color-navy);"><?php echo h($aw['title']); ?></h5>
                    <span style="font-size: 0.75rem; color: var(--color-teal); font-weight: 600;"><?php echo h($aw['org']); ?></span>
                    <p style="margin: 0.2rem 0 0 0; font-size: 0.8rem; color: var(--color-muted);"><?php echo h($aw['desc']); ?></p>
                  </div>
                  <span style="font-size: 0.7rem; background: #DEF7EC; color: #03543F; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Active</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'cta'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Take the Next Step'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Ready to Explore Zuvio for Your Child?'); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Text</label>
            <textarea name="subtitle" rows="3" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Primary Button Label</label>
              <input type="text" name="btn_primary_text" value="<?php echo h($current_sec['btn_primary_text'] ?? 'Book Free Counselling'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Primary Button URL</label>
              <input type="text" name="btn_primary_url" value="<?php echo h($current_sec['btn_primary_url'] ?? '/contact'); ?>" class="admin-input">
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Secondary Button Label</label>
              <input type="text" name="btn_secondary_text" value="<?php echo h($current_sec['btn_secondary_text'] ?? 'Explore Academics'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Secondary Button URL</label>
              <input type="text" name="btn_secondary_url" value="<?php echo h($current_sec['btn_secondary_url'] ?? '/academics'); ?>" class="admin-input">
            </div>
          </div>

        <?php endif; ?>

        <!-- INDIVIDUAL SECTION SAVE BUTTON -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid var(--color-border); padding-top: 1.5rem; margin-top: 2rem; flex-wrap: wrap; gap: 1rem;">
          <a href="/admin/about-cms.php?tab=<?php echo urlencode($tab); ?>" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1.25rem;">
            Reset / Reload
          </a>
          <button type="submit" id="sectionSubmitBtn" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 2.25rem; font-size: 0.95rem; font-weight: 600;">
            💾 Save <?php echo h($sections_nav[$tab]['name']); ?>
          </button>
        </div>

      </form>

      <script>
      function setSectionAction(action) {
        const input = document.getElementById('pendingActionInput');
        const alertBox = document.getElementById('pendingRemovalAlert');
        const submitBtn = document.getElementById('sectionSubmitBtn');
        if (action === 'remove') {
          if (confirm('Are you sure you want to mark this section for removal? (Note: Section will NOT be removed from the live website until you click "Confirm Removal & Save")')) {
            if (input) input.value = 'remove';
            if (alertBox) alertBox.style.display = 'block';
            if (submitBtn) {
              submitBtn.innerText = '⚠️ Confirm Removal & Save';
              submitBtn.style.background = '#EF4444';
              submitBtn.style.borderColor = '#EF4444';
            }
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        } else if (action === 'restore') {
          if (input) input.value = 'restore';
          if (alertBox) alertBox.style.display = 'none';
          if (submitBtn) {
            submitBtn.innerText = '↩️ Confirm Restore & Save';
            submitBtn.style.background = '#10B981';
            submitBtn.style.borderColor = '#10B981';
          }
          submitBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }

      function cancelSectionAction() {
        const input = document.getElementById('pendingActionInput');
        const alertBox = document.getElementById('pendingRemovalAlert');
        const submitBtn = document.getElementById('sectionSubmitBtn');
        if (input) input.value = 'save';
        if (alertBox) alertBox.style.display = 'none';
        if (submitBtn) {
          submitBtn.innerText = '💾 Save <?php echo h(addslashes($sections_nav[$tab]['name'])); ?>';
          submitBtn.style.background = 'var(--color-navy)';
          submitBtn.style.borderColor = 'var(--color-navy)';
        }
      }
      </script>

    </div>

  </div>

</div>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
