<?php
// Zuvio Global School - Admin Academics Pillar CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';

// Prepopulate mock data
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [
        'tech_title' => 'Technology That Empowers, Never Replaces Teachers',
        'tech_content' => "At Zuvio, technology serves humanity. Our bespoke Learning Management System is custom-engineered for age-appropriate online education — combining live HD video, interactive digital whiteboards, instant polling, and AI diagnostic insights.",
        'curriculum_title' => 'NEP 2020 & Oxford Thematic Curriculum (K to Grade 8)',
        'curriculum_content' => "Our curriculum is comprehensively aligned with the Central Board of Secondary Education (CBSE) and the National Curriculum Framework (NCF) under NEP 2020. Interdisciplinary themes connect subjects with real-world problems.",
        'special_ed_title' => 'Inclusive Special Education Needs (SEN) & IEPs',
        'special_ed_content' => "Every child learns uniquely. Our dedicated Special Education wing provides certified counselors, modified pacing, multi-sensory materials, and one-on-one remedial sessions for students requiring differentiated attention.",
        'electives_title' => 'Future-Ready Electives & Global Languages',
        'electives_content' => "Coding & App Building, AI & Robotics, Public Speaking & Debating, Visual Arts & Digital Illustration, Financial Literacy, and Foreign Languages (French, Spanish, German).",
        'nep_title' => 'Holistic 5+3+3+4 NEP 2020 Alignment',
        'nep_content' => "Foundational literacy and numeracy (FLN), continuous formative assessment instead of rote exams, multidisciplinary electives, and bilingual pedagogical scaffolding."
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $_SESSION['mock_academics_cms']['tech_title'] = trim($_POST['tech_title'] ?? '');
        $_SESSION['mock_academics_cms']['tech_content'] = trim($_POST['tech_content'] ?? '');
        $_SESSION['mock_academics_cms']['curriculum_title'] = trim($_POST['curriculum_title'] ?? '');
        $_SESSION['mock_academics_cms']['curriculum_content'] = trim($_POST['curriculum_content'] ?? '');
        $_SESSION['mock_academics_cms']['special_ed_title'] = trim($_POST['special_ed_title'] ?? '');
        $_SESSION['mock_academics_cms']['special_ed_content'] = trim($_POST['special_ed_content'] ?? '');
        $_SESSION['mock_academics_cms']['electives_title'] = trim($_POST['electives_title'] ?? '');
        $_SESSION['mock_academics_cms']['electives_content'] = trim($_POST['electives_content'] ?? '');
        $_SESSION['mock_academics_cms']['nep_title'] = trim($_POST['nep_title'] ?? '');
        $_SESSION['mock_academics_cms']['nep_content'] = trim($_POST['nep_content'] ?? '');

        header('Location: /admin/academics-cms.php?msg=saved');
        exit;
    }
}

$data = $_SESSION['mock_academics_cms'];
$page_slug = 'admin-academics-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Academics Pillar CMS
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage Technology, Curriculum, Special Education, Electives, and NEP 2020 alignment modules.
    </p>
  </div>
  <div>
    <a href="/academics" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview /academics &nearr;
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Academics content published successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<form method="POST" action="/admin/academics-cms.php">
  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

  <!-- 1. Technology & AI LMS -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      1. Technology & Learning LMS
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="tech_title" value="<?php echo h($data['tech_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
      <textarea name="tech_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['tech_content']); ?></textarea>
    </div>
  </div>

  <!-- 2. Curriculum & Stages -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      2. Curriculum & Learning Stages
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="curriculum_title" value="<?php echo h($data['curriculum_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
      <textarea name="curriculum_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['curriculum_content']); ?></textarea>
    </div>
  </div>

  <!-- 3. Special Education & IEPs -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      3. Special Education (SEN) & Individualized Plans (IEPs)
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Title</label>
      <input type="text" name="special_ed_title" value="<?php echo h($data['special_ed_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
      <textarea name="special_ed_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['special_ed_content']); ?></textarea>
    </div>
  </div>

  <!-- 4. Electives & NEP 2020 -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      4. Electives & NEP 2020 Compliance
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Electives Title</label>
      <input type="text" name="electives_title" value="<?php echo h($data['electives_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Electives Summary</label>
      <textarea name="electives_content" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['electives_content']); ?></textarea>
    </div>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">NEP 2020 Summary</label>
      <textarea name="nep_content" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['nep_content']); ?></textarea>
    </div>
  </div>

  <div style="display: flex; gap: 1rem;">
    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
      Save Academics Changes
    </button>
  </div>
</form>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
