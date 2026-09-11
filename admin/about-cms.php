<?php
// Zuvio Global School - Admin About Us Pillar CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';

// Prepopulate mock data
if (!isset($_SESSION['mock_about_cms'])) {
    $_SESSION['mock_about_cms'] = [
        'story_title' => 'Learning Without Boundaries, Growing With Purpose',
        'story_content' => "Zuvio Global School is an online school built on one belief: education should adapt to the child, not the child to the system.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere.",
        'story_image' => '/assets/images/about_us_hero.jpg',
        'vision' => 'To empower learners globally through boundaryless, child-centric, and future-ready schooling that nurtures intellect, empathy, and creative agency.',
        'mission' => 'To deliver high-rigor CBSE and NEP-aligned education via small interactive cohorts, Oxford thematic learning, and ethical AI support from the safety of home.',
        'founder_title' => 'Learning Without Boundaries. Growing With Purpose.',
        'founder_message' => "Dear Parents, Students and Members of the Zuvio Community,\n\nEducation today must prepare children not only for examinations, but for a world that is constantly evolving.\n\nAt Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is shaped by curiosity, consistency, personal attention and the freedom to explore.\n\nEvery child learns differently, at their own pace, with their own unique strengths. Our vision is to create an educational experience that adapts to the student — combining academic rigor with flexibility, modern pedagogy with caring mentorship.\n\nThrough our Oxford theme-based curriculum, interactive live sessions, and holistic development pathways, we strive to build confident, compassionate, and future-ready global citizens.\n\nWe welcome you to partner with us in shaping a joyful and purposeful learning journey for your child.\n\nWarm regards,\nFounder\nZuvio Global School",
        'founder_photo' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $_SESSION['mock_about_cms']['story_title'] = trim($_POST['story_title'] ?? '');
        $_SESSION['mock_about_cms']['story_content'] = trim($_POST['story_content'] ?? '');
        $_SESSION['mock_about_cms']['story_image'] = trim($_POST['story_image'] ?? '');
        $_SESSION['mock_about_cms']['vision'] = trim($_POST['vision'] ?? '');
        $_SESSION['mock_about_cms']['mission'] = trim($_POST['mission'] ?? '');
        $_SESSION['mock_about_cms']['founder_title'] = trim($_POST['founder_title'] ?? '');
        $_SESSION['mock_about_cms']['founder_message'] = trim($_POST['founder_message'] ?? '');
        $_SESSION['mock_about_cms']['founder_photo'] = trim($_POST['founder_photo'] ?? '');

        // Also update homepage founder_message if DB available
        if ($db) {
            try {
                $stmt = $db->prepare("
                    UPDATE `homepage_sections` 
                    SET `title` = ?, `content` = ?, `image` = ? 
                    WHERE `section_key` = 'founder_message'
                ");
                $stmt->execute([
                    $_SESSION['mock_about_cms']['founder_title'],
                    $_SESSION['mock_about_cms']['founder_message'],
                    $_SESSION['mock_about_cms']['founder_photo']
                ]);
            } catch (Exception $e) {
                // Silently keep session update
            }
        }

        header('Location: /admin/about-cms.php?msg=saved');
        exit;
    }
}

$data = $_SESSION['mock_about_cms'];
$page_slug = 'admin-about-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      About Us Pillar CMS
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage About Zuvio narrative, Vision & Mission, Leadership, and the official Founder’s Message.
    </p>
  </div>
  <div style="display: flex; gap: 0.5rem;">
    <a href="/about" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview /about &nearr;
    </a>
    <a href="/founder-message" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview Founder's Letter &nearr;
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    About Us content published successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<form method="POST" action="/admin/about-cms.php">
  <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

  <!-- Section 1: About Zuvio & Story -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      1. About Zuvio & School Story
    </h3>
    
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Headline Title</label>
      <input type="text" name="story_title" value="<?php echo h($data['story_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>

    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Story Content</label>
      <textarea name="story_content" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['story_content']); ?></textarea>
    </div>

    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Feature Banner Image URL</label>
      <input type="text" name="story_image" value="<?php echo h($data['story_image']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
  </div>

  <!-- Section 2: Vision & Mission -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1rem; font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      2. Institutional Vision & Mission
    </h3>
    
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Our Vision</label>
      <textarea name="vision" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($data['vision']); ?></textarea>
    </div>

    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Our Mission</label>
      <textarea name="mission" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($data['mission']); ?></textarea>
    </div>
  </div>

  <!-- Section 3: Official Founder's Message -->
  <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem; margin-bottom: 1rem;">
      <h3 style="color: var(--color-navy); font-family: var(--font-secondary); font-size: 1.2rem; margin: 0;">
        3. Founder’s Message (Official)
      </h3>
      <span style="background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">
        Replaces legacy CEO Message
      </span>
    </div>
    
    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Letter Title</label>
      <input type="text" name="founder_title" value="<?php echo h($data['founder_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>

    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Letter Content (Verbatim Sign-off)</label>
      <textarea name="founder_message" rows="9" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($data['founder_message']); ?></textarea>
      <small style="color: var(--color-muted); font-size: 0.75rem; margin-top: 0.25rem; display: block;">
        Must strictly conclude with: Warm regards, Founder, Zuvio Global School.
      </small>
    </div>

    <div class="form-group" style="margin-bottom: 1.25rem;">
      <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Founder Portrait Photo URL</label>
      <input type="text" name="founder_photo" value="<?php echo h($data['founder_photo']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
    </div>
  </div>

  <div style="display: flex; gap: 1rem;">
    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 0.95rem;">
      Save About Us Changes
    </button>
  </div>
</form>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
