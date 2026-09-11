<?php
// Zuvio Global School - Admin Beyond Pillar CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';

// Prepopulate mock data
if (!isset($_SESSION['mock_beyond_cms'])) {
    $_SESSION['mock_beyond_cms'] = [
        'clubs_title' => 'Beyond the Screen — Virtual Clubs & Activities',
        'clubs_content' => "Every Friday afternoon, textbooks make way for passions. Zuvio's curated virtual clubs are teacher-mentored and student-led: Coding & Robotics, Model United Nations (MUN), Visual Arts & 3D Modeling, Chess & Strategy, Environmental Action, and Creative Writing.",
        'achievers_title' => 'Student Spotlights & International Achievers',
        'achievers_content' => "Our students compete and excel across national Olympiads, regional chess tournaments, international coding hackathons, and creative writing contests. Flexible schedules give student-athletes and performers the room to shine.",
        'gallery_title' => 'Life at Zuvio — Collaborative Activity Gallery',
        'gallery_content' => 'Showcasing project prototypes, digital art galleries, virtual science fairs, and community celebrations from across our global student cohorts.'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $_SESSION['mock_beyond_cms']['clubs_title'] = trim($_POST['clubs_title'] ?? '');
        $_SESSION['mock_beyond_cms']['clubs_content'] = trim($_POST['clubs_content'] ?? '');
        $_SESSION['mock_beyond_cms']['achievers_title'] = trim($_POST['achievers_title'] ?? '');
        $_SESSION['mock_beyond_cms']['achievers_content'] = trim($_POST['achievers_content'] ?? '');
        $_SESSION['mock_beyond_cms']['gallery_title'] = trim($_POST['gallery_title'] ?? '');
        $_SESSION['mock_beyond_cms']['gallery_content'] = trim($_POST['gallery_content'] ?? '');

        header('Location: /admin/beyond-cms.php?msg=saved');
        exit;
    }
}

$data = $_SESSION['mock_beyond_cms'];
$page_slug = 'admin-beyond-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Beyond Pillar CMS
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage Co-curricular Clubs, Student Achievers & Spotlights, and the Activity Gallery.
    </p>
  </div>
  <div>
    <a href="/#beyond-textbook" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview Beyond Section &nearr;
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Beyond pillar content saved successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<form method="POST" action="/admin/beyond-cms.php">
  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

  <!-- 1. Co-curricular & Clubs -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      1. Co-Curricular & Friday Virtual Clubs
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Section Title</label>
      <input type="text" name="clubs_title" value="<?php echo h($data['clubs_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Clubs Description</label>
      <textarea name="clubs_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['clubs_content']); ?></textarea>
    </div>
  </div>

  <!-- 2. Student Achievers -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      2. Student Achievers & Spotlights
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Section Title</label>
      <input type="text" name="achievers_title" value="<?php echo h($data['achievers_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Achievers Narrative</label>
      <textarea name="achievers_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['achievers_content']); ?></textarea>
    </div>
  </div>

  <!-- 3. Gallery -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      3. Activity & Showcase Gallery
    </h3>
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Section Title</label>
      <input type="text" name="gallery_title" value="<?php echo h($data['gallery_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
    <div class="form-group">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Gallery Overview</label>
      <textarea name="gallery_content" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['gallery_content']); ?></textarea>
    </div>
  </div>

  <div style="display: flex; gap: 1rem;">
    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
      Save Beyond Changes
    </button>
  </div>
</form>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
