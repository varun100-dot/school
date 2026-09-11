<?php
// Zuvio Global School - Admin Homepage CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$use_mock = !$db;
$action = $_GET['action'] ?? 'list';
$key = $_GET['key'] ?? '';
$card_id = isset($_GET['card_id']) ? (int)$_GET['card_id'] : 0;
$msg = $_GET['msg'] ?? '';
$error = '';

// Check if tables exist
if ($db) {
    try {
        $db->query("SELECT 1 FROM `homepage_sections` LIMIT 1");
    } catch (Exception $e) {
        $use_mock = true;
    }
}

// Mock fallback session initialization
if ($use_mock) {
    if (!isset($_SESSION['mock_homepage_sections'])) {
        $_SESSION['mock_homepage_sections'] = [
            'about_short' => [
                'section_key' => 'about_short',
                'title' => 'About Zuvio',
                'subtitle' => 'Learning Beyond Boundaries',
                'content' => "Zuvio Global School is an online school built on one belief: education should adapt to the child, not the child to the system.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere. Different ways of learning. One community. Equal opportunities.\n\nThat's what learning beyond boundaries means.",
                'image' => '/assets/images/about_us_hero.jpg',
                'cta_text' => 'Read Our Story',
                'cta_url' => '/about',
                'cta_enabled' => 1,
                'sort_order' => 1,
                'is_active' => 1
            ],
            'who_should_choose' => [
                'section_key' => 'who_should_choose',
                'title' => 'Who Should Choose Zuvio',
                'subtitle' => 'Tailored for Modern Learners',
                'content' => "Zuvio is for families who want learning to fit their life — not their life to revolve around a timetable.\n\nIf you believe education should adapt to the child, Zuvio may be the right choice.",
                'image' => '',
                'cta_text' => 'Check Eligibility',
                'cta_url' => '/admissions#eligibility',
                'cta_enabled' => 1,
                'sort_order' => 2,
                'is_active' => 1
            ],
            'curriculum_intro' => [
                'section_key' => 'curriculum_intro',
                'title' => 'A Future-Ready Learning Journey — K to Grade 8',
                'subtitle' => 'Curriculum Pathways',
                'content' => 'Aligned with CBSE, NEP 2020 and NCF — strong academic foundations blended with creativity, communication, digital fluency and real-world learning.',
                'image' => '',
                'cta_text' => 'Explore the Full Curriculum →',
                'cta_url' => '/curriculum',
                'cta_enabled' => 1,
                'sort_order' => 3,
                'is_active' => 1
            ],
            'learning_framework' => [
                'section_key' => 'learning_framework',
                'title' => 'The Zuvio Learning Framework',
                'subtitle' => 'From Knowing to Doing',
                'content' => "Know → Think → Create → Connect → Apply\n\nKnowledge → Understanding → Application → Innovation",
                'image' => '',
                'cta_text' => 'Learn About Methodology',
                'cta_url' => '/academics',
                'cta_enabled' => 1,
                'sort_order' => 4,
                'is_active' => 1
            ],
            'beyond_textbook' => [
                'section_key' => 'beyond_textbook',
                'title' => 'Learning Beyond the Textbook',
                'subtitle' => 'Real-World Classrooms',
                'content' => 'Because the world is the real classroom. Hands-on discovery, responsible technology, communication, life skills, creativity, and global exposure.',
                'image' => '',
                'cta_text' => 'Explore Beyond',
                'cta_url' => '/beyond',
                'cta_enabled' => 1,
                'sort_order' => 5,
                'is_active' => 1
            ],
            'why_different' => [
                'section_key' => 'why_different',
                'title' => 'What Makes Zuvio Different',
                'subtitle' => 'The Zuvio Edge',
                'content' => 'Assessment for Growth, Personalised Learning, and Zuvio Beyond — preparing the Zuvio Graduate by the end of Grade 8.',
                'image' => '',
                'cta_text' => 'Compare Our Approach',
                'cta_url' => '/academics',
                'cta_enabled' => 1,
                'sort_order' => 6,
                'is_active' => 1
            ],
            'inclusivity' => [
                'section_key' => 'inclusivity',
                'title' => 'Inclusivity & Beyond',
                'subtitle' => 'Equal Opportunities For Every Child',
                'content' => 'Every Child. Every Mind. Every Possibility. Inclusive by design with personalized plans and qualified special educator support.',
                'image' => '',
                'cta_text' => 'Special Ed Support',
                'cta_url' => '/academics#special-education',
                'cta_enabled' => 1,
                'sort_order' => 7,
                'is_active' => 1
            ],
            'statistics' => [
                'section_key' => 'statistics',
                'title' => 'Statistics / Benchmarks',
                'subtitle' => 'Institutional Standards',
                'content' => 'K–8 Grade Spectrum | 15:1 Max Cohort | 100% Live Online | CBSE & NEP 2020 Aligned | 6 Beyond Domains',
                'image' => '',
                'cta_text' => '',
                'cta_url' => '',
                'cta_enabled' => 0,
                'sort_order' => 8,
                'is_active' => 1
            ],
            'founder_message' => [
                'section_key' => 'founder_message',
                'title' => 'Learning Without Boundaries. Growing With Purpose.',
                'subtitle' => 'Founder’s Message',
                'content' => "Dear Parents, Students and Members of the Zuvio Community,\n\nEducation today must prepare children not only for examinations, but for a world that is constantly evolving.\n\nAt Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom.",
                'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp',
                'cta_text' => 'Read Her Full Message',
                'cta_url' => '/founder-message',
                'cta_enabled' => 1,
                'sort_order' => 9,
                'is_active' => 1
            ],
            'final_cta' => [
                'section_key' => 'final_cta',
                'title' => 'Ready to Experience Future-Ready Schooling?',
                'subtitle' => 'Admissions Open 2026–2027',
                'content' => 'Take the first step towards personalized, boundaryless education for your child.',
                'image' => '',
                'cta_text' => 'Enrol Now',
                'cta_url' => '/admissions#enrol',
                'cta_enabled' => 1,
                'sort_order' => 10,
                'is_active' => 1
            ]
        ];
    }
}

// 1. Action Handler: Toggle Section Active
if ($action === 'toggle' && !empty($key)) {
    if (!$use_mock && $db) {
        try {
            $stmt = $db->prepare("UPDATE `homepage_sections` SET `is_active` = NOT `is_active` WHERE `section_key` = ?");
            $stmt->execute([$key]);
            header('Location: /admin/homepage.php?msg=updated');
            exit;
        } catch (Exception $e) {
            $error = 'Toggle Error: ' . $e->getMessage();
        }
    } else {
        if (isset($_SESSION['mock_homepage_sections'][$key])) {
            $_SESSION['mock_homepage_sections'][$key]['is_active'] = $_SESSION['mock_homepage_sections'][$key]['is_active'] ? 0 : 1;
            header('Location: /admin/homepage.php?msg=updated');
            exit;
        }
    }
}

// 2. Action Handler: Save Section
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit_section') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security token invalid. Please refresh and try again.';
    } else {
        $s_key = trim($_POST['section_key'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $video = trim($_POST['video'] ?? '');
        $cta_text = trim($_POST['cta_text'] ?? '');
        $cta_url = trim($_POST['cta_url'] ?? '');
        $cta_enabled = isset($_POST['cta_enabled']) ? 1 : 0;
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title)) {
            $error = 'Section title is required.';
        } else {
            if (!$use_mock && $db) {
                try {
                    $stmt = $db->prepare("
                        INSERT INTO `homepage_sections` (`section_key`, `title`, `subtitle`, `content`, `image`, `video`, `cta_text`, `cta_url`, `cta_enabled`, `sort_order`, `is_active`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        `title` = VALUES(`title`), `subtitle` = VALUES(`subtitle`), `content` = VALUES(`content`),
                        `image` = VALUES(`image`), `video` = VALUES(`video`), `cta_text` = VALUES(`cta_text`),
                        `cta_url` = VALUES(`cta_url`), `cta_enabled` = VALUES(`cta_enabled`),
                        `sort_order` = VALUES(`sort_order`), `is_active` = VALUES(`is_active`)
                    ");
                    $stmt->execute([$s_key, $title, $subtitle, $content, $image, $video, $cta_text, $cta_url, $cta_enabled, $sort_order, $is_active]);
                    header('Location: /admin/homepage.php?msg=saved');
                    exit;
                } catch (Exception $e) {
                    $error = 'Save Error: ' . $e->getMessage();
                }
            } else {
                $_SESSION['mock_homepage_sections'][$s_key] = [
                    'section_key' => $s_key,
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'content' => $content,
                    'image' => $image,
                    'video' => $video,
                    'cta_text' => $cta_text,
                    'cta_url' => $cta_url,
                    'cta_enabled' => $cta_enabled,
                    'sort_order' => $sort_order,
                    'is_active' => $is_active
                ];
                header('Location: /admin/homepage.php?msg=saved');
                exit;
            }
        }
    }
}

// Fetch Sections List
$section_list = [];
if (!$use_mock && $db) {
    try {
        $stmt = $db->query("SELECT * FROM `homepage_sections` ORDER BY `sort_order` ASC");
        $section_list = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($section_list)) {
    $section_list = array_values($_SESSION['mock_homepage_sections'] ?? []);
}

// Current Section for Edit
$current_section = null;
if ($action === 'edit' && !empty($key)) {
    if (!$use_mock && $db) {
        try {
            $stmt = $db->prepare("SELECT * FROM `homepage_sections` WHERE `section_key` = ? LIMIT 1");
            $stmt->execute([$key]);
            $current_section = $stmt->fetch();
        } catch (Exception $e) {}
    }
    if (!$current_section && isset($_SESSION['mock_homepage_sections'][$key])) {
        $current_section = $_SESSION['mock_homepage_sections'][$key];
    }
}

$page_slug = 'admin-homepage';
include dirname(__FILE__) . '/header.php';
?>

<div class="admin-container" style="max-width: 1100px; margin: 0 auto;">
  
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-size: 1.75rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0 0 0.25rem 0;">Homepage Sections CMS</h1>
      <p style="color: var(--color-muted); font-size: 0.9rem; margin: 0;">Manage titles, descriptions, imagery, CTAs, and active visibility for all homepage sections.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
      <a href="/admin/hero" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1rem;">Manage Hero Slides</a>
      <a href="/admin/faqs.php" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1rem;">Manage FAQs</a>
      <a href="/admin/testimonials.php" class="btn btn-outline" style="font-size: 0.85rem; padding: 0.5rem 1rem;">Manage Testimonials</a>
    </div>
  </div>

  <?php if ($msg === 'saved'): ?>
    <div style="background-color: #DEF7EC; border-left: 4px solid #10B981; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #03543F; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
      Section updated successfully.
    </div>
  <?php elseif ($msg === 'updated'): ?>
    <div style="background-color: #DEF7EC; border-left: 4px solid #10B981; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #03543F; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
      Section status updated.
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
      Error: <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <?php if ($action === 'edit' && $current_section): ?>
    <!-- Edit Form Card -->
    <div class="card" style="padding: 2.5rem; border-left: none; border-top: 4px solid var(--color-gold); background: #FFFFFF; margin-bottom: 2rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem;">
        <h2 style="font-size: 1.35rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0;">Edit Section: <code><?php echo h($current_section['section_key']); ?></code></h2>
        <a href="/admin/homepage.php" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 1rem;">Cancel</a>
      </div>

      <form method="POST" action="/admin/homepage.php?action=edit_section">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <input type="hidden" name="section_key" value="<?php echo h($current_section['section_key']); ?>">

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
          <div>
            <div class="admin-form-group">
              <label class="admin-label">Section Title *</label>
              <input type="text" name="title" value="<?php echo h($current_section['title']); ?>" required class="admin-input">
            </div>

            <div class="admin-form-group">
              <label class="admin-label">Subtitle / Category Tag</label>
              <input type="text" name="subtitle" value="<?php echo h($current_section['subtitle'] ?? ''); ?>" class="admin-input">
            </div>

            <div class="admin-form-group">
              <label class="admin-label">Section Description / Content</label>
              <textarea name="content" rows="6" class="admin-input" style="line-height: 1.6;"><?php echo h($current_section['content'] ?? ''); ?></textarea>
            </div>
          </div>

          <div>
            <div class="admin-form-group">
              <label class="admin-label">Image URL / Path</label>
              <input type="text" name="image" value="<?php echo h($current_section['image'] ?? ''); ?>" placeholder="/assets/images/..." class="admin-input">
            </div>

            <div class="admin-form-group">
              <label class="admin-label">CTA Button Text</label>
              <input type="text" name="cta_text" value="<?php echo h($current_section['cta_text'] ?? ''); ?>" placeholder="e.g. Learn More" class="admin-input">
            </div>

            <div class="admin-form-group">
              <label class="admin-label">CTA Destination URL</label>
              <input type="text" name="cta_url" value="<?php echo h($current_section['cta_url'] ?? ''); ?>" placeholder="e.g. /about" class="admin-input">
            </div>

            <div class="admin-form-group">
              <label class="admin-label">Display Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($current_section['sort_order'] ?? 0); ?>" class="admin-input">
            </div>

            <div style="margin: 1.25rem 0; display: flex; flex-direction: column; gap: 0.5rem;">
              <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">
                <input type="checkbox" name="cta_enabled" value="1" <?php echo !empty($current_section['cta_enabled']) ? 'checked' : ''; ?>>
                Enable CTA Button
              </label>

              <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" <?php echo !empty($current_section['is_active']) ? 'checked' : ''; ?>>
                Section Published / Active
              </label>
            </div>
          </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem; margin-top: 1.5rem;">
          <a href="/admin/homepage.php" class="btn btn-outline" style="font-size: 0.85rem;">Cancel</a>
          <button type="submit" class="btn btn-primary" style="background-color: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; font-size: 0.85rem; padding: 0.6rem 2rem;">
            Save Section Changes
          </button>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <!-- Sections Table -->
  <div class="card" style="border-left: none; background: #FFFFFF; padding: 0; overflow: hidden; box-shadow: var(--shadow-sm);">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
      <thead>
        <tr style="background-color: var(--color-navy-dark); color: #FFFFFF; font-family: var(--font-secondary);">
          <th style="padding: 1rem 1.25rem;">Key</th>
          <th style="padding: 1rem 1.25rem;">Section Title</th>
          <th style="padding: 1rem 1.25rem;">Subtitle</th>
          <th style="padding: 1rem 1.25rem; text-align: center;">Order</th>
          <th style="padding: 1rem 1.25rem; text-align: center;">Status</th>
          <th style="padding: 1rem 1.25rem; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($section_list as $s): 
          $active = !empty($s['is_active']);
        ?>
          <tr style="border-bottom: 1px solid var(--color-border);">
            <td style="padding: 1rem 1.25rem; font-family: monospace; font-size: 0.82rem; color: var(--color-teal); font-weight: 700;">
              <?php echo h($s['section_key']); ?>
            </td>
            <td style="padding: 1rem 1.25rem; font-weight: 600; color: var(--color-navy);">
              <?php echo h($s['title']); ?>
            </td>
            <td style="padding: 1rem 1.25rem; color: var(--color-muted); font-size: 0.85rem;">
              <?php echo h($s['subtitle'] ?? '-'); ?>
            </td>
            <td style="padding: 1rem 1.25rem; text-align: center; font-weight: 600;">
              <?php echo (int)($s['sort_order'] ?? 0); ?>
            </td>
            <td style="padding: 1rem 1.25rem; text-align: center;">
              <a href="/admin/homepage.php?action=toggle&key=<?php echo urlencode($s['section_key']); ?>" style="text-decoration: none;">
                <?php if ($active): ?>
                  <span style="background-color: #DEF7EC; color: #03543F; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">Active</span>
                <?php else: ?>
                  <span style="background-color: #FEE2E2; color: #991B1B; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">Inactive</span>
                <?php endif; ?>
              </a>
            </td>
            <td style="padding: 1rem 1.25rem; text-align: right;">
              <a href="/admin/homepage.php?action=edit&key=<?php echo urlencode($s['section_key']); ?>" class="btn btn-outline" style="padding: 0.35rem 0.85rem; font-size: 0.8rem; font-weight: 600;">
                Edit
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<?php
include dirname(__FILE__) . '/footer.php';
?>
