<?php
// Zuvio Global School - Admin Admissions CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'overview';

// Ensure $_SESSION['mock_admissions_cms'] is populated with structure
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [];
}
$adm_cms = &$_SESSION['mock_admissions_cms'];

// 1. Defaults for Overview
if (!isset($adm_cms['overview'])) {
    $adm_cms['overview'] = [
        'hero_badge' => 'Academic Year 2026–2027',
        'hero_title' => 'Admissions & Enrolment',
        'hero_subtitle' => 'A seamless, supportive onboarding journey designed to understand your child’s learning style, baseline competencies, and personal interests.'
    ];
}

// 2. Defaults for Enrol Now
if (!isset($adm_cms['enrol'])) {
    $adm_cms['enrol'] = [
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

// 4. Defaults for Calendar
if (!isset($adm_cms['calendar'])) {
    $adm_cms['calendar'] = [
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

// 5. Defaults for Fees
if (!isset($adm_cms['fees'])) {
    $adm_cms['fees'] = [
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

        // 1. Overview Actions
        if ($action === 'save_overview') {
            $adm_cms['overview']['hero_badge'] = trim($_POST['hero_badge'] ?? '');
            $adm_cms['overview']['hero_title'] = trim($_POST['hero_title'] ?? '');
            $adm_cms['overview']['hero_subtitle'] = trim($_POST['hero_subtitle'] ?? '');
            header('Location: /admin/admissions-cms.php?tab=overview&msg=saved');
            exit;
        }

        // 2. Enrol Now Actions
        if ($action === 'save_enrol_overview') {
            $adm_cms['enrol']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['enrol']['subtitle'] = trim($_POST['subtitle'] ?? '');
            
            // Required docs comma separated
            $raw_docs = trim($_POST['required_docs'] ?? '');
            if (!empty($raw_docs)) {
                $adm_cms['enrol']['required_docs'] = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $raw_docs))));
            }
            header('Location: /admin/admissions-cms.php?tab=enrol&msg=saved');
            exit;
        }

        if ($action === 'add_enrol_step') {
            $new_id = time();
            $adm_cms['enrol']['steps'][] = [
                'id' => $new_id,
                'step' => count($adm_cms['enrol']['steps']) + 1,
                'title' => trim($_POST['title'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'sort_order' => count($adm_cms['enrol']['steps']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/admissions-cms.php?tab=enrol&msg=added');
            exit;
        }

        if ($action === 'delete_enrol_step') {
            $id = (int)$_POST['id'];
            $adm_cms['enrol']['steps'] = array_values(array_filter($adm_cms['enrol']['steps'], fn($s) => ($s['id'] ?? 0) !== $id));
            header('Location: /admin/admissions-cms.php?tab=enrol&msg=deleted');
            exit;
        }

        // 3. Eligibility Actions
        if ($action === 'save_eligibility_overview') {
            $adm_cms['eligibility']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['eligibility']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $adm_cms['eligibility']['mid_session_note'] = trim($_POST['mid_session_note'] ?? '');
            header('Location: /admin/admissions-cms.php?tab=eligibility&msg=saved');
            exit;
        }

        if ($action === 'add_eligibility_stage') {
            $new_id = time();
            $adm_cms['eligibility']['stages'][] = [
                'id' => $new_id,
                'stage' => trim($_POST['stage'] ?? ''),
                'grades' => trim($_POST['grades'] ?? ''),
                'age' => trim($_POST['age'] ?? ''),
                'duration' => trim($_POST['duration'] ?? ''),
                'focus' => trim($_POST['focus'] ?? ''),
                'sort_order' => count($adm_cms['eligibility']['stages']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/admissions-cms.php?tab=eligibility&msg=added');
            exit;
        }

        if ($action === 'delete_eligibility_stage') {
            $id = (int)$_POST['id'];
            $adm_cms['eligibility']['stages'] = array_values(array_filter($adm_cms['eligibility']['stages'], fn($s) => ($s['id'] ?? 0) !== $id));
            header('Location: /admin/admissions-cms.php?tab=eligibility&msg=deleted');
            exit;
        }

        // 4. Calendar Actions
        if ($action === 'save_calendar_overview') {
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
                header('Location: /admin/admissions-cms.php?tab=calendar&msg=saved');
                exit;
            }
        }

        // 5. Fees Actions
        if ($action === 'save_fees_overview') {
            $adm_cms['fees']['title'] = trim($_POST['title'] ?? '');
            $adm_cms['fees']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $adm_cms['fees']['payment_notes'] = trim($_POST['payment_notes'] ?? '');
            $adm_cms['fees']['complimentary_note'] = trim($_POST['complimentary_note'] ?? '');
            
            try {
                $uploaded_pdf = handle_admissions_upload('fee_pdf', ['pdf']);
                if ($uploaded_pdf) {
                    $adm_cms['fees']['pdf_url'] = $uploaded_pdf;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                header('Location: /admin/admissions-cms.php?tab=fees&msg=saved');
                exit;
            }
        }

        if ($action === 'add_fee_tier') {
            $new_id = time();
            $adm_cms['fees']['tiers'][] = [
                'id' => $new_id,
                'grade' => trim($_POST['grade'] ?? ''),
                'reg_fee' => trim($_POST['reg_fee'] ?? '500'),
                'adm_fee' => trim($_POST['adm_fee'] ?? '2,500'),
                'tuition_q' => trim($_POST['tuition_q'] ?? ''),
                'total_annual' => trim($_POST['total_annual'] ?? ''),
                'sort_order' => count($adm_cms['fees']['tiers']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/admissions-cms.php?tab=fees&msg=added');
            exit;
        }

        if ($action === 'delete_fee_tier') {
            $id = (int)$_POST['id'];
            $adm_cms['fees']['tiers'] = array_values(array_filter($adm_cms['fees']['tiers'], fn($t) => ($t['id'] ?? 0) !== $id));
            header('Location: /admin/admissions-cms.php?tab=fees&msg=deleted');
            exit;
        }
    }
}

$page_slug = 'admin-admissions-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Admissions CMS Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage dedicated Admissions pages: Overview, Enrol Now, Eligibility, Calendar, and Fee Structure.
    </p>
  </div>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <a href="/admissions" target="_blank" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem;">Preview /admissions &nearr;</a>
    <a href="/admissions/enrol-now" target="_blank" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem;">Enrol Now &nearr;</a>
    <a href="/admissions/fees" target="_blank" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem;">Fees &nearr;</a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Changes published successfully and synchronized with frontend pages.
  </div>
<?php elseif ($msg === 'added'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    New item successfully added.
  </div>
<?php elseif ($msg === 'deleted'): ?>
  <div style="background-color: #fee2e2; border-left: 4px solid #dc2626; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991b1b; font-size: 0.85rem; margin-bottom: 1.5rem;">
    Item deleted successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c; font-size:0.85rem;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; overflow-x: auto;">
  <a href="/admin/admissions-cms.php?tab=overview" style="padding: 0.75rem 1.25rem; font-weight: 600; text-decoration: none; color: <?php echo $tab === 'overview' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; border-bottom: 3px solid <?php echo $tab === 'overview' ? 'var(--color-gold)' : 'transparent'; ?>; font-size: 0.9rem;">
    🏛️ Overview Hub
  </a>
  <a href="/admin/admissions-cms.php?tab=enrol" style="padding: 0.75rem 1.25rem; font-weight: 600; text-decoration: none; color: <?php echo $tab === 'enrol' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; border-bottom: 3px solid <?php echo $tab === 'enrol' ? 'var(--color-gold)' : 'transparent'; ?>; font-size: 0.9rem;">
    📝 Enrol Now (5 Steps)
  </a>
  <a href="/admin/admissions-cms.php?tab=eligibility" style="padding: 0.75rem 1.25rem; font-weight: 600; text-decoration: none; color: <?php echo $tab === 'eligibility' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; border-bottom: 3px solid <?php echo $tab === 'eligibility' ? 'var(--color-gold)' : 'transparent'; ?>; font-size: 0.9rem;">
    🎯 Eligibility &amp; Age Matrix
  </a>
  <a href="/admin/admissions-cms.php?tab=calendar" style="padding: 0.75rem 1.25rem; font-weight: 600; text-decoration: none; color: <?php echo $tab === 'calendar' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; border-bottom: 3px solid <?php echo $tab === 'calendar' ? 'var(--color-gold)' : 'transparent'; ?>; font-size: 0.9rem;">
    🗓️ Calendar &amp; Terms
  </a>
  <a href="/admin/admissions-cms.php?tab=fees" style="padding: 0.75rem 1.25rem; font-weight: 600; text-decoration: none; color: <?php echo $tab === 'fees' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>; border-bottom: 3px solid <?php echo $tab === 'fees' ? 'var(--color-gold)' : 'transparent'; ?>; font-size: 0.9rem;">
    💳 Fees Structure
  </a>
</div>

<!-- Tab 1: Overview -->
<?php if ($tab === 'overview'): ?>
  <div class="card" style="padding: 2rem; max-width: 900px; border: 1.5px solid rgba(6, 43, 99, 0.16);">
    <h3 style="color: var(--color-navy); margin-bottom: 1.5rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      Admissions Overview Hub (/admissions)
    </h3>
    <form method="POST" action="/admin/admissions-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_overview">
      
      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Hero Badge</label>
        <input type="text" name="hero_badge" value="<?php echo h($adm_cms['overview']['hero_badge']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Hero Title</label>
        <input type="text" name="hero_title" value="<?php echo h($adm_cms['overview']['hero_title']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Subtitle / Value Proposition</label>
        <textarea name="hero_subtitle" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit;"><?php echo h($adm_cms['overview']['hero_subtitle']); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary" style="background: var(--color-navy); color: #fff; padding: 0.75rem 1.5rem; border: none; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
        Save Overview &rarr;
      </button>
    </form>
  </div>
<?php endif; ?>

<!-- Tab 2: Enrol Now -->
<?php if ($tab === 'enrol'): ?>
  <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem;">
    <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16);">
      <h3 style="color: var(--color-navy); margin-bottom: 1.5rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Enrolment Page Headers &amp; Required Documents
      </h3>
      <form method="POST" action="/admin/admissions-cms.php">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="save_enrol_overview">
        
        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Process Section Title</label>
          <input type="text" name="title" value="<?php echo h($adm_cms['enrol']['title']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>

        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Subtitle</label>
          <input type="text" name="subtitle" value="<?php echo h($adm_cms['enrol']['subtitle']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Required Documents (One per line)</label>
          <textarea name="required_docs" rows="6" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h(implode("\n", $adm_cms['enrol']['required_docs'] ?? [])); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="background: var(--color-navy); color: #fff; padding: 0.75rem 1.5rem; border: none; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
          Save Enrolment Info &rarr;
        </button>
      </form>
    </div>

    <!-- Steps List & Add -->
    <div>
      <div class="card" style="padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16); margin-bottom: 1.5rem;">
        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-bottom: 1rem;">Add Process Step</h4>
        <form method="POST" action="/admin/admissions-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="add_enrol_step">
          
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.3rem;">Step Title</label>
            <input type="text" name="title" required placeholder="e.g. Schedule Orientation" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.3rem;">Step Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit;"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="background: var(--color-teal); color: #fff; border: none; padding: 0.6rem 1.25rem; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
            + Add Step
          </button>
        </form>
      </div>

      <div class="card" style="padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16);">
        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-bottom: 1rem;">Current Steps</h4>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
          <?php foreach ($adm_cms['enrol']['steps'] as $st): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--color-surface); border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <div>
                <strong>Step <?php echo h($st['step']); ?>:</strong> <?php echo h($st['title']); ?>
              </div>
              <form method="POST" action="/admin/admissions-cms.php" onsubmit="return confirm('Delete this step?');">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="delete_enrol_step">
                <input type="hidden" name="id" value="<?php echo (int)($st['id'] ?? 0); ?>">
                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 0.85rem; font-weight: 600;">Delete</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Tab 3: Eligibility -->
<?php if ($tab === 'eligibility'): ?>
  <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem;">
    <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16);">
      <h3 style="color: var(--color-navy); margin-bottom: 1.5rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Eligibility &amp; Age Matrix Settings
      </h3>
      <form method="POST" action="/admin/admissions-cms.php">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="save_eligibility_overview">
        
        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Title</label>
          <input type="text" name="title" value="<?php echo h($adm_cms['eligibility']['title']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>

        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Subtitle / NEP Rule</label>
          <input type="text" name="subtitle" value="<?php echo h($adm_cms['eligibility']['subtitle']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Mid-Session / Term 2 Policy Note</label>
          <textarea name="mid_session_note" rows="4" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($adm_cms['eligibility']['mid_session_note']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="background: var(--color-navy); color: #fff; padding: 0.75rem 1.5rem; border: none; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
          Save Policy &rarr;
        </button>
      </form>
    </div>

    <!-- Add Stage Row -->
    <div>
      <div class="card" style="padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16); margin-bottom: 1.5rem;">
        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-bottom: 1rem;">Add Stage Criteria</h4>
        <form method="POST" action="/admin/admissions-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="add_eligibility_stage">
          
          <div style="margin-bottom: 0.75rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Stage Name</label>
            <input type="text" name="stage" required placeholder="e.g. Senior Secondary" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div style="margin-bottom: 0.75rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Grades</label>
            <input type="text" name="grades" required placeholder="e.g. Grades 9 – 10" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div style="margin-bottom: 0.75rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Recommended Age</label>
            <input type="text" name="age" required placeholder="e.g. 14 – 15+ Years" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div style="margin-bottom: 0.75rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Daily Duration</label>
            <input type="text" name="duration" required placeholder="e.g. Approx. 3.5 Hours" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Pedagogical Focus</label>
            <input type="text" name="focus" placeholder="e.g. Advanced electives, career preparation" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>

          <button type="submit" class="btn btn-primary" style="background: var(--color-teal); color: #fff; border: none; padding: 0.6rem 1.25rem; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
            + Add Stage
          </button>
        </form>
      </div>

      <div class="card" style="padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16);">
        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-bottom: 1rem;">Current Matrix Rows</h4>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
          <?php foreach ($adm_cms['eligibility']['stages'] as $st): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--color-surface); border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <div>
                <strong><?php echo h($st['stage']); ?>:</strong> <?php echo h($st['grades']); ?> (<?php echo h($st['age']); ?>)
              </div>
              <form method="POST" action="/admin/admissions-cms.php" onsubmit="return confirm('Delete this row?');">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="delete_eligibility_stage">
                <input type="hidden" name="id" value="<?php echo (int)($st['id'] ?? 0); ?>">
                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 0.85rem; font-weight: 600;">Delete</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Tab 4: Calendar -->
<?php if ($tab === 'calendar'): ?>
  <div class="card" style="padding: 2rem; max-width: 900px; border: 1.5px solid rgba(6, 43, 99, 0.16);">
    <h3 style="color: var(--color-navy); margin-bottom: 1.5rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      Academic Calendar Settings &amp; PDF Upload
    </h3>
    <form method="POST" action="/admin/admissions-cms.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_calendar_overview">
      
      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Calendar Page Title</label>
        <input type="text" name="title" value="<?php echo h($adm_cms['calendar']['title']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <div style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Subtitle</label>
        <input type="text" name="subtitle" value="<?php echo h($adm_cms['calendar']['subtitle']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <div style="margin-bottom: 1.5rem; padding: 1.25rem; background: var(--color-surface); border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Downloadable Academic Calendar PDF</label>
        <p style="font-size: 0.8rem; color: var(--color-muted); margin-bottom: 0.75rem;">
          Current File: <a href="<?php echo h($adm_cms['calendar']['pdf_url']); ?>" target="_blank"><?php echo h($adm_cms['calendar']['pdf_url']); ?></a>
        </p>
        <input type="file" name="calendar_pdf" accept=".pdf" style="font-size: 0.85rem;">
      </div>

      <button type="submit" class="btn btn-primary" style="background: var(--color-navy); color: #fff; padding: 0.75rem 1.5rem; border: none; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
        Save Calendar &rarr;
      </button>
    </form>
  </div>
<?php endif; ?>

<!-- Tab 5: Fees -->
<?php if ($tab === 'fees'): ?>
  <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem;">
    <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.16);">
      <h3 style="color: var(--color-navy); margin-bottom: 1.5rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Fee Structure Details &amp; Policies
      </h3>
      <form method="POST" action="/admin/admissions-cms.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="action" value="save_fees_overview">
        
        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Page Title</label>
          <input type="text" name="title" value="<?php echo h($adm_cms['fees']['title']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>

        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Subtitle</label>
          <input type="text" name="subtitle" value="<?php echo h($adm_cms['fees']['subtitle']); ?>" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>

        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Payment Terms &amp; Quarters Note</label>
          <textarea name="payment_notes" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($adm_cms['fees']['payment_notes']); ?></textarea>
        </div>

        <div style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Complimentary Inclusions Note</label>
          <textarea name="complimentary_note" rows="3" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($adm_cms['fees']['complimentary_note']); ?></textarea>
        </div>

        <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--color-surface); border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
          <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.4rem;">Upload / Replace Fee Schedule PDF</label>
          <input type="file" name="fee_pdf" accept=".pdf" style="font-size: 0.85rem;">
        </div>

        <button type="submit" class="btn btn-primary" style="background: var(--color-navy); color: #fff; padding: 0.75rem 1.5rem; border: none; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
          Save Fee Settings &rarr;
        </button>
      </form>
    </div>

    <!-- Fee Tiers List & Add -->
    <div>
      <div class="card" style="padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16); margin-bottom: 1.5rem;">
        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-bottom: 1rem;">Add Grade Fee Tier</h4>
        <form method="POST" action="/admin/admissions-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="add_fee_tier">
          
          <div style="margin-bottom: 0.75rem;">
            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Grade Level</label>
            <input type="text" name="grade" required placeholder="e.g. Grade 9" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Reg. Fee (₹)</label>
              <input type="text" name="reg_fee" value="500" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Adm. Fee (₹)</label>
              <input type="text" name="adm_fee" value="2,500" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
          </div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Tuition/Quarter (₹)</label>
              <input type="text" name="tuition_q" required placeholder="e.g. 24,000" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.2rem;">Annual Total (₹)</label>
              <input type="text" name="total_annual" required placeholder="e.g. 1,05,000" style="width: 100%; padding: 0.55rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="background: var(--color-teal); color: #fff; border: none; padding: 0.6rem 1.25rem; font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;">
            + Add Fee Tier
          </button>
        </form>
      </div>

      <div class="card" style="padding: 1.75rem; border: 1.5px solid rgba(6, 43, 99, 0.16);">
        <h4 style="color: var(--color-navy); font-size: 1.1rem; margin-bottom: 1rem;">Approved Fee Tiers</h4>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
          <?php foreach ($adm_cms['fees']['tiers'] as $t): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: var(--color-surface); border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <div>
                <strong><?php echo h($t['grade']); ?>:</strong> ₹<?php echo h($t['tuition_q']); ?>/Q &bull; Annual: ₹<?php echo h($t['total_annual']); ?>
              </div>
              <form method="POST" action="/admin/admissions-cms.php" onsubmit="return confirm('Delete this tier?');">
                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                <input type="hidden" name="action" value="delete_fee_tier">
                <input type="hidden" name="id" value="<?php echo (int)($t['id'] ?? 0); ?>">
                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 0.85rem; font-weight: 600;">Delete</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
