<?php
// Zuvio Global School - Admin Admissions Pillar CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';

// Prepopulate mock data
if (!isset($_SESSION['mock_admissions_cms'])) {
    $_SESSION['mock_admissions_cms'] = [
        'enrol_title' => 'Simple 5-Step Admissions Journey',
        'enrol_steps' => "1. Online Application Form\n2. Child Diagnostic Interaction (Informal)\n3. Academic Pathway Recommendation\n4. Document Verification & Fee Payment\n5. Welcome Kit & LMS Onboarding",
        'eligibility_title' => 'Age Criteria & Grade Cut-Offs (NEP 2020 Aligned)',
        'eligibility_content' => "Early Years (Nursery/LKG/UKG): 3 to 5 years\nFoundation Stage (Grades 1-2): 6 to 7 years\nPreparatory Stage (Grades 3-5): 8 to 10 years\nMiddle School (Grades 6-8): 11 to 14 years",
        'calendar_title' => 'Academic Year 2026-2027 Calendar',
        'calendar_content' => "Term 1: June 2026 – September 2026\nTerm 2: October 2026 – February 2027\nMid-Term Admissions: Open for Term 2 with dedicated catch-up modules.",
        'fees_title' => 'Transparent, Predictable Fee Structure',
        'fees_content' => 'No hidden technology surcharges or unexpected add-ons. Fees include live sessions, Oxford thematic learning material access, LMS licenses, and quarterly assessments.'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $_SESSION['mock_admissions_cms']['enrol_title'] = trim($_POST['enrol_title'] ?? '');
        $_SESSION['mock_admissions_cms']['enrol_steps'] = trim($_POST['enrol_steps'] ?? '');
        $_SESSION['mock_admissions_cms']['eligibility_title'] = trim($_POST['eligibility_title'] ?? '');
        $_SESSION['mock_admissions_cms']['eligibility_content'] = trim($_POST['eligibility_content'] ?? '');
        $_SESSION['mock_admissions_cms']['calendar_title'] = trim($_POST['calendar_title'] ?? '');
        $_SESSION['mock_admissions_cms']['calendar_content'] = trim($_POST['calendar_content'] ?? '');
        $_SESSION['mock_admissions_cms']['fees_title'] = trim($_POST['fees_title'] ?? '');
        $_SESSION['mock_admissions_cms']['fees_content'] = trim($_POST['fees_content'] ?? '');

        header('Location: /admin/admissions-cms.php?msg=saved');
        exit;
    }
}

$data = $_SESSION['mock_admissions_cms'];
$page_slug = 'admin-admissions-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Admissions Pillar CMS
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage Enrolment Process, Eligibility Matrix, Academic Calendar, and Fee Structure content.
    </p>
  </div>
  <div>
    <a href="/admissions" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview /admissions &nearr;
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Admissions content published successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<form method="POST" action="/admin/admissions-cms.php">
  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

  <!-- 1. Enrolment Process -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      1. Enrolment Process & Steps
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="enrol_title" value="<?php echo h($data['enrol_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Enrolment Steps</label>
      <textarea name="enrol_steps" rows="5" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['enrol_steps']); ?></textarea>
    </div>
  </div>

  <!-- 2. Eligibility & Age Matrix -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      2. Eligibility & Age Matrix (NEP 2020)
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="eligibility_title" value="<?php echo h($data['eligibility_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Age Breakdown & Rules</label>
      <textarea name="eligibility_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['eligibility_content']); ?></textarea>
    </div>
  </div>

  <!-- 3. Academic Calendar -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      3. Academic Calendar & Term Intake
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="calendar_title" value="<?php echo h($data['calendar_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Terms Schedule & Mid-Term Notes</label>
      <textarea name="calendar_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['calendar_content']); ?></textarea>
    </div>
  </div>

  <!-- 4. Fees Structure -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      4. Fee Structure & Policies
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="fees_title" value="<?php echo h($data['fees_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Fee Policy Summary</label>
      <textarea name="fees_content" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['fees_content']); ?></textarea>
    </div>
  </div>

  <div style="display: flex; gap: 1rem;">
    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
      Save Admissions Changes
    </button>
  </div>
</form>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
