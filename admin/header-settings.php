<?php
// Zuvio Global School - Main Header CMS Management
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('settings.view');

$msg = $_GET['msg'] ?? '';
$error = '';

// Load persistent header settings
$db_hdr = get_json_setting('cms_header_settings', []);
if (!isset($_SESSION['mock_header_settings']) || !empty($db_hdr)) {
    $_SESSION['mock_header_settings'] = !empty($db_hdr) ? $db_hdr : [];
}
$hdr_cms = &$_SESSION['mock_header_settings'];

// Defaults
if (!isset($hdr_cms['logo_url'])) $hdr_cms['logo_url'] = '/assets/images/logo.png';
if (!isset($hdr_cms['logo_webp_url'])) $hdr_cms['logo_webp_url'] = '/assets/images/logo.webp';
if (!isset($hdr_cms['is_header_visible'])) $hdr_cms['is_header_visible'] = 1;
if (!isset($hdr_cms['is_topbar_visible'])) $hdr_cms['is_topbar_visible'] = 1;
if (!isset($hdr_cms['affiliation_text'])) $hdr_cms['affiliation_text'] = 'Affiliation No: IA 4883 • IAO Accredited • ISSO Member';
if (!isset($hdr_cms['phone_number'])) $hdr_cms['phone_number'] = '7827262956';
if (!isset($hdr_cms['email_address'])) $hdr_cms['email_address'] = 'info@zuvioglobalschool.com';
if (!isset($hdr_cms['primary_cta_text'])) $hdr_cms['primary_cta_text'] = 'Enquire Now';
if (!isset($hdr_cms['primary_cta_url'])) $hdr_cms['primary_cta_url'] = '/contact';
if (!isset($hdr_cms['secondary_cta_text'])) $hdr_cms['secondary_cta_text'] = 'Book a Demo';
if (!isset($hdr_cms['secondary_cta_action'])) $hdr_cms['secondary_cta_action'] = 'javascript:openCallbackModal()';

if (!isset($hdr_cms['social_facebook'])) $hdr_cms['social_facebook'] = 'https://www.facebook.com/share/1XsYWDm3rt/';
if (!isset($hdr_cms['social_instagram'])) $hdr_cms['social_instagram'] = 'https://www.instagram.com/thezuvio/';
if (!isset($hdr_cms['social_linkedin'])) $hdr_cms['social_linkedin'] = 'https://www.linkedin.com/company/zuvio-global-school/';
if (!isset($hdr_cms['social_youtube'])) $hdr_cms['social_youtube'] = 'https://www.youtube.com/@zuvioglobalschool';

// Handle Save POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_header_settings'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please refresh.';
    } else {
        $hdr_cms['is_header_visible'] = isset($_POST['is_header_visible']) ? 1 : 0;
        $hdr_cms['is_topbar_visible'] = isset($_POST['is_topbar_visible']) ? 1 : 0;
        $hdr_cms['affiliation_text'] = trim($_POST['affiliation_text'] ?? '');
        $hdr_cms['phone_number'] = trim($_POST['phone_number'] ?? '');
        $hdr_cms['email_address'] = trim($_POST['email_address'] ?? '');
        $hdr_cms['logo_url'] = trim($_POST['logo_url'] ?? '/assets/images/logo.png');
        $hdr_cms['logo_webp_url'] = trim($_POST['logo_webp_url'] ?? '/assets/images/logo.webp');
        $hdr_cms['primary_cta_text'] = trim($_POST['primary_cta_text'] ?? 'Enquire Now');
        $hdr_cms['primary_cta_url'] = trim($_POST['primary_cta_url'] ?? '/contact');
        $hdr_cms['secondary_cta_text'] = trim($_POST['secondary_cta_text'] ?? 'Book a Demo');
        $hdr_cms['secondary_cta_action'] = trim($_POST['secondary_cta_action'] ?? 'javascript:openCallbackModal()');
        $hdr_cms['social_facebook'] = trim($_POST['social_facebook'] ?? '');
        $hdr_cms['social_instagram'] = trim($_POST['social_instagram'] ?? '');
        $hdr_cms['social_linkedin'] = trim($_POST['social_linkedin'] ?? '');
        $hdr_cms['social_youtube'] = trim($_POST['social_youtube'] ?? '');

        // Handle uploaded logo
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $f = $_FILES['logo_file'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                $up_name = 'logo_' . time() . '.' . $ext;
                $up_dir = dirname(__FILE__) . '/../uploads/';
                if (!is_dir($up_dir)) mkdir($up_dir, 0755, true);
                if (move_uploaded_file($f['tmp_name'], $up_dir . $up_name)) {
                    $hdr_cms['logo_url'] = '/uploads/' . $up_name;
                }
            }
        }

        // Persist JSON setting and general settings table
        set_json_setting('cms_header_settings', $hdr_cms, 'Master Header Settings');
        
        // Also sync to standard settings table keys if available
        if (function_exists('set_setting')) {
            set_setting('logo_url', $hdr_cms['logo_url']);
            set_setting('phone', $hdr_cms['phone_number']);
            set_setting('general_email', $hdr_cms['email_address']);
            set_setting('social_facebook', $hdr_cms['social_facebook']);
            set_setting('social_instagram', $hdr_cms['social_instagram']);
            set_setting('social_linkedin', $hdr_cms['social_linkedin']);
            set_setting('social_youtube', $hdr_cms['social_youtube']);
        }

        header('Location: /admin/header-settings.php?msg=saved');
        exit;
    }
}

$page_slug = 'admin-header-settings';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="max-width: 1080px; margin: 0 auto;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
        Main Header CMS Management
      </h1>
      <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
        Manage the website's brand logo, top utility bar, CTA buttons, and header contact channels.
      </p>
    </div>

    <div style="display: flex; gap: 0.75rem; align-items: center;">
      <a href="/admin/sliding-strip.php" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        &rarr; Manage Sliding Strip
      </a>
      <a href="/admin/navigation.php" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        &rarr; Manage Menu Tree
      </a>
      <a href="/" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        View Live Site ↗
      </a>
    </div>
  </div>

  <?php if ($msg === 'saved'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Saved!</strong> Header settings updated and published to the live website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Error:</strong> <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="/admin/header-settings.php" enctype="multipart/form-data">
    <input type="hidden" name="save_header_settings" value="1">
    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

    <!-- 1. Brand Logo & Header Visibility -->
    <div class="card" style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); border-radius: var(--radius-md); padding: 2rem; margin-bottom: 2rem;">
      <h3 style="font-size: 1.2rem; color: var(--color-navy); margin: 0 0 1.25rem 0; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
        1. Brand Logo &amp; Display Controls
      </h3>

      <div style="display: grid; grid-template-columns: 240px 1fr; gap: 2rem; align-items: center; margin-bottom: 1.5rem;">
        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; padding: 1.5rem; border-radius: var(--radius-sm); text-align: center;">
          <p style="font-size: 0.75rem; color: var(--color-muted); margin: 0 0 0.75rem 0; font-weight: 600;">Current Logo Preview</p>
          <img src="<?php echo h($hdr_cms['logo_url']); ?>" alt="Logo Preview" style="max-height: 56px; max-width: 100%; object-fit: contain;">
        </div>

        <div>
          <div class="admin-form-group">
            <label class="admin-label">Logo Path / URL</label>
            <input type="text" name="logo_url" value="<?php echo h($hdr_cms['logo_url']); ?>" class="admin-input" placeholder="/assets/images/logo.png">
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Upload New Logo (Replaces Current)</label>
            <input type="file" name="logo_file" accept="image/*" class="admin-input">
          </div>

          <div style="display: flex; gap: 2rem; margin-top: 1rem;">
            <label style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
              <input type="checkbox" name="is_header_visible" value="1" <?php echo !empty($hdr_cms['is_header_visible']) ? 'checked' : ''; ?>>
              <span>Main Header Visible</span>
            </label>
            <label style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
              <input type="checkbox" name="is_topbar_visible" value="1" <?php echo !empty($hdr_cms['is_topbar_visible']) ? 'checked' : ''; ?>>
              <span>Top Utility Bar Visible</span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Header CTA Action Buttons -->
    <div class="card" style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); border-radius: var(--radius-md); padding: 2rem; margin-bottom: 2rem;">
      <h3 style="font-size: 1.2rem; color: var(--color-navy); margin: 0 0 1.25rem 0; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
        2. Header Call-to-Action Buttons
      </h3>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div>
          <h4 style="font-size: 0.95rem; color: var(--color-navy); margin: 0 0 0.75rem 0;">Primary Action Button (Outline)</h4>
          <div class="admin-form-group">
            <label class="admin-label">Button Label</label>
            <input type="text" name="primary_cta_text" value="<?php echo h($hdr_cms['primary_cta_text']); ?>" class="admin-input" placeholder="e.g. Enquire Now">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Button Link URL</label>
            <input type="text" name="primary_cta_url" value="<?php echo h($hdr_cms['primary_cta_url']); ?>" class="admin-input" placeholder="e.g. /contact">
          </div>
        </div>

        <div>
          <h4 style="font-size: 0.95rem; color: var(--color-navy); margin: 0 0 0.75rem 0;">Secondary Action Button (Solid)</h4>
          <div class="admin-form-group">
            <label class="admin-label">Button Label</label>
            <input type="text" name="secondary_cta_text" value="<?php echo h($hdr_cms['secondary_cta_text']); ?>" class="admin-input" placeholder="e.g. Book a Demo">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Button Action / URL</label>
            <input type="text" name="secondary_cta_action" value="<?php echo h($hdr_cms['secondary_cta_action']); ?>" class="admin-input" placeholder="e.g. javascript:openCallbackModal()">
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Top Utility Bar Information & Channels -->
    <div class="card" style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); border-radius: var(--radius-md); padding: 2rem; margin-bottom: 2.5rem;">
      <h3 style="font-size: 1.2rem; color: var(--color-navy); margin: 0 0 1.25rem 0; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
        3. Top Utility Bar Channels &amp; Affiliation
      </h3>

      <div class="admin-form-group">
        <label class="admin-label">Top Bar Left: Official Affiliation Text</label>
        <input type="text" name="affiliation_text" value="<?php echo h($hdr_cms['affiliation_text']); ?>" class="admin-input" placeholder="e.g. Affiliation No: IA 4883 • IAO Accredited • ISSO Member">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1rem;">
        <div class="admin-form-group">
          <label class="admin-label">Header Phone Number</label>
          <input type="text" name="phone_number" value="<?php echo h($hdr_cms['phone_number']); ?>" class="admin-input" placeholder="7827262956">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Header Email Address</label>
          <input type="email" name="email_address" value="<?php echo h($hdr_cms['email_address']); ?>" class="admin-input" placeholder="info@zuvioglobalschool.com">
        </div>
      </div>

      <h4 style="font-size: 0.95rem; color: var(--color-navy); margin: 1.25rem 0 0.75rem 0;">Social Channel Links</h4>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
          <label class="admin-label">Facebook URL</label>
          <input type="url" name="social_facebook" value="<?php echo h($hdr_cms['social_facebook']); ?>" class="admin-input">
        </div>
        <div>
          <label class="admin-label">Instagram URL</label>
          <input type="url" name="social_instagram" value="<?php echo h($hdr_cms['social_instagram']); ?>" class="admin-input">
        </div>
        <div>
          <label class="admin-label">LinkedIn URL</label>
          <input type="url" name="social_linkedin" value="<?php echo h($hdr_cms['social_linkedin']); ?>" class="admin-input">
        </div>
        <div>
          <label class="admin-label">YouTube URL</label>
          <input type="url" name="social_youtube" value="<?php echo h($hdr_cms['social_youtube']); ?>" class="admin-input">
        </div>
      </div>
    </div>

    <!-- Individual Explicit Save Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); padding: 1.25rem 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); position: sticky; bottom: 1rem; z-index: 50;">
      <div>
        <p style="margin: 0; font-size: 0.85rem; color: var(--color-muted);">
          Changes are purely local until you click <strong>Save Header Changes</strong>.
        </p>
      </div>
      <div>
        <button type="submit" class="btn btn-primary" style="background: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.75rem 2.5rem; font-size: 0.95rem;">
          💾 Save Header Changes
        </button>
      </div>
    </div>
  </form>
</div>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
