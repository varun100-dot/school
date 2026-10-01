<?php
// Zuvio Global School - Admin Academics CMS Manager (2-Column Page Section Editor)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'technology';

// Persistent CMS Storage (MySQL database with session fallback)
$db_saved = get_json_setting('cms_academics', []);
if (!isset($_SESSION['mock_academics_cms']) || !empty($db_saved)) {
    $_SESSION['mock_academics_cms'] = !empty($db_saved) ? $db_saved : [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

// 1. Defaults for Technology & LMS
if (!isset($ac_cms['technology'])) {
    $ac_cms['technology'] = [
        'is_active' => 1,
        'hero_title' => 'Technology Built for Real Learning',
        'hero_subtitle' => 'Our Digital Learning Ecosystem',
        'hero_desc' => 'At Zuvio Global School, technology is never a passive screen. It is an active workspace for curiosity, collaboration, and creative mastery powered by an enterprise-grade online learning environment.',
        'lms_video' => '/assets/images/03_Science_Experiment_Learning.mp4',
        'lms_features' => [
            ['id' => 1, 'title' => 'Child-Friendly and User-Focused', 'desc' => 'Intentionally designed for young learners with intuitive, accessible navigation.', 'icon' => '🧒', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Live, Interactive Classes', 'desc' => 'Real-time daily connections with teachers and peers using live video and whiteboards.', 'icon' => '💻', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Safety and Security First', 'desc' => 'End-to-end encrypted and moderated virtual classrooms ensuring a safe environment.', 'icon' => '🛡️', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Digital Library at Fingertips', 'desc' => 'Instant access to curriculum textbooks in PDF, Oxford graded readers, and guides.', 'icon' => '📚', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'All-in-One Convenience', 'desc' => 'From live schedules to gradebooks and progress reports — everything is one click away.', 'icon' => '⚡', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Diverse Learning Resources', 'desc' => 'Catering to visual, auditory, and kinesthetic learners with multimedia content.', 'icon' => '🎨', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

// 2. Defaults for Curriculum Framework
if (!isset($ac_cms['curriculum'])) {
    $ac_cms['curriculum'] = [
        'is_active' => 1,
        'hero_title' => 'A Future-Ready Learning Journey — Kindergarten to Grade 8th',
        'hero_subtitle' => 'Curriculum Framework',
        'hero_desc' => 'Mapped to CBSE learning outcomes, NEP 2020 pedagogical structure, and Oxford thematic inquiry — building strong academic foundations with creativity, communication, digital fluency, and real-world mastery.',
        'early_years_title' => 'Early Years · Nursery to UKG',
        'early_years_desc' => 'Learning through stories, play, music, sensory discovery, and phonics. Fostering curiosity, language foundations, and social-emotional confidence.',
        'foundation_title' => 'Foundation Stage · Grades 1–2',
        'foundation_desc' => 'Strengthening reading comprehension, foundational numeracy, environmental awareness, and creative expression through theme-based modules.',
        'preparatory_title' => 'Preparatory Stage · Grades 3–5',
        'preparatory_desc' => 'Interdisciplinary, application-led learning in science, computational thinking, financial awareness, and structured inquiry projects.',
        'middle_school_title' => 'Middle School · Grades 6–8',
        'middle_school_desc' => 'Analytical depth, research methodologies, AI fluency, public debate, entrepreneurship, and global citizenship.',
        'oxford_theme' => 'Zuvio incorporates Oxford thematic learning where subjects converge around meaningful monthly themes, teaching children to synthesize concepts rather than memorizing in silos.',
        'assessment_philosophy' => 'Continuous formative assessment, portfolio reviews, and observation-based milestones that measure growth, conceptual mastery, and critical application.'
    ];
}

// 3. Defaults for Special Education
if (!isset($ac_cms['special_ed'])) {
    $ac_cms['special_ed'] = [
        'is_active' => 1,
        'title' => 'Inclusive Learning & Special Education',
        'kicker' => 'Every Child Learns. Every Child Belongs.',
        'intro' => 'At Zuvio Global School, we believe education must adapt to the learner — never the child to the system. Our inclusive learning programme creates a supportive, flexible, and learner-centred environment where children with diverse needs can take part meaningfully, grow in confidence, and discover their unique strengths.',
        'pillars' => [
            ['id' => 1, 'title' => 'Personalised Learning', 'desc' => 'Flexible learning pace, individualised goals, and customized worksheets.', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Individual Attention', 'desc' => 'Small cohorts (1:15–1:20) and dedicated one-on-one check-ins.', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Flexible Learning Rhythm', 'desc' => 'Learn from the comfort of home, free from sensory overload or peer anxiety.', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Strength-Based Pedagogy', 'desc' => 'Focusing on what children love and do best, cultivating genuine self-esteem.', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'Social & Emotional Growth', 'desc' => 'Empathy-driven teacher relationships in an inclusive peer setting.', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Close Family Partnership', 'desc' => 'Regular collaborative reviews with parents to calibrate IEP milestones.', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

// 4. Defaults for Electives
if (!isset($ac_cms['electives'])) {
    $ac_cms['electives'] = [
        'is_active' => 1,
        'badge' => 'Beyond Core Academics',
        'title' => 'Electives, Languages & Future Skills',
        'regional_languages' => 'Hindi, Sanskrit, Urdu, Tamil, Telugu, Kannada, Marathi, Bengali',
        'foreign_languages' => 'French, Spanish, German, Arabic, Mandarin',
        'future_skills' => [
            ['id' => 1, 'name' => 'Coding & Robotics', 'desc' => 'Block programming, Python fundamentals, and logic by Discovery Education.'],
            ['id' => 2, 'name' => 'Abacus & Rubik\'s Cube', 'desc' => 'Mental arithmetic speed, spatial memory, and focus concentration.'],
            ['id' => 3, 'name' => 'Public Speaking & Debate', 'desc' => 'Articulating ideas with poise, persuasive rhetoric, and voice modulation.'],
            ['id' => 4, 'name' => 'Creative Writing & Media', 'desc' => 'Authoring short stories, journalistic reporting, and digital publishing.'],
            ['id' => 5, 'name' => 'Financial Literacy', 'desc' => 'Foundational concepts of money, saving, budgeting, and ethical commerce.'],
            ['id' => 6, 'name' => 'Yoga & Mindfulness', 'desc' => 'Breathing exercises, physical postures, and emotional regulation techniques.']
        ]
    ];
}

// 5. Defaults for NEP 2020
if (!isset($ac_cms['nep_2020'])) {
    $ac_cms['nep_2020'] = [
        'is_active' => 1,
        'title' => 'NEP 2020 & NCF Compliance',
        'subtitle' => 'National Education Policy 2020 Alignment',
        'desc' => 'In full alignment with the National Education Policy (NEP 2020) and National Curriculum Framework (NCF), Zuvio replaces rote memorization with experiential, discovery-based, and interdisciplinary learning.',
        'pdf_title' => 'National Education Policy 2020 — Ministry of Education, Govt. of India',
        'pdf_url' => '/assets/docs/NEP_2020_Policy_Document.pdf'
    ];
}

// 6. Defaults for Resources
if (!isset($ac_cms['resources'])) {
    $ac_cms['resources'] = [
        'is_active' => 1,
        'calendar_title' => 'Academic Calendar 2026–27',
        'calendar_desc' => 'Comprehensive term dates, assessment schedules, project submission deadlines, and school holidays.',
        'calendar_pdf' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
    ];
}

// 7. Defaults for CTA
if (!isset($ac_cms['cta'])) {
    $ac_cms['cta'] = [
        'is_active' => 1,
        'badge' => 'Begin Your Child’s Journey',
        'title' => 'Ready to Explore Zuvio Academics?',
        'subtitle' => 'Schedule a free 1-on-1 counseling interaction or explore our admission process.',
        'btn_primary_text' => 'Enrol Now',
        'btn_primary_url' => '/admissions#enrol',
        'btn_secondary_text' => 'Schedule Counselling',
        'btn_secondary_url' => '/contact'
    ];
}

// 7 Sections Ordered Exactly as on Academics Page
$sections_nav = [
    'technology' => ['num' => 1, 'name' => 'Technology & LMS Ecosystem', 'icon' => '💻'],
    'curriculum' => ['num' => 2, 'name' => 'Curriculum Framework (4 Stages)', 'icon' => '🎓'],
    'special_ed' => ['num' => 3, 'name' => 'Special Education & Neurodiversity', 'icon' => '🤝'],
    'electives' => ['num' => 4, 'name' => 'Electives, Languages & Skills', 'icon' => '🌍'],
    'nep_2020' => ['num' => 5, 'name' => 'NEP 2020 & Policy Framework', 'icon' => '📜'],
    'resources' => ['num' => 6, 'name' => 'Academic Resources & Calendar', 'icon' => '📚'],
    'cta' => ['num' => 7, 'name' => 'Conversion CTA Banner', 'icon' => '🚀'],
];

if (!isset($sections_nav[$tab])) {
    $tab = 'technology';
}
$current_sec = $ac_cms[$tab] ?? [];

// POST Action Handlers (Section Save, Remove, Restore)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_section'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $s_key = trim($_POST['section_key'] ?? $tab);
        $pending_action = trim($_POST['pending_action'] ?? 'save');
        
        if (isset($ac_cms[$s_key])) {
            if ($pending_action === 'remove') {
                $ac_cms[$s_key]['is_removed'] = 1;
                $ac_cms[$s_key]['is_active'] = 0;
            } elseif ($pending_action === 'restore') {
                $ac_cms[$s_key]['is_removed'] = 0;
                $ac_cms[$s_key]['is_active'] = 1;
            } else {
                $ac_cms[$s_key]['is_active'] = isset($_POST['is_active']) ? 1 : 0;
                $ac_cms[$s_key]['is_removed'] = 0;
            }

            // Save Specific Section Content
            if ($s_key === 'technology') {
                $ac_cms['technology']['hero_subtitle'] = trim($_POST['hero_subtitle'] ?? '');
                $ac_cms['technology']['hero_title'] = trim($_POST['hero_title'] ?? '');
                $ac_cms['technology']['hero_desc'] = trim($_POST['hero_desc'] ?? '');
                $ac_cms['technology']['lms_video'] = trim($_POST['lms_video'] ?? '');
            } elseif ($s_key === 'curriculum') {
                $ac_cms['curriculum']['hero_subtitle'] = trim($_POST['hero_subtitle'] ?? '');
                $ac_cms['curriculum']['hero_title'] = trim($_POST['hero_title'] ?? '');
                $ac_cms['curriculum']['hero_desc'] = trim($_POST['hero_desc'] ?? '');
                $ac_cms['curriculum']['early_years_title'] = trim($_POST['early_years_title'] ?? '');
                $ac_cms['curriculum']['early_years_desc'] = trim($_POST['early_years_desc'] ?? '');
                $ac_cms['curriculum']['foundation_title'] = trim($_POST['foundation_title'] ?? '');
                $ac_cms['curriculum']['foundation_desc'] = trim($_POST['foundation_desc'] ?? '');
                $ac_cms['curriculum']['preparatory_title'] = trim($_POST['preparatory_title'] ?? '');
                $ac_cms['curriculum']['preparatory_desc'] = trim($_POST['preparatory_desc'] ?? '');
                $ac_cms['curriculum']['middle_school_title'] = trim($_POST['middle_school_title'] ?? '');
                $ac_cms['curriculum']['middle_school_desc'] = trim($_POST['middle_school_desc'] ?? '');
                $ac_cms['curriculum']['oxford_theme'] = trim($_POST['oxford_theme'] ?? '');
                $ac_cms['curriculum']['assessment_philosophy'] = trim($_POST['assessment_philosophy'] ?? '');
            } elseif ($s_key === 'special_ed') {
                $ac_cms['special_ed']['kicker'] = trim($_POST['kicker'] ?? '');
                $ac_cms['special_ed']['title'] = trim($_POST['title'] ?? '');
                $ac_cms['special_ed']['intro'] = trim($_POST['intro'] ?? '');
            } elseif ($s_key === 'electives') {
                $ac_cms['electives']['badge'] = trim($_POST['badge'] ?? '');
                $ac_cms['electives']['title'] = trim($_POST['title'] ?? '');
                $ac_cms['electives']['regional_languages'] = trim($_POST['regional_languages'] ?? '');
                $ac_cms['electives']['foreign_languages'] = trim($_POST['foreign_languages'] ?? '');
            } elseif ($s_key === 'nep_2020') {
                $ac_cms['nep_2020']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $ac_cms['nep_2020']['title'] = trim($_POST['title'] ?? '');
                $ac_cms['nep_2020']['desc'] = trim($_POST['desc'] ?? '');
                $ac_cms['nep_2020']['pdf_title'] = trim($_POST['pdf_title'] ?? '');
                $ac_cms['nep_2020']['pdf_url'] = trim($_POST['pdf_url'] ?? '');
            } elseif ($s_key === 'resources') {
                $ac_cms['resources']['calendar_title'] = trim($_POST['calendar_title'] ?? '');
                $ac_cms['resources']['calendar_desc'] = trim($_POST['calendar_desc'] ?? '');
                $ac_cms['resources']['calendar_pdf'] = trim($_POST['calendar_pdf'] ?? '');
            } elseif ($s_key === 'cta') {
                $ac_cms['cta']['badge'] = trim($_POST['badge'] ?? '');
                $ac_cms['cta']['title'] = trim($_POST['title'] ?? '');
                $ac_cms['cta']['subtitle'] = trim($_POST['subtitle'] ?? '');
                $ac_cms['cta']['btn_primary_text'] = trim($_POST['btn_primary_text'] ?? '');
                $ac_cms['cta']['btn_primary_url'] = trim($_POST['btn_primary_url'] ?? '');
                $ac_cms['cta']['btn_secondary_text'] = trim($_POST['btn_secondary_text'] ?? '');
                $ac_cms['cta']['btn_secondary_url'] = trim($_POST['btn_secondary_url'] ?? '');
            }

            set_json_setting('cms_academics', $ac_cms, 'Academics CMS Content');
            $_SESSION['mock_academics_cms'] = $ac_cms;
            $redirect_msg = ($pending_action === 'remove') ? 'removed' : (($pending_action === 'restore') ? 'restored' : 'saved');
            header("Location: /admin/academics-cms.php?tab=" . urlencode($s_key) . "&msg=" . $redirect_msg);
            exit;
        }
    }
}

$page_slug = 'admin-academics-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="max-width: 1200px; margin: 0 auto;">

  <!-- Page Header -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
        Academics Page Sections CMS
      </h1>
      <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
        Manage all 7 sections of the Academics page in the exact visual sequence they appear. Enable, disable, remove, and save each section independently.
      </p>
    </div>

    <div style="display: flex; gap: 0.75rem;">
      <a href="/curriculum" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem; border-color: var(--color-navy); color: var(--color-navy);">
        📖 Curriculum View ↗
      </a>
      <a href="/academics" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        View Live Academics Page ↗
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
    
    <!-- LEFT SIDEBAR: 7 ORDERED SECTIONS -->
    <div class="card" style="padding: 1rem; border: 1.5px solid rgba(6, 43, 99, 0.12); background: #FFFFFF; border-radius: var(--radius-md); position: sticky; top: 1.5rem;">
      <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--color-gold); letter-spacing: 1px; padding: 0.5rem 0.75rem 0.75rem 0.75rem; border-bottom: 1px solid var(--color-border); margin-bottom: 0.5rem;">
        Academics Sequence (1–7)
      </div>

      <div style="display: flex; flex-direction: column; gap: 0.25rem; max-height: calc(100vh - 180px); overflow-y: auto;">
        <?php foreach ($sections_nav as $s_k => $s_meta): 
          $is_current = ($tab === $s_k);
          $s_removed = !empty($ac_cms[$s_k]['is_removed']);
          $s_active = !empty($ac_cms[$s_k]['is_active']) && !$s_removed;
        ?>
          <a href="/admin/academics-cms.php?tab=<?php echo urlencode($s_k); ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; border-radius: 6px; text-decoration: none; font-size: 0.82rem; transition: all 0.15s ease; <?php echo $is_current ? 'background: var(--color-navy); color: #FFFFFF; font-weight: 600;' : 'color: var(--color-text); background: transparent;'; ?>">
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
      
      <form method="POST" action="/admin/academics-cms.php" enctype="multipart/form-data" id="academicsSectionForm">
        <input type="hidden" name="section_key" value="<?php echo h($tab); ?>">
        <input type="hidden" name="save_section" value="1">
        <input type="hidden" name="pending_action" id="pendingActionInput" value="save">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

        <!-- Section Header with Active Toggle & Remove Action -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.25rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
          <div>
            <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1px;">
              Section <?php echo $sections_nav[$tab]['num']; ?> of 7
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
              <strong>⚠️ PENDING REMOVAL:</strong> This section is marked for removal from the live Academics page. It is <strong>NOT yet removed</strong> until you click <strong>"Confirm Removal & Save"</strong> below.
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

        <?php if ($tab === 'technology'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Kicker / Eyebrow Subtitle</label>
            <input type="text" name="hero_subtitle" value="<?php echo h($current_sec['hero_subtitle'] ?? 'Our Digital Learning Ecosystem'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="hero_title" value="<?php echo h($current_sec['hero_title'] ?? ''); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Description</label>
            <textarea name="hero_desc" rows="4" class="admin-input" style="line-height: 1.6;"><?php echo h($current_sec['hero_desc'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">LMS Showcase Video / Media URL</label>
            <input type="text" name="lms_video" value="<?php echo h($current_sec['lms_video'] ?? ''); ?>" class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">LMS Architecture Features (<?php echo count($current_sec['lms_features'] ?? []); ?> Total)</h4>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              <?php foreach (($current_sec['lms_features'] ?? []) as $feat): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.85rem 1.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm); flex-wrap: wrap; gap: 0.5rem;">
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.4rem;"><?php echo h($feat['icon'] ?? '⚡'); ?></span>
                    <div>
                      <strong style="color: var(--color-navy);"><?php echo h($feat['title']); ?></strong>
                      <p style="margin: 0; font-size: 0.8rem; color: var(--color-muted);"><?php echo h($feat['desc']); ?></p>
                    </div>
                  </div>
                  <span style="font-size: 0.7rem; background: #DEF7EC; color: #03543F; padding: 2px 8px; border-radius: 10px; font-weight: 600;">Active</span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'curriculum'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Eyebrow Subtitle</label>
            <input type="text" name="hero_subtitle" value="<?php echo h($current_sec['hero_subtitle'] ?? 'Curriculum Framework'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Curriculum Heading *</label>
            <input type="text" name="hero_title" value="<?php echo h($current_sec['hero_title'] ?? ''); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Curriculum Description</label>
            <textarea name="hero_desc" rows="3" class="admin-input"><?php echo h($current_sec['hero_desc'] ?? ''); ?></textarea>
          </div>
          <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem; margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Four Pedagogical Stages</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div class="admin-form-group">
                <label class="admin-label">Stage 1: Early Years Title</label>
                <input type="text" name="early_years_title" value="<?php echo h($current_sec['early_years_title'] ?? ''); ?>" class="admin-input">
                <label class="admin-label" style="margin-top: 0.5rem;">Stage 1 Description</label>
                <textarea name="early_years_desc" rows="2" class="admin-input"><?php echo h($current_sec['early_years_desc'] ?? ''); ?></textarea>
              </div>
              <div class="admin-form-group">
                <label class="admin-label">Stage 2: Foundation Stage Title</label>
                <input type="text" name="foundation_title" value="<?php echo h($current_sec['foundation_title'] ?? ''); ?>" class="admin-input">
                <label class="admin-label" style="margin-top: 0.5rem;">Stage 2 Description</label>
                <textarea name="foundation_desc" rows="2" class="admin-input"><?php echo h($current_sec['foundation_desc'] ?? ''); ?></textarea>
              </div>
              <div class="admin-form-group">
                <label class="admin-label">Stage 3: Preparatory Stage Title</label>
                <input type="text" name="preparatory_title" value="<?php echo h($current_sec['preparatory_title'] ?? ''); ?>" class="admin-input">
                <label class="admin-label" style="margin-top: 0.5rem;">Stage 3 Description</label>
                <textarea name="preparatory_desc" rows="2" class="admin-input"><?php echo h($current_sec['preparatory_desc'] ?? ''); ?></textarea>
              </div>
              <div class="admin-form-group">
                <label class="admin-label">Stage 4: Middle School Title</label>
                <input type="text" name="middle_school_title" value="<?php echo h($current_sec['middle_school_title'] ?? ''); ?>" class="admin-input">
                <label class="admin-label" style="margin-top: 0.5rem;">Stage 4 Description</label>
                <textarea name="middle_school_desc" rows="2" class="admin-input"><?php echo h($current_sec['middle_school_desc'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>
          <div class="admin-form-group" style="margin-top: 1rem;">
            <label class="admin-label">Oxford Thematic Learning Philosophy</label>
            <textarea name="oxford_theme" rows="3" class="admin-input"><?php echo h($current_sec['oxford_theme'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Assessment & Evaluation Philosophy</label>
            <textarea name="assessment_philosophy" rows="3" class="admin-input"><?php echo h($current_sec['assessment_philosophy'] ?? ''); ?></textarea>
          </div>

        <?php elseif ($tab === 'special_ed'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Eyebrow Kicker</label>
            <input type="text" name="kicker" value="<?php echo h($current_sec['kicker'] ?? 'Every Child Learns. Every Child Belongs.'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Inclusive Learning & Special Education'); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Inclusive Learning Introduction</label>
            <textarea name="intro" rows="4" class="admin-input"><?php echo h($current_sec['intro'] ?? ''); ?></textarea>
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Inclusivity Pillars</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
              <?php foreach (($current_sec['pillars'] ?? []) as $pil): ?>
                <div style="padding: 0.85rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm);">
                  <strong style="color: var(--color-navy); font-size: 0.9rem;"><?php echo h($pil['title']); ?></strong>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.78rem; color: var(--color-muted); line-height: 1.4;"><?php echo h($pil['desc']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'electives'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Beyond Core Academics'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Electives, Languages & Future Skills'); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Regional Indian Languages (Comma separated)</label>
            <input type="text" name="regional_languages" value="<?php echo h($current_sec['regional_languages'] ?? ''); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Foreign Languages Offered (Comma separated)</label>
            <input type="text" name="foreign_languages" value="<?php echo h($current_sec['foreign_languages'] ?? ''); ?>" class="admin-input">
          </div>
          <div style="margin-top: 1.5rem;">
            <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Future Skills Modules</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
              <?php foreach (($current_sec['future_skills'] ?? []) as $sk): ?>
                <div style="padding: 0.85rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: var(--radius-sm);">
                  <strong style="color: var(--color-navy); font-size: 0.9rem;"><?php echo h($sk['name']); ?></strong>
                  <p style="margin: 0.25rem 0 0 0; font-size: 0.78rem; color: var(--color-muted);"><?php echo h($sk['desc']); ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

        <?php elseif ($tab === 'nep_2020'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Kicker / Eyebrow Subtitle</label>
            <input type="text" name="subtitle" value="<?php echo h($current_sec['subtitle'] ?? 'National Education Policy 2020 Alignment'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'NEP 2020 & NCF Compliance'); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Policy Narrative Description</label>
            <textarea name="desc" rows="5" class="admin-input" style="line-height: 1.6;"><?php echo h($current_sec['desc'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Official Document Title</label>
              <input type="text" name="pdf_title" value="<?php echo h($current_sec['pdf_title'] ?? ''); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Document Download URL</label>
              <input type="text" name="pdf_url" value="<?php echo h($current_sec['pdf_url'] ?? ''); ?>" class="admin-input">
            </div>
          </div>

        <?php elseif ($tab === 'resources'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Academic Calendar Title</label>
            <input type="text" name="calendar_title" value="<?php echo h($current_sec['calendar_title'] ?? 'Academic Calendar 2026–27'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Calendar Description</label>
            <textarea name="calendar_desc" rows="3" class="admin-input"><?php echo h($current_sec['calendar_desc'] ?? ''); ?></textarea>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Calendar PDF URL</label>
            <input type="text" name="calendar_pdf" value="<?php echo h($current_sec['calendar_pdf'] ?? ''); ?>" class="admin-input">
          </div>

        <?php elseif ($tab === 'cta'): ?>
          <div class="admin-form-group">
            <label class="admin-label">Section Eyebrow Badge</label>
            <input type="text" name="badge" value="<?php echo h($current_sec['badge'] ?? 'Begin Your Child’s Journey'); ?>" class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Section Heading *</label>
            <input type="text" name="title" value="<?php echo h($current_sec['title'] ?? 'Ready to Explore Zuvio Academics?'); ?>" required class="admin-input">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Subtitle Text</label>
            <textarea name="subtitle" rows="3" class="admin-input"><?php echo h($current_sec['subtitle'] ?? ''); ?></textarea>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Primary Button Label</label>
              <input type="text" name="btn_primary_text" value="<?php echo h($current_sec['btn_primary_text'] ?? 'Enrol Now'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Primary Button URL</label>
              <input type="text" name="btn_primary_url" value="<?php echo h($current_sec['btn_primary_url'] ?? '/admissions#enrol'); ?>" class="admin-input">
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Secondary Button Label</label>
              <input type="text" name="btn_secondary_text" value="<?php echo h($current_sec['btn_secondary_text'] ?? 'Schedule Counselling'); ?>" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Secondary Button URL</label>
              <input type="text" name="btn_secondary_url" value="<?php echo h($current_sec['btn_secondary_url'] ?? '/contact'); ?>" class="admin-input">
            </div>
          </div>

        <?php endif; ?>

        <!-- INDIVIDUAL SECTION SAVE BUTTON -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid var(--color-border); padding-top: 1.5rem; margin-top: 2rem; flex-wrap: wrap; gap: 1rem;">
          <a href="/admin/academics-cms.php?tab=<?php echo urlencode($tab); ?>" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1.25rem;">
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
