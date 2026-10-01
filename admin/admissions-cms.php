<?php
// Zuvio Global School - Admin Admissions CMS Manager (Unified 2-Column Section Manager)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$selected_sec = $_GET['sec'] ?? 'overview';

// Persistent CMS Storage (MySQL database with session fallback)
$db_saved = get_json_setting('cms_admissions', []);
if (!isset($_SESSION['mock_admissions_cms']) || !empty($db_saved)) {
    $_SESSION['mock_admissions_cms'] = !empty($db_saved) ? $db_saved : [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

// 1. Defaults for Overview
if (!isset($adm_cms['overview'])) {
    $adm_cms['overview'] = [
        'is_active' => 1,
        'hero_badge' => 'Academic Year 2026–2027',
        'hero_title' => 'Admissions & Enrolment',
        'hero_subtitle' => 'A seamless, supportive onboarding journey designed to understand your child’s learning style, baseline competencies, and personal interests.',
        'btn1_text' => 'Enrol Now',
        'btn1_url' => '/admissions/enrol-now',
        'btn2_text' => 'Check Eligibility',
        'btn2_url' => '/admissions/eligibility'
    ];
}

// 2. Defaults for Enrol Now
if (!isset($adm_cms['enrol'])) {
    $adm_cms['enrol'] = [
        'is_active' => 1,
        'title' => 'Simple 5-Step Admissions Journey',
        'subtitle' => 'From initial enquiry to your child’s very first live classroom session.',
        'steps' => [
            ['id' => 1, 'step' => 1, 'title' => 'Connect with Admission Counselor', 'desc' => 'Speak with an expert admission counselor to understand curriculum mapping, live timings, and technological setup.', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'step' => 2, 'title' => 'Fill Out Admission Form', 'desc' => 'Complete the online application with student details, previous academic background, and preferred curriculum track.', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'step' => 3, 'title' => 'Pay the Fees', 'desc' => 'Secure your seat through transparent quarterly tuition payment via encrypted online payment gateway or bank transfer.', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'step' => 4, 'title' => 'Receive Login Credentials', 'desc' => 'Get dedicated student LMS access, parent portal onboarding credentials, digital timetables, and orientation pack.', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'step' => 5, 'title' => 'Attend Orientation Session', 'desc' => 'Meet class mentors, test interactive tools, meet global peers, and begin live interactive schooling with confidence.', 'sort_order' => 5, 'is_published' => 1]
        ],
        'required_docs' => [
            'Valid birth certificate of the child',
            'Valid photo identity card of parent/guardian and child (Aadhaar card or passport)',
            'Recent passport-sized color photographs of parent/guardian and child',
            'Previous year school progress report / marks card (for admission in Grade 1 and above)',
            'Transfer Certificate (if applicable from previous recognized school)'
        ]
    ];
}

// 3. Defaults for Eligibility
if (!isset($adm_cms['eligibility'])) {
    $adm_cms['eligibility'] = [
        'is_active' => 1,
        'title' => 'Eligibility & Age Criteria',
        'subtitle' => 'Age norms in alignment with NEP 2020 guidelines as of 31st March 2026.',
        'stages' => [
            ['id' => 1, 'stage' => 'Early Years', 'grades' => 'Nursery – KG', 'age' => '3 – 5+ Years', 'duration' => 'Approx. 2 Hours', 'focus' => 'Play-based, phonics, fine motor skills, sensory discovery', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'stage' => 'Foundation Stage', 'grades' => 'Grades 1 – 2', 'age' => '6 – 7+ Years', 'duration' => 'Approx. 2.5 Hours', 'focus' => 'Foundational literacy, numeracy, discovery-based inquiry', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'stage' => 'Preparatory Stage', 'grades' => 'Grades 3 – 5', 'age' => '8 – 10+ Years', 'duration' => 'Approx. 2.5 Hours', 'focus' => 'Conceptual mathematics, science inquiry, reading fluencies', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'stage' => 'Middle School', 'grades' => 'Grades 6 – 8', 'age' => '11 – 13+ Years', 'duration' => 'Approx. 3 Hours', 'focus' => 'Subject-specialist educators, coding, experimental science, debate', 'sort_order' => 4, 'is_published' => 1]
        ],
        'mid_session_note' => 'Students joining mid-session undergo an initial diagnostic assessment. Teachers identify curricular gaps and provide an individual bridge learning plan to support an effortless transition into the ongoing curriculum.'
    ];
}

// 4. Defaults for Fees
if (!isset($adm_cms['fees'])) {
    $adm_cms['fees'] = [
        'is_active' => 1,
        'title' => 'Fee Structure 2026–2027',
        'subtitle' => 'Transparent, predictable tuition without hidden infrastructural overheads or unexpected surcharges.',
        'tiers' => [
            ['id' => 1, 'grade' => 'Pre Primary', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '15,000', 'total_annual' => '66,550', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'grade' => 'Kindergarten', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '16,000', 'total_annual' => '70,950', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'grade' => 'Grade 1–2', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '19,000', 'total_annual' => '85,250', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'grade' => 'Grade 3–5', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '21,000', 'total_annual' => '95,150', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'grade' => 'Grade 6–8', 'reg_fee' => '500', 'adm_fee' => '2,500', 'tuition_q' => '21,000', 'total_annual' => '95,150', 'sort_order' => 5, 'is_published' => 1]
        ],
        'payment_notes' => 'Fees to be paid on a quarterly basis between 1st to 10th of the quarter. First Quarter has to be paid at the time of admission. Q1: April–June | Q2: July–September | Q3: October–December | Q4: January–March.',
        'complimentary_note' => 'BOOKS / LMS / ERP — COMPLIMENTARY | High-quality physical books, advanced LMS access, and ERP support are included in the annual fees.',
        'pdf_url' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
    ];
}

// 5. Defaults for Calendar
if (!isset($adm_cms['calendar'])) {
    $adm_cms['calendar'] = [
        'is_active' => 1,
        'title' => 'Academic Calendar 2026–2027',
        'subtitle' => 'Structured two-term academic year aligned with standard Indian and international schooling schedules.',
        'terms' => [
            [
                'id' => 1,
                'term' => 'Term 1: April to September',
                'desc' => 'Curriculum launch, foundational concepts, Oxford themes, mid-term formative reviews, and summer enrichment modules.',
                'milestones' => [
                    ['month' => 'April 2026', 'event' => 'New Academic Session Launch, Student & Parent Tech Orientation'],
                    ['month' => 'May – June 2026', 'event' => 'Core Subject Inquiries, Summer Enrichment & Coding Bootcamps'],
                    ['month' => 'July – August 2026', 'event' => 'Formative Assessment Checkpoint 1, ISSO Virtual Sports Challenges'],
                    ['month' => 'September 2026', 'event' => 'Mid-Term Comprehensive Review & Student Progress Portfolios']
                ]
            ],
            [
                'id' => 2,
                'term' => 'Term 2: October to March',
                'desc' => 'Project exhibitions, advanced coding/AI explorations, co-curricular showcases, and end-of-year comprehensive portfolios.',
                'milestones' => [
                    ['month' => 'October 2026', 'event' => 'Term 2 Kickoff, Cultural Assemblies & Creative Arts Festival'],
                    ['month' => 'November 2026', 'event' => 'Global Olympiads (Science/Math), NEP Experiential Project Exhibitions'],
                    ['month' => 'December 2026', 'event' => 'Formative Assessment Checkpoint 2 & Winter Break'],
                    ['month' => 'January 2027', 'event' => 'Classes Resume, Future Skills Showcase & Parent-Teacher Dialogue'],
                    ['month' => 'February – March 2027', 'event' => 'Summative Annual Portfolios, Graduation & Next-Grade Progression']
                ]
            ]
        ],
        'pdf_title' => 'Official Academic Calendar 2026–27',
        'pdf_url' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
    ];
}

// 6. Defaults for Counselor & FAQ CTA
if (!isset($adm_cms['counselor_cta'])) {
    $adm_cms['counselor_cta'] = [
        'is_active' => 1,
        'faq_kicker' => 'Got Admissions Questions?',
        'faq_title' => 'Parent FAQ & Transitions',
        'faq_desc' => 'Wondering about transitioning back to an offline school, board registration pathways, or class timings? Read our comprehensive 18-question parent FAQ guide.',
        'faq_btn_text' => 'Read Complete 18-Question FAQ',
        'faq_btn_url' => '/faq',
        'title' => 'Speak with an Admissions Counselor',
        'desc' => 'Have specific questions regarding grade placement, special education, or class schedules? Our admissions team is ready to guide you.',
        'btn_primary_text' => 'Request a Callback',
        'btn_secondary_text' => 'Online Application',
        'btn_secondary_url' => '/admissions/enrol-now'
    ];
}

// 6 Admissions Sections
$sections_nav = [
    'overview'      => ['num' => 1, 'name' => 'Admissions Hero & Overview', 'icon' => '🏛️'],
    'enrol'         => ['num' => 2, 'name' => 'Enrolment Journey & Docs', 'icon' => '📝'],
    'eligibility'   => ['num' => 3, 'name' => 'Eligibility & Age Matrix', 'icon' => '🎯'],
    'fees'          => ['num' => 4, 'name' => 'Tuition Fees & Inclusions', 'icon' => '💳'],
    'calendar'      => ['num' => 5, 'name' => 'Academic Calendar & Terms', 'icon' => '🗓️'],
    'counselor_cta' => ['num' => 6, 'name' => 'Admissions FAQ & Counselor CTA', 'icon' => '🤝']
];

if (!array_key_exists($selected_sec, $sections_nav)) {
    $selected_sec = 'overview';
}

function redirect_and_save_admissions($sec, $msg) {
    global $adm_cms;
    set_json_setting('cms_admissions', $adm_cms, 'Admissions CMS Content');
    header("Location: /admin/admissions-cms.php?sec=" . urlencode($sec) . "&msg=" . urlencode($msg));
    exit;
}

// Helper: Handle file uploads for PDFs / media
function handle_admissions_upload($file_key, $allowed = ['pdf', 'jpg', 'png', 'webp']) {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$file_key];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        throw new Exception("Invalid file extension: $ext. Allowed: " . implode(', ', $allowed));
    }
    $upload_dir = dirname(__FILE__) . '/../uploads/admissions/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $filename = 'adm_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file['name']);
    $dest = $upload_dir . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return '/uploads/admissions/' . $filename;
    }
    return null;
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        $target_sec = $_POST['section_key'] ?? $selected_sec;

        // Apply Section Removal / Restoration / Visibility
        if (!isset($adm_cms[$target_sec])) {
            $adm_cms[$target_sec] = [];
        }

        $sec_action = $_POST['sec_action'] ?? '';
        if ($sec_action === 'remove') {
            $adm_cms[$target_sec]['is_removed'] = 1;
            $adm_cms[$target_sec]['is_active'] = 0;
        } elseif ($sec_action === 'restore') {
            $adm_cms[$target_sec]['is_removed'] = 0;
            $adm_cms[$target_sec]['is_active'] = 1;
        } else {
            $adm_cms[$target_sec]['is_active'] = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;
            if ($adm_cms[$target_sec]['is_active'] == 1) {
                $adm_cms[$target_sec]['is_removed'] = 0;
            }
        }

        // Section 1: Overview
        if ($target_sec === 'overview') {
            $adm_cms['overview']['hero_badge'] = trim($_POST['hero_badge'] ?? '');
            $adm_cms['overview']['hero_title'] = trim($_POST['hero_title'] ?? '');
            $adm_cms['overview']['hero_subtitle'] = trim($_POST['hero_subtitle'] ?? '');
            $adm_cms['overview']['btn1_text'] = trim($_POST['btn1_text'] ?? 'Enrol Now');
            $adm_cms['overview']['btn1_url'] = trim($_POST['btn1_url'] ?? '/admissions/enrol-now');
            $adm_cms['overview']['btn2_text'] = trim($_POST['btn2_text'] ?? 'Check Eligibility');
            $adm_cms['overview']['btn2_url'] = trim($_POST['btn2_url'] ?? '/admissions/eligibility');
            redirect_and_save_admissions('overview', 'saved');
        }

        // Section 2: Enrol
        if ($target_sec === 'enrol') {
            $adm_cms['enrol']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['enrol']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if (isset($_POST['steps']) && is_array($_POST['steps'])) {
                $clean_steps = [];
                foreach ($_POST['steps'] as $idx => $st) {
                    $clean_steps[] = [
                        'id' => (int)($st['id'] ?? ($idx + 1)),
                        'step' => $idx + 1,
                        'title' => trim($st['title'] ?? ''),
                        'desc' => trim($st['desc'] ?? ''),
                        'sort_order' => $idx + 1,
                        'is_published' => 1
                    ];
                }
                $adm_cms['enrol']['steps'] = $clean_steps;
            }

            $raw_docs = trim($_POST['required_docs'] ?? '');
            if (!empty($raw_docs)) {
                $adm_cms['enrol']['required_docs'] = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $raw_docs)))));
            }
            redirect_and_save_admissions('enrol', 'saved');
        }

        // Section 3: Eligibility
        if ($target_sec === 'eligibility') {
            $adm_cms['eligibility']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['eligibility']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $adm_cms['eligibility']['mid_session_note'] = trim($_POST['mid_session_note'] ?? '');

            if (isset($_POST['stages']) && is_array($_POST['stages'])) {
                $clean_stages = [];
                foreach ($_POST['stages'] as $idx => $stg) {
                    $clean_stages[] = [
                        'id' => (int)($stg['id'] ?? ($idx + 1)),
                        'stage' => trim($stg['stage'] ?? ''),
                        'grades' => trim($stg['grades'] ?? ''),
                        'age' => trim($stg['age'] ?? ''),
                        'duration' => trim($stg['duration'] ?? ''),
                        'focus' => trim($stg['focus'] ?? ''),
                        'sort_order' => $idx + 1,
                        'is_published' => 1
                    ];
                }
                $adm_cms['eligibility']['stages'] = $clean_stages;
            }
            redirect_and_save_admissions('eligibility', 'saved');
        }

        // Section 4: Fees
        if ($target_sec === 'fees') {
            $adm_cms['fees']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['fees']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $adm_cms['fees']['payment_notes'] = trim($_POST['payment_notes'] ?? '');
            $adm_cms['fees']['complimentary_note'] = trim($_POST['complimentary_note'] ?? '');

            if (isset($_POST['tiers']) && is_array($_POST['tiers'])) {
                $clean_tiers = [];
                foreach ($_POST['tiers'] as $idx => $tr) {
                    $clean_tiers[] = [
                        'id' => (int)($tr['id'] ?? ($idx + 1)),
                        'grade' => trim($tr['grade'] ?? ''),
                        'reg_fee' => trim($tr['reg_fee'] ?? '500'),
                        'adm_fee' => trim($tr['adm_fee'] ?? '2,500'),
                        'tuition_q' => trim($tr['tuition_q'] ?? ''),
                        'total_annual' => trim($tr['total_annual'] ?? ''),
                        'sort_order' => $idx + 1,
                        'is_published' => 1
                    ];
                }
                $adm_cms['fees']['tiers'] = $clean_tiers;
            }

            try {
                $uploaded_pdf = handle_admissions_upload('fee_pdf', ['pdf']);
                if ($uploaded_pdf) {
                    $adm_cms['fees']['pdf_url'] = $uploaded_pdf;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                redirect_and_save_admissions('fees', 'saved');
            }
        }

        // Section 5: Calendar
        if ($target_sec === 'calendar') {
            $adm_cms['calendar']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['calendar']['subtitle'] = trim($_POST['subtitle'] ?? '');

            try {
                $uploaded_pdf = handle_admissions_upload('calendar_pdf', ['pdf']);
                if ($uploaded_pdf) {
                    $adm_cms['calendar']['pdf_url'] = $uploaded_pdf;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                redirect_and_save_admissions('calendar', 'saved');
            }
        }

        // Section 6: Counselor CTA
        if ($target_sec === 'counselor_cta') {
            $adm_cms['counselor_cta']['faq_kicker'] = trim($_POST['faq_kicker'] ?? '');
            $adm_cms['counselor_cta']['faq_title'] = trim($_POST['faq_title'] ?? '');
            $adm_cms['counselor_cta']['faq_desc'] = trim($_POST['faq_desc'] ?? '');
            $adm_cms['counselor_cta']['faq_btn_text'] = trim($_POST['faq_btn_text'] ?? '');
            $adm_cms['counselor_cta']['faq_btn_url'] = trim($_POST['faq_btn_url'] ?? '');
            $adm_cms['counselor_cta']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['counselor_cta']['desc'] = trim($_POST['desc'] ?? '');
            $adm_cms['counselor_cta']['btn_primary_text'] = trim($_POST['btn_primary_text'] ?? '');
            $adm_cms['counselor_cta']['btn_secondary_text'] = trim($_POST['btn_secondary_text'] ?? '');
            $adm_cms['counselor_cta']['btn_secondary_url'] = trim($_POST['btn_secondary_url'] ?? '');
            redirect_and_save_admissions('counselor_cta', 'saved');
        }
    }
}

$page_slug = 'admin-admissions-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Admissions Page Section Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Unified 2-column management for all Admissions page sections. Enable/disable, remove, or customize content.
    </p>
  </div>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <a href="/admissions" target="_blank" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem;">
      <span>👁️</span> Preview Live Page &nearr;
    </a>
    <a href="/admin/admissions-cms.php?sec=<?php echo urlencode($selected_sec); ?>" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; background: #FFFFFF;">
      ↻ Reload
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.9rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <div><strong>✓ Saved & Synchronized!</strong> Section updates are live on the website.</div>
    <span style="font-size: 0.8rem; color: var(--color-muted);"><?php echo date('H:i:s'); ?></span>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.85rem 1.25rem; margin-bottom:1.5rem; color:#9b1c1c; font-size:0.9rem;">
    <strong>Error:</strong> <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- 2-Column Section Manager Layout -->
<div style="display: grid; grid-template-columns: 310px 1fr; gap: 1.75rem; align-items: start;">

  <!-- Left Sidebar: Sticky Section Sequence -->
  <div style="position: sticky; top: 1.5rem;">
    <div class="card" style="padding: 0; overflow: hidden; border: 1.5px solid rgba(6, 43, 99, 0.12); box-shadow: var(--shadow-sm);">
      <div style="background: var(--color-navy); color: #FFFFFF; padding: 1rem 1.25rem; font-weight: 700; font-size: 0.9rem; display: flex; justify-content: space-between; align-items: center;">
        <span>Admissions Sections</span>
        <span style="font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 0.2rem 0.55rem; border-radius: 12px; font-weight: 600;">
          <?php echo count($sections_nav); ?> Total
        </span>
      </div>

      <div style="divide-y: 1px solid var(--color-border); max-height: calc(100vh - 180px); overflow-y: auto;">
        <?php foreach ($sections_nav as $skey => $sdata): 
          $s_active = !isset($adm_cms[$skey]['is_active']) || !empty($adm_cms[$skey]['is_active']);
          $s_removed = !empty($adm_cms[$skey]['is_removed']);
          $is_current = ($selected_sec === $skey);
        ?>
          <a href="/admin/admissions-cms.php?sec=<?php echo urlencode($skey); ?>" 
             style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.1rem; text-decoration: none; border-bottom: 1px solid var(--color-border); background: <?php echo $is_current ? 'var(--pastel-blue)' : '#FFFFFF'; ?>; border-left: 4px solid <?php echo $is_current ? 'var(--color-navy)' : 'transparent'; ?>; transition: all 0.15s ease;">
            <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
              <span style="font-size: 0.75rem; font-weight: 800; color: var(--color-muted); width: 18px;">
                <?php echo str_pad($sdata['num'], 2, '0', STR_PAD_LEFT); ?>
              </span>
              <span style="font-size: 1.1rem;"><?php echo $sdata['icon']; ?></span>
              <span style="font-size: 0.85rem; font-weight: <?php echo $is_current ? '700' : '500'; ?>; color: var(--color-navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <?php echo h($sdata['name']); ?>
              </span>
            </div>
            <div>
              <?php if ($s_removed): ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  REMOVED
                </span>
              <?php elseif (!$s_active): ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #6b7280; background: #f3f4f6; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  OFF
                </span>
              <?php else: ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #047857; background: #d1fae5; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  ON
                </span>
              <?php endif; ?>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Right Main Section Editor -->
  <div>
    <?php 
      $current_sec_data = $adm_cms[$selected_sec] ?? [];
      $is_sec_removed = !empty($current_sec_data['is_removed']);
      $is_sec_active = !isset($current_sec_data['is_active']) || !empty($current_sec_data['is_active']);
      $sec_meta = $sections_nav[$selected_sec];
    ?>

    <form method="POST" enctype="multipart/form-data" id="sectionForm">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="section_key" value="<?php echo h($selected_sec); ?>">
      <input type="hidden" name="sec_action" id="sec_action_input" value="">

      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.15); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">

        <!-- Section Editor Header with Live Controls -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 1.25rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-border); flex-wrap: wrap; gap: 1rem;">
          <div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 0.25rem;">
              Section <?php echo $sec_meta['num']; ?> of <?php echo count($sections_nav); ?>
            </div>
            <h2 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
              <span><?php echo $sec_meta['icon']; ?></span>
              <span><?php echo h($sec_meta['name']); ?></span>
            </h2>
          </div>

          <div style="display: flex; align-items: center; gap: 1rem;">
            <!-- Visible on Page Toggle -->
            <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); background: var(--color-surface-warm); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <input type="checkbox" name="is_active" value="1" <?php echo ($is_sec_active && !$is_sec_removed) ? 'checked' : ''; ?> <?php echo $is_sec_removed ? 'disabled' : ''; ?> style="width: 16px; height: 16px; cursor: pointer;">
              <span>Visible on Page</span>
            </label>

            <!-- Remove / Restore Button -->
            <?php if ($is_sec_removed): ?>
              <button type="button" onclick="setSectionAction('restore')" class="btn btn-outline" style="border-color: #047857; color: #047857; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>↩️</span> Restore Section
              </button>
            <?php else: ?>
              <button type="button" onclick="setSectionAction('remove')" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>🗑️</span> Remove Section
              </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pending Removal Alert Box -->
        <div id="pendingRemovalBox" style="display: none; background: #fee2e2; border-left: 4px solid #dc2626; padding: 1rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; color: #991b1b; font-size: 0.9rem;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              <strong>⚠️ PENDING REMOVAL:</strong> This section will be removed from the Admissions page when you click "Save Changes" below.
            </div>
            <button type="button" onclick="cancelSectionAction()" style="background: transparent; border: 1px solid #dc2626; color: #dc2626; padding: 0.25rem 0.65rem; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.8rem;">
              Cancel Removal
            </button>
          </div>
        </div>

        <!-- Section 1: Overview -->
        <?php if ($selected_sec === 'overview'): 
          $ov = $adm_cms['overview'] ?? [];
        ?>
          <div style="display: grid; gap: 1.25rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Hero Eyebrow / Badge
              </label>
              <input type="text" name="hero_badge" value="<?php echo h($ov['hero_badge'] ?? 'Academic Year 2026–2027'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Hero Main Heading
              </label>
              <input type="text" name="hero_title" value="<?php echo h($ov['hero_title'] ?? 'Admissions & Enrolment'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Hero Subtitle / Description
              </label>
              <textarea name="hero_subtitle" rows="3" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($ov['hero_subtitle'] ?? ''); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Primary Button Text
                </label>
                <input type="text" name="btn1_text" value="<?php echo h($ov['btn1_text'] ?? 'Enrol Now'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Primary Button URL
                </label>
                <input type="text" name="btn1_url" value="<?php echo h($ov['btn1_url'] ?? '/admissions/enrol-now'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Secondary Button Text
                </label>
                <input type="text" name="btn2_text" value="<?php echo h($ov['btn2_text'] ?? 'Check Eligibility'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Secondary Button URL
                </label>
                <input type="text" name="btn2_url" value="<?php echo h($ov['btn2_url'] ?? '/admissions/eligibility'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>
          </div>

        <!-- Section 2: Enrol Journey & Docs -->
        <?php elseif ($selected_sec === 'enrol'): 
          $en = $adm_cms['enrol'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($en['title'] ?? 'Simple 5-Step Admissions Journey'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($en['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Steps List -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                5-Step Enrolment Workflow
              </label>
              <div style="display: grid; gap: 1rem;">
                <?php foreach (($en['steps'] ?? []) as $i => $step): ?>
                  <div style="background: var(--color-surface-warm); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                      <span style="font-weight: 800; color: var(--color-gold); font-size: 0.85rem;">
                        STEP <?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?>
                      </span>
                      <input type="hidden" name="steps[<?php echo $i; ?>][id]" value="<?php echo h($step['id'] ?? ($i + 1)); ?>">
                    </div>
                    <div style="display: grid; gap: 0.75rem;">
                      <input type="text" name="steps[<?php echo $i; ?>][title]" value="<?php echo h($step['title'] ?? ''); ?>" placeholder="Step Title" class="form-control" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-weight: 600;">
                      <textarea name="steps[<?php echo $i; ?>][desc]" rows="2" placeholder="Step Description" class="form-control" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.88rem;"><?php echo h($step['desc'] ?? ''); ?></textarea>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Required Docs Checklist -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Required Documents Checklist (One per line)
              </label>
              <textarea name="required_docs" rows="5" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.88rem; line-height: 1.6;"><?php echo h(implode("\n", $en['required_docs'] ?? [])); ?></textarea>
            </div>
          </div>

        <!-- Section 3: Eligibility & Age Matrix -->
        <?php elseif ($selected_sec === 'eligibility'): 
          $el = $adm_cms['eligibility'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($el['title'] ?? 'Eligibility & Age Criteria'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($el['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Stages Table -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Age & Grade Stages (NEP 2020 Aligned)
              </label>
              <div style="display: grid; gap: 1rem;">
                <?php foreach (($el['stages'] ?? []) as $i => $stage): ?>
                  <div style="background: var(--color-surface-warm); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <input type="hidden" name="stages[<?php echo $i; ?>][id]" value="<?php echo h($stage['id'] ?? ($i + 1)); ?>">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                      <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Stage</label>
                        <input type="text" name="stages[<?php echo $i; ?>][stage]" value="<?php echo h($stage['stage'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px; font-weight: 600;">
                      </div>
                      <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Grades</label>
                        <input type="text" name="stages[<?php echo $i; ?>][grades]" value="<?php echo h($stage['grades'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                      </div>
                      <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Age Criteria</label>
                        <input type="text" name="stages[<?php echo $i; ?>][age]" value="<?php echo h($stage['age'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                      </div>
                      <div>
                        <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Daily Live Duration</label>
                        <input type="text" name="stages[<?php echo $i; ?>][duration]" value="<?php echo h($stage['duration'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                      </div>
                    </div>
                    <div>
                      <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Key Pedagogical Focus</label>
                      <input type="text" name="stages[<?php echo $i; ?>][focus]" value="<?php echo h($stage['focus'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Mid-Session Transfer Note
              </label>
              <textarea name="mid_session_note" rows="3" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($el['mid_session_note'] ?? ''); ?></textarea>
            </div>
          </div>

        <!-- Section 4: Tuition Fees & Inclusions -->
        <?php elseif ($selected_sec === 'fees'): 
          $fe = $adm_cms['fees'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($fe['title'] ?? 'Fee Structure 2026–2027'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($fe['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Fee Tiers Table -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Grade Fee Tiers
              </label>
              <div style="display: grid; gap: 1rem;">
                <?php foreach (($fe['tiers'] ?? []) as $i => $tier): ?>
                  <div style="background: var(--color-surface-warm); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr 1fr; gap: 0.75rem; align-items: center;">
                    <input type="hidden" name="tiers[<?php echo $i; ?>][id]" value="<?php echo h($tier['id'] ?? ($i + 1)); ?>">
                    <div>
                      <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Grade Cohort</label>
                      <input type="text" name="tiers[<?php echo $i; ?>][grade]" value="<?php echo h($tier['grade'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px; font-weight: 600;">
                    </div>
                    <div>
                      <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Reg. Fee (₹)</label>
                      <input type="text" name="tiers[<?php echo $i; ?>][reg_fee]" value="<?php echo h($tier['reg_fee'] ?? '500'); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>
                    <div>
                      <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Adm. Fee (₹)</label>
                      <input type="text" name="tiers[<?php echo $i; ?>][adm_fee]" value="<?php echo h($tier['adm_fee'] ?? '2,500'); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>
                    <div>
                      <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Tuition/Quarter (₹)</label>
                      <input type="text" name="tiers[<?php echo $i; ?>][tuition_q]" value="<?php echo h($tier['tuition_q'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>
                    <div>
                      <label style="font-size: 0.75rem; font-weight: 600; color: var(--color-muted);">Total Annual (₹)</label>
                      <input type="text" name="tiers[<?php echo $i; ?>][total_annual]" value="<?php echo h($tier['total_annual'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid var(--color-border); border-radius: 4px; font-weight: 700; color: var(--color-navy);">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Payment Terms & Quarterly Schedule
              </label>
              <textarea name="payment_notes" rows="2" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($fe['payment_notes'] ?? ''); ?></textarea>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Complimentary Inclusions (Books / LMS / ERP)
              </label>
              <input type="text" name="complimentary_note" value="<?php echo h($fe['complimentary_note'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Fee Policy PDF Document
              </label>
              <input type="file" name="fee_pdf" accept=".pdf" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              <?php if (!empty($fe['pdf_url'])): ?>
                <div style="font-size: 0.8rem; color: var(--color-muted); margin-top: 0.35rem;">
                  Current PDF: <a href="<?php echo h($fe['pdf_url']); ?>" target="_blank" style="color: var(--color-teal);"><?php echo h($fe['pdf_url']); ?></a>
                </div>
              <?php endif; ?>
            </div>
          </div>

        <!-- Section 5: Academic Calendar & Terms -->
        <?php elseif ($selected_sec === 'calendar'): 
          $cal = $adm_cms['calendar'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($cal['title'] ?? 'Academic Calendar 2026–2027'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($cal['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Terms Overview -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Two-Term Structure & Highlights
              </label>
              <div style="display: grid; gap: 1rem;">
                <?php foreach (($cal['terms'] ?? []) as $i => $term): ?>
                  <div style="background: var(--color-surface-warm); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div style="font-weight: 700; color: var(--color-navy); margin-bottom: 0.5rem;">
                      <?php echo h($term['term'] ?? ''); ?>
                    </div>
                    <p style="font-size: 0.88rem; color: var(--color-text); margin-bottom: 0.75rem;">
                      <?php echo h($term['desc'] ?? ''); ?>
                    </p>
                    <div style="font-size: 0.82rem; color: var(--color-muted);">
                      <?php foreach (($term['milestones'] ?? []) as $ms): ?>
                        <div style="margin-bottom: 0.25rem;">
                          • <strong><?php echo h($ms['month'] ?? ''); ?>:</strong> <?php echo h($ms['event'] ?? ''); ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Downloadable Official Academic Calendar (PDF)
              </label>
              <input type="file" name="calendar_pdf" accept=".pdf" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              <?php if (!empty($cal['pdf_url'])): ?>
                <div style="font-size: 0.8rem; color: var(--color-muted); margin-top: 0.35rem;">
                  Current PDF: <a href="<?php echo h($cal['pdf_url']); ?>" target="_blank" style="color: var(--color-teal);"><?php echo h($cal['pdf_url']); ?></a>
                </div>
              <?php endif; ?>
            </div>
          </div>

        <!-- Section 6: Counselor CTA & FAQs -->
        <?php elseif ($selected_sec === 'counselor_cta'): 
          $c_cta = $adm_cms['counselor_cta'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Parent FAQ Cross-Link Block</h3>
              <div style="display: grid; gap: 0.75rem;">
                <div>
                  <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Kicker</label>
                  <input type="text" name="faq_kicker" value="<?php echo h($c_cta['faq_kicker'] ?? 'Got Admissions Questions?'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                </div>
                <div>
                  <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Heading</label>
                  <input type="text" name="faq_title" value="<?php echo h($c_cta['faq_title'] ?? 'Parent FAQ & Transitions'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                </div>
                <div>
                  <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Description</label>
                  <textarea name="faq_desc" rows="2" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($c_cta['faq_desc'] ?? ''); ?></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                  <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Button Text</label>
                    <input type="text" name="faq_btn_text" value="<?php echo h($c_cta['faq_btn_text'] ?? 'Read Complete 18-Question FAQ'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                  </div>
                  <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Button URL</label>
                    <input type="text" name="faq_btn_url" value="<?php echo h($c_cta['faq_btn_url'] ?? '/faq'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                  </div>
                </div>
              </div>
            </div>

            <div style="background: var(--color-surface-warm); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Counselor Callback Banner</h3>
              <div style="display: grid; gap: 0.75rem;">
                <div>
                  <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Heading</label>
                  <input type="text" name="title" value="<?php echo h($c_cta['title'] ?? 'Speak with an Admissions Counselor'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                </div>
                <div>
                  <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Description</label>
                  <textarea name="desc" rows="2" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($c_cta['desc'] ?? ''); ?></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                  <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Primary CTA Text</label>
                    <input type="text" name="btn_primary_text" value="<?php echo h($c_cta['btn_primary_text'] ?? 'Request a Callback'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                  </div>
                  <div>
                    <label style="font-size: 0.8rem; font-weight: 600; color: var(--color-navy);">Secondary CTA Text</label>
                    <input type="text" name="btn_secondary_text" value="<?php echo h($c_cta['btn_secondary_text'] ?? 'Online Application'); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Bottom Actions Bar -->
        <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
          <a href="/admin/admissions-cms.php?sec=<?php echo urlencode($selected_sec); ?>" class="btn btn-outline" style="padding: 0.65rem 1.25rem; font-size: 0.9rem;">
            ↻ Reset / Reload
          </a>

          <button type="submit" id="saveSubmitBtn" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.65rem 1.75rem; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>💾</span> Save <?php echo h($sec_meta['name']); ?>
          </button>
        </div>

      </div>
    </form>
  </div>

</div>

<script>
function setSectionAction(action) {
  const actionInput = document.getElementById('sec_action_input');
  const pendingBox = document.getElementById('pendingRemovalBox');
  const saveBtn = document.getElementById('saveSubmitBtn');

  if (action === 'remove') {
    actionInput.value = 'remove';
    if (pendingBox) pendingBox.style.display = 'block';
    if (saveBtn) {
      saveBtn.style.backgroundColor = '#dc2626';
      saveBtn.style.borderColor = '#dc2626';
      saveBtn.innerHTML = '<span>⚠️</span> Confirm Removal &amp; Save';
    }
  } else if (action === 'restore') {
    actionInput.value = 'restore';
    document.getElementById('sectionForm').submit();
  }
}

function cancelSectionAction() {
  const actionInput = document.getElementById('sec_action_input');
  const pendingBox = document.getElementById('pendingRemovalBox');
  const saveBtn = document.getElementById('saveSubmitBtn');

  actionInput.value = '';
  if (pendingBox) pendingBox.style.display = 'none';
  if (saveBtn) {
    saveBtn.style.backgroundColor = 'var(--color-navy)';
    saveBtn.style.borderColor = 'var(--color-navy)';
    saveBtn.innerHTML = '<span>💾</span> Save <?php echo addslashes(h($sec_meta['name'])); ?>';
  }
}
</script>

<?php
include_once dirname(__FILE__) . '/footer.php';
