<?php
// Zuvio Global School - Admin Site Settings Editor
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('settings.view');

$msg = '';
$error = '';

// Handle Settings Update Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    require_permission('settings.edit');
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        try {
            $keys_to_update = [
                'site_name', 'site_tagline',
                'logo_url', 'favicon_url',
                'phone', 'general_email', 'admissions_email', 'office_timings',
                'address', 'google_maps_link',
                'google_analytics_id', 'google_analytics_enabled',
                'facebook_pixel_id', 'facebook_pixel_enabled',
                'whatsapp', 'whatsapp_enabled', 'whatsapp_message',
                'maintenance_mode', 'maintenance_title', 'maintenance_message',
                'copyright', 'social_instagram', 'social_facebook', 'social_linkedin', 'social_youtube'
            ];

            // Normalize checkbox / boolean toggles
            $_POST['google_analytics_enabled'] = isset($_POST['google_analytics_enabled']) ? '1' : '0';
            $_POST['facebook_pixel_enabled'] = isset($_POST['facebook_pixel_enabled']) ? '1' : '0';
            $_POST['whatsapp_enabled'] = isset($_POST['whatsapp_enabled']) ? '1' : '0';
            $_POST['maintenance_mode'] = isset($_POST['maintenance_mode']) ? '1' : '0';

            // Handle Logo File Upload
            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
                $f = $_FILES['logo_file'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                    $up_name = 'logo_' . time() . '.' . $ext;
                    $up_dir = dirname(__FILE__) . '/../uploads/';
                    if (!is_dir($up_dir)) mkdir($up_dir, 0755, true);
                    if (move_uploaded_file($f['tmp_name'], $up_dir . $up_name)) {
                        $_POST['logo_url'] = '/uploads/' . $up_name;
                    }
                }
            }

            // Handle Favicon File Upload
            if (isset($_FILES['favicon_file']) && $_FILES['favicon_file']['error'] === UPLOAD_ERR_OK) {
                $f = $_FILES['favicon_file'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['ico', 'png', 'svg', 'webp', 'jpg'])) {
                    $up_name = 'favicon_' . time() . '.' . $ext;
                    $up_dir = dirname(__FILE__) . '/../uploads/';
                    if (!is_dir($up_dir)) mkdir($up_dir, 0755, true);
                    if (move_uploaded_file($f['tmp_name'], $up_dir . $up_name)) {
                        $_POST['favicon_url'] = '/uploads/' . $up_name;
                    }
                }
            }

            if ($db) {
                $db->beginTransaction();
                foreach ($keys_to_update as $key) {
                    if (isset($_POST[$key])) {
                        $val = trim($_POST[$key]);
                        set_setting($key, $val);
                    }
                }
                $db->commit();
            } else {
                // Fallback to session
                foreach ($keys_to_update as $key) {
                    if (isset($_POST[$key])) {
                        set_setting($key, trim($_POST[$key]));
                    }
                }
            }

            // Also keep cms_header_settings in sync if applicable
            $hdr_cfg = get_json_setting('cms_header_settings', []);
            if (!empty($_POST['logo_url'])) $hdr_cfg['logo_url'] = $_POST['logo_url'];
            if (!empty($_POST['phone'])) $hdr_cfg['phone_number'] = $_POST['phone'];
            if (!empty($_POST['general_email'])) $hdr_cfg['email_address'] = $_POST['general_email'];
            if (isset($_POST['social_facebook'])) $hdr_cfg['social_facebook'] = $_POST['social_facebook'];
            if (isset($_POST['social_instagram'])) $hdr_cfg['social_instagram'] = $_POST['social_instagram'];
            if (isset($_POST['social_linkedin'])) $hdr_cfg['social_linkedin'] = $_POST['social_linkedin'];
            if (isset($_POST['social_youtube'])) $hdr_cfg['social_youtube'] = $_POST['social_youtube'];
            set_json_setting('cms_header_settings', $hdr_cfg, 'Master Header Settings');

            log_audit('SETTINGS_UPDATED', 'settings', 'site_settings', 1, null, null, 'Updated global site controls & configurations');
            header('Location: /admin/settings.php?msg=updated');
            exit;
        } catch (Exception $e) {
            if ($db && $db->inTransaction()) {
                $db->rollBack();
            }
            $error = 'Failed to update settings: ' . $e->getMessage();
        }
    }
}

// Load current site settings with robust fallbacks
$defaults = [
    'site_name' => 'Zuvio Global School',
    'site_tagline' => 'Learning Beyond Boundaries',
    'logo_url' => '/assets/images/logo.png',
    'favicon_url' => '/assets/images/logo.png',
    'phone' => '7827262956',
    'general_email' => 'info@zuvioglobalschool.com',
    'admissions_email' => 'info@zuvioglobalschool.com',
    'office_timings' => 'Monday - Saturday: 10:00 AM - 7:00 PM IST',
    'address' => "B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi - 110034",
    'google_maps_link' => 'https://maps.google.com/?q=ITL+Twin+Tower+Netaji+Subhash+Place+Delhi',
    'google_analytics_id' => '',
    'google_analytics_enabled' => '0',
    'facebook_pixel_id' => '',
    'facebook_pixel_enabled' => '0',
    'whatsapp' => '7827262956',
    'whatsapp_enabled' => '1',
    'whatsapp_message' => 'Hello Zuvio Global School, I would like to enquire about admissions.',
    'maintenance_mode' => '0',
    'maintenance_title' => 'We’re Upgrading Our Learning Experience',
    'maintenance_message' => 'Zuvio Global School website is currently undergoing scheduled platform upgrades. We will be back online shortly. For admissions assistance or immediate inquiries, our counseling desk is available via phone and WhatsApp.',
    'copyright' => '© 2026 Zuvio Global School. All rights reserved.',
    'social_instagram' => 'https://www.instagram.com/thezuvio/',
    'social_facebook' => 'https://www.facebook.com/share/1XsYWDm3rt/',
    'social_linkedin' => 'https://www.linkedin.com/company/zuvio-global-school/',
    'social_youtube' => 'https://www.youtube.com/@zuvioglobalschool'
];

$settings = $defaults;
if ($db) {
    try {
        $rows = $db->query("SELECT `setting_key`, `setting_value` FROM `site_settings`")->fetchAll();
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {
        $error = 'Failed to load settings from database.';
    }
}
// Merge session mock if available
if (!empty($_SESSION['mock_settings'])) {
    foreach ($_SESSION['mock_settings'] as $k => $v) {
        $settings[$k] = $v;
    }
}

$is_maint_active = ((string)($settings['maintenance_mode'] ?? '0') === '1');

$page_slug = 'admin-settings';
include_once dirname(__FILE__) . '/header.php';
?>

<style>
  .settings-hero {
    margin-bottom: 2rem;
  }
  .settings-category-badge {
    font-size: 0.8rem;
    font-weight: 700;
    color: #D9A441;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 0.35rem;
  }
  .settings-title {
    font-family: var(--font-secondary, 'Playfair Display', serif);
    font-size: 2.25rem;
    font-weight: 700;
    color: #062B63;
    line-height: 1.2;
    margin: 0;
  }
  .settings-subtitle {
    color: var(--color-muted, #64748B);
    font-size: 0.95rem;
    margin-top: 0.5rem;
  }

  /* 8 Overview Cards Grid */
  .overview-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.75rem;
  }
  @media (max-width: 1200px) {
    .overview-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 640px) {
    .overview-grid { grid-template-columns: 1fr; }
  }

  .overview-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 1.35rem 1.4rem;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }
  .overview-card:hover {
    border-color: #062B63;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(6, 43, 99, 0.08);
  }
  .overview-card.active-tab {
    border-color: #062B63;
    background: #F8FAFC;
    box-shadow: 0 4px 15px rgba(6, 43, 99, 0.06);
  }
  .overview-card.card-maintenance {
    border: 1.5px solid #E2E8F0;
  }
  .overview-card.card-maintenance.is-on,
  .overview-card.card-maintenance:hover {
    border-color: #D97706;
  }
  .overview-card-title {
    font-size: 1.08rem;
    font-weight: 700;
    color: #062B63;
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .overview-card.card-maintenance .overview-card-title {
    color: #B45309;
  }
  .overview-card-desc {
    font-size: 0.86rem;
    color: #64748B;
    line-height: 1.45;
  }
  .card-status-pill {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.15rem 0.55rem;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }
  .status-on { background: #DCFCE7; color: #166534; }
  .status-warn { background: #FEF3C7; color: #92400E; }
  .status-off { background: #F1F5F9; color: #64748B; }

  /* Maintenance Callout Banner */
  .maint-warning-banner {
    background: #FFF7ED;
    border-left: 5px solid #D97706;
    padding: 1.25rem 1.6rem;
    border-radius: 10px;
    display: flex;
    align-items: flex-start;
    gap: 1.15rem;
    margin-bottom: 2.25rem;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.06);
  }
  .maint-warning-icon {
    font-size: 1.6rem;
    color: #D97706;
    line-height: 1;
    margin-top: 0.1rem;
  }
  .maint-warning-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #78350F;
    margin-bottom: 0.2rem;
  }
  .maint-warning-sub {
    font-size: 0.88rem;
    color: #9A3412;
    line-height: 1.45;
  }

  /* Form Sections Cards */
  .settings-panel-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 2.25rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
  }
  .panel-header {
    border-bottom: 1px solid #E2E8F0;
    padding-bottom: 1.25rem;
    margin-bottom: 1.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .panel-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #062B63;
    font-family: var(--font-secondary, 'Playfair Display', serif);
    margin: 0;
  }
  .panel-desc {
    font-size: 0.85rem;
    color: #64748B;
    margin-top: 0.25rem;
  }

  .toggle-switch-wrap {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    user-select: none;
  }
  .toggle-switch-wrap input {
    display: none;
  }
  .toggle-slider {
    width: 48px;
    height: 26px;
    background: #CBD5E1;
    border-radius: 999px;
    position: relative;
    transition: background 0.25s ease;
  }
  .toggle-slider::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #FFFFFF;
    top: 3px;
    left: 3px;
    transition: transform 0.25s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }
  .toggle-switch-wrap input:checked + .toggle-slider {
    background: #16A34A;
  }
  .toggle-switch-wrap.danger-toggle input:checked + .toggle-slider {
    background: #DC2626;
  }
  .toggle-switch-wrap input:checked + .toggle-slider::after {
    transform: translateX(22px);
  }
  .toggle-label-text {
    font-weight: 600;
    font-size: 0.92rem;
    color: #1E293B;
  }

  .preview-box {
    background: #F8FAFC;
    border: 1px dashed #CBD5E1;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-top: 0.75rem;
  }
  .preview-thumb {
    max-height: 52px;
    max-width: 120px;
    object-fit: contain;
    background: #FFFFFF;
    padding: 4px;
    border-radius: 6px;
    border: 1px solid #E2E8F0;
  }

  .floating-save-bar {
    position: sticky;
    bottom: 1.5rem;
    z-index: 100;
    background: rgba(6, 43, 99, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 1.1rem 2rem;
    box-shadow: 0 15px 35px rgba(6, 43, 99, 0.35);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #FFFFFF;
    margin-top: 2rem;
  }
  .floating-save-bar .info-text {
    font-size: 0.9rem;
    color: #E2E8F0;
  }
  .floating-save-bar .btn-gold {
    background: #D9A441;
    color: #FFFFFF;
    font-weight: 700;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(217, 164, 65, 0.4);
    transition: all 0.2s;
  }
  .floating-save-bar .btn-gold:hover {
    background: #C49033;
    transform: translateY(-1px);
  }
</style>

<div class="settings-hero">
  <span class="settings-category-badge">SITE SETTINGS</span>
  <h1 class="settings-title">Global controls, set once</h1>
  <p class="settings-subtitle">Configure foundational institutional metadata, tracking tags, messaging anchors, and platform state.</p>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: #DCFCE7; border-left: 4px solid #16A34A; padding: 1rem 1.25rem; border-radius: 8px; color: #166534; font-size: 0.9rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    <span><strong>Settings Saved!</strong> Global controls updated and immediately synchronized across production.</span>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background-color: #FEE2E2; border-left: 4px solid #DC2626; padding: 1rem 1.25rem; border-radius: 8px; color: #991B1B; font-size: 0.9rem; margin-bottom: 2rem;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- 8 Overview Navigation Cards matching exact user design -->
<div class="overview-grid">
  <!-- 1. Site Name -->
  <a href="#sec-sitename" class="overview-card" onclick="highlightSection('sec-sitename')">
    <div class="overview-card-title">
      <span>Site Name</span>
      <span class="card-status-pill status-on">Core</span>
    </div>
    <div class="overview-card-desc">Shown in browser tabs and SEO titles</div>
  </a>

  <!-- 2. Logo & Favicon -->
  <a href="#sec-branding" class="overview-card" onclick="highlightSection('sec-branding')">
    <div class="overview-card-title">
      <span>Logo & Favicon</span>
      <span class="card-status-pill status-on">Branding</span>
    </div>
    <div class="overview-card-desc">Header, footer and browser-tab icon</div>
  </a>

  <!-- 3. Phone & Email -->
  <a href="#sec-contact" class="overview-card" onclick="highlightSection('sec-contact')">
    <div class="overview-card-title">
      <span>Phone & Email</span>
      <span class="card-status-pill status-on">Contact</span>
    </div>
    <div class="overview-card-desc">Main contact details site-wide</div>
  </a>

  <!-- 4. School Address -->
  <a href="#sec-address" class="overview-card" onclick="highlightSection('sec-address')">
    <div class="overview-card-title">
      <span>School Address</span>
      <span class="card-status-pill status-on">Location</span>
    </div>
    <div class="overview-card-desc">Full registered address</div>
  </a>

  <!-- 5. Google Analytics -->
  <a href="#sec-analytics" class="overview-card" onclick="highlightSection('sec-analytics')">
    <div class="overview-card-title">
      <span>Google Analytics</span>
      <span class="card-status-pill <?php echo !empty($settings['google_analytics_id']) && $settings['google_analytics_enabled'] === '1' ? 'status-on' : 'status-off'; ?>">
        <?php echo !empty($settings['google_analytics_id']) && $settings['google_analytics_enabled'] === '1' ? 'Active' : 'Disabled'; ?>
      </span>
    </div>
    <div class="overview-card-desc">Paste the GA4 ID to start tracking</div>
  </a>

  <!-- 6. Facebook Pixel -->
  <a href="#sec-pixel" class="overview-card" onclick="highlightSection('sec-pixel')">
    <div class="overview-card-title">
      <span>Facebook Pixel</span>
      <span class="card-status-pill <?php echo !empty($settings['facebook_pixel_id']) && $settings['facebook_pixel_enabled'] === '1' ? 'status-on' : 'status-off'; ?>">
        <?php echo !empty($settings['facebook_pixel_id']) && $settings['facebook_pixel_enabled'] === '1' ? 'Active' : 'Disabled'; ?>
      </span>
    </div>
    <div class="overview-card-desc">Ad conversions on Facebook and Instagram</div>
  </a>

  <!-- 7. WhatsApp Button -->
  <a href="#sec-whatsapp" class="overview-card" onclick="highlightSection('sec-whatsapp')">
    <div class="overview-card-title">
      <span>WhatsApp Button</span>
      <span class="card-status-pill <?php echo $settings['whatsapp_enabled'] === '1' ? 'status-on' : 'status-off'; ?>">
        <?php echo $settings['whatsapp_enabled'] === '1' ? 'Active' : 'Disabled'; ?>
      </span>
    </div>
    <div class="overview-card-desc">Number behind the floating chat button</div>
  </a>

  <!-- 8. Maintenance Mode -->
  <a href="#sec-maintenance" class="overview-card card-maintenance <?php echo $is_maint_active ? 'is-on' : ''; ?>" onclick="highlightSection('sec-maintenance')">
    <div class="overview-card-title">
      <span>Maintenance Mode</span>
      <span class="card-status-pill <?php echo $is_maint_active ? 'status-warn' : 'status-off'; ?>">
        <?php echo $is_maint_active ? 'ENABLED' : 'OFF'; ?>
      </span>
    </div>
    <div class="overview-card-desc">Shows a Coming Soon page instead</div>
  </a>
</div>

<!-- Maintenance Mode Alert Callout -->
<div class="maint-warning-banner">
  <div class="maint-warning-icon">⚠️</div>
  <div>
    <div class="maint-warning-title">Maintenance Mode hides the whole site from every visitor</div>
    <div class="maint-warning-sub">Switch it on only for planned work, and off again straight after. Logged-in administrators will always retain full preview access.</div>
  </div>
</div>

<form method="POST" action="/admin/settings.php" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

  <!-- 1. SITE NAME & IDENTITY -->
  <div class="settings-panel-card" id="sec-sitename">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">1. Site Name & Institutional Identity</h3>
        <p class="panel-desc">Appears in browser tabs, search engine snippets, bookmark bars, and default page titles.</p>
      </div>
      <span class="card-status-pill status-on">Global SEO</span>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Official Site / School Name *</label>
        <input type="text" name="site_name" class="admin-input" value="<?php echo h($settings['site_name'] ?? ''); ?>" required placeholder="e.g. Zuvio Global School">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">Used as prefix/suffix across all page meta titles.</small>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Institutional Tagline / Motto</label>
        <input type="text" name="site_tagline" class="admin-input" value="<?php echo h($settings['site_tagline'] ?? ''); ?>" placeholder="e.g. Learning Beyond Boundaries">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">Displayed in hero sections and meta descriptions.</small>
      </div>
    </div>
  </div>

  <!-- 2. LOGO & FAVICON -->
  <div class="settings-panel-card" id="sec-branding">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">2. Logo & Favicon Assets</h3>
        <p class="panel-desc">Header logo, footer crest, and 32x32 browser tab shortcut icon.</p>
      </div>
      <span class="card-status-pill status-on">Branding</span>
    </div>

    <div class="grid-2">
      <!-- Main Logo -->
      <div class="admin-form-group">
        <label class="admin-label">Primary Site Logo</label>
        <input type="text" name="logo_url" id="logo_url_input" class="admin-input" value="<?php echo h($settings['logo_url'] ?? ''); ?>" placeholder="/assets/images/logo.png">
        <div style="margin-top: 0.65rem;">
          <input type="file" name="logo_file" accept=".png,.jpg,.jpeg,.webp,.svg" class="admin-input" style="padding: 0.4rem;">
        </div>
        <div class="preview-box">
          <img src="<?php echo h($settings['logo_url'] ?? '/assets/images/logo.png'); ?>" alt="Current Logo" class="preview-thumb" id="logo_preview">
          <div>
            <div style="font-weight: 600; font-size: 0.85rem; color: #062B63;">Active Logo Preview</div>
            <div style="font-size: 0.78rem; color: #64748B;">Supported: PNG, SVG, WEBP, JPG</div>
          </div>
        </div>
      </div>

      <!-- Favicon -->
      <div class="admin-form-group">
        <label class="admin-label">Browser Favicon (.ico / .png / .svg)</label>
        <input type="text" name="favicon_url" id="favicon_url_input" class="admin-input" value="<?php echo h($settings['favicon_url'] ?? ''); ?>" placeholder="/assets/images/logo.png">
        <div style="margin-top: 0.65rem;">
          <input type="file" name="favicon_file" accept=".ico,.png,.svg,.webp,.jpg" class="admin-input" style="padding: 0.4rem;">
        </div>
        <div class="preview-box">
          <img src="<?php echo h($settings['favicon_url'] ?? '/assets/images/logo.png'); ?>" alt="Current Favicon" class="preview-thumb" style="width: 32px; height: 32px;" id="favicon_preview">
          <div>
            <div style="font-weight: 600; font-size: 0.85rem; color: #062B63;">Active Tab Icon Preview</div>
            <div style="font-size: 0.78rem; color: #64748B;">Displays on Chrome, Safari, Firefox browser tabs</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. PHONE & EMAIL CONTACTS -->
  <div class="settings-panel-card" id="sec-contact">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">3. Institutional Phone & Email Directory</h3>
        <p class="panel-desc">Main contact telephone lines and inquiry mailboxes displayed across header, topbar, and footer.</p>
      </div>
      <span class="card-status-pill status-on">Public Contact</span>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Admissions Hotline / Phone Number *</label>
        <input type="text" name="phone" class="admin-input" value="<?php echo h($settings['phone'] ?? ''); ?>" required placeholder="+91 7827262956">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">General Contact Email *</label>
        <input type="email" name="general_email" class="admin-input" value="<?php echo h($settings['general_email'] ?? ''); ?>" required placeholder="info@zuvioglobalschool.com">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Dedicated Admissions Email</label>
        <input type="email" name="admissions_email" class="admin-input" value="<?php echo h($settings['admissions_email'] ?? ''); ?>" placeholder="admissions@zuvioglobalschool.com">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Counseling Desk Hours / Timings</label>
        <input type="text" name="office_timings" class="admin-input" value="<?php echo h($settings['office_timings'] ?? ''); ?>" placeholder="Monday - Saturday: 10:00 AM - 7:00 PM IST">
      </div>
    </div>
  </div>

  <!-- 4. SCHOOL REGISTERED ADDRESS -->
  <div class="settings-panel-card" id="sec-address">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">4. School Campus & Registered Office Address</h3>
        <p class="panel-desc">Official registered premises address shown in footer, contact page, and trust compliance notices.</p>
      </div>
      <span class="card-status-pill status-on">Location</span>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Full Registered Street Address</label>
        <textarea name="address" class="admin-input" rows="4" placeholder="Building, Suite, Street, City, State, PIN"><?php echo h($settings['address'] ?? ''); ?></textarea>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Google Maps Direct Link / Embed URL</label>
        <input type="text" name="google_maps_link" class="admin-input" value="<?php echo h($settings['google_maps_link'] ?? ''); ?>" placeholder="https://maps.google.com/...">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.5rem;">
          Provides direct GPS directions on mobile devices when parents tap the address pin.
        </small>
      </div>
    </div>
  </div>

  <!-- 5. GOOGLE ANALYTICS (GA4) -->
  <div class="settings-panel-card" id="sec-analytics">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">5. Google Analytics (GA4) Integration</h3>
        <p class="panel-desc">Track real-time visitors, user retention, pageviews, and geographic admissions interest.</p>
      </div>
      <label class="toggle-switch-wrap">
        <input type="checkbox" name="google_analytics_enabled" value="1" <?php echo ($settings['google_analytics_enabled'] ?? '0') === '1' ? 'checked' : ''; ?>>
        <span class="toggle-slider"></span>
        <span class="toggle-label-text">Enable GA4 Script</span>
      </label>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Google Analytics Measurement ID (GA4)</label>
        <input type="text" name="google_analytics_id" class="admin-input" value="<?php echo h($settings['google_analytics_id'] ?? ''); ?>" placeholder="G-XXXXXXXXXX" style="font-family: monospace; font-size: 1rem; letter-spacing: 0.05em;">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.4rem;">
          Find this under <em>Google Analytics Admin &rarr; Data Streams &rarr; Measurement ID</em>.
        </small>
      </div>
      <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem 1.25rem; border-radius: 10px; font-size: 0.84rem; color: #475569; line-height: 1.5;">
        <strong style="color: #062B63; display: block; margin-bottom: 0.35rem;">How it works:</strong>
        When enabled, the Google tag (gtag.js) script is asynchronously injected into the public website header without slowing down page load speed.
      </div>
    </div>
  </div>

  <!-- 6. FACEBOOK / META PIXEL -->
  <div class="settings-panel-card" id="sec-pixel">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">6. Facebook Pixel (Meta Pixel) Integration</h3>
        <p class="panel-desc">Attribute ad conversions and run retargeting campaigns on Facebook and Instagram.</p>
      </div>
      <label class="toggle-switch-wrap">
        <input type="checkbox" name="facebook_pixel_enabled" value="1" <?php echo ($settings['facebook_pixel_enabled'] ?? '0') === '1' ? 'checked' : ''; ?>>
        <span class="toggle-slider"></span>
        <span class="toggle-label-text">Enable Meta Pixel</span>
      </label>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Meta / Facebook Pixel ID</label>
        <input type="text" name="facebook_pixel_id" class="admin-input" value="<?php echo h($settings['facebook_pixel_id'] ?? ''); ?>" placeholder="e.g. 123456789012345" style="font-family: monospace; font-size: 1rem; letter-spacing: 0.05em;">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.4rem;">
          Obtain this from <em>Meta Events Manager &rarr; Data Sources &rarr; Dataset / Pixel ID</em>.
        </small>
      </div>
      <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem 1.25rem; border-radius: 10px; font-size: 0.84rem; color: #475569; line-height: 1.5;">
        <strong style="color: #062B63; display: block; margin-bottom: 0.35rem;">Conversion Tracking:</strong>
        Automatically triggers the standard <code>PageView</code> event across all pages and admissions funnel touchpoints.
      </div>
    </div>
  </div>

  <!-- 7. WHATSAPP BUTTON -->
  <div class="settings-panel-card" id="sec-whatsapp">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">7. Floating WhatsApp Chat Widget</h3>
        <p class="panel-desc">Direct 1-on-1 instant counselor chat button anchored to the bottom-right corner of all public pages.</p>
      </div>
      <label class="toggle-switch-wrap">
        <input type="checkbox" name="whatsapp_enabled" value="1" <?php echo ($settings['whatsapp_enabled'] ?? '1') === '1' ? 'checked' : ''; ?>>
        <span class="toggle-slider"></span>
        <span class="toggle-label-text">Show Floating WhatsApp Button</span>
      </label>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">WhatsApp Number (with or without 91 prefix) *</label>
        <input type="text" name="whatsapp" class="admin-input" value="<?php echo h($settings['whatsapp'] ?? '7827262956'); ?>" required placeholder="7827262956">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.4rem;">
          Example: <code>7827262956</code> or <code>+917827262956</code>.
        </small>
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Pre-filled Message for Parents</label>
        <input type="text" name="whatsapp_message" class="admin-input" value="<?php echo h($settings['whatsapp_message'] ?? ''); ?>" placeholder="Hello Zuvio Global School, I would like to enquire about admissions.">
        <small style="color: var(--color-muted); font-size: 0.8rem; display: block; margin-top: 0.4rem;">
          Automatically populates in WhatsApp chat when clicked.
        </small>
      </div>
    </div>
  </div>

  <!-- 8. MAINTENANCE MODE -->
  <div class="settings-panel-card" id="sec-maintenance" style="<?php echo $is_maint_active ? 'border: 2px solid #D97706; background: #FFFCF7;' : ''; ?>">
    <div class="panel-header">
      <div>
        <h3 class="panel-title" style="<?php echo $is_maint_active ? 'color: #B45309;' : ''; ?>">
          8. Maintenance Mode & Coming Soon Page
        </h3>
        <p class="panel-desc">Replaces public front-end pages with a branded maintenance and admissions inquiry notice.</p>
      </div>
      <label class="toggle-switch-wrap danger-toggle">
        <input type="checkbox" name="maintenance_mode" value="1" <?php echo $is_maint_active ? 'checked' : ''; ?> onchange="toggleMaintWarning(this)">
        <span class="toggle-slider"></span>
        <span class="toggle-label-text" style="color: #B45309;">Turn On Maintenance Mode</span>
      </label>
    </div>

    <div style="background: #FFF7ED; border-left: 4px solid #D97706; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.85rem; color: #9A3412;">
      <strong>⚠️ Warning:</strong> When activated, public visitors cannot view any website pages. Logged-in admin team members can continue to browse normally with a top status indicator.
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Maintenance Notice Title</label>
      <input type="text" name="maintenance_title" class="admin-input" value="<?php echo h($settings['maintenance_title'] ?? ''); ?>" placeholder="We’re Upgrading Our Learning Experience">
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Maintenance Message & Parent Instructions</label>
      <textarea name="maintenance_message" class="admin-input" rows="3" placeholder="Explain the maintenance reason and provide alternative counseling contacts..."><?php echo h($settings['maintenance_message'] ?? ''); ?></textarea>
    </div>
  </div>

  <!-- 9. FOOTER & SOCIAL LINKS (Preserved) -->
  <div class="settings-panel-card" id="sec-social">
    <div class="panel-header">
      <div>
        <h3 class="panel-title">Footer Disclaimer & Official Social Handles</h3>
        <p class="panel-desc">Legal copyright notice and verified social media channels.</p>
      </div>
      <span class="card-status-pill status-on">Footer</span>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Footer Copyright Disclaimer</label>
      <input type="text" name="copyright" class="admin-input" value="<?php echo h($settings['copyright'] ?? ''); ?>">
    </div>

    <div class="grid-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-top: 1rem;">
      <div class="admin-form-group">
        <label class="admin-label">Instagram Profile URL</label>
        <input type="text" name="social_instagram" class="admin-input" value="<?php echo h($settings['social_instagram'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Facebook Page URL</label>
        <input type="text" name="social_facebook" class="admin-input" value="<?php echo h($settings['social_facebook'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">LinkedIn Organization URL</label>
        <input type="text" name="social_linkedin" class="admin-input" value="<?php echo h($settings['social_linkedin'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">YouTube Channel URL</label>
        <input type="text" name="social_youtube" class="admin-input" value="<?php echo h($settings['social_youtube'] ?? ''); ?>">
      </div>
    </div>
  </div>

  <!-- Sticky Save Action Bar -->
  <div class="floating-save-bar">
    <div>
      <div style="font-weight: 700; font-size: 1.05rem;">Ready to publish changes?</div>
      <div class="info-text">All 8 global settings will be applied immediately across desktop & mobile.</div>
    </div>
    <div style="display: flex; gap: 1rem; align-items: center;">
      <a href="/admin/settings.php" style="color: #94A3B8; text-decoration: none; font-size: 0.9rem;">Reset Form</a>
      <button type="submit" name="save_settings" class="btn-gold">Save Site Settings</button>
    </div>
  </div>

</form>

<script>
  function highlightSection(id) {
    const el = document.getElementById(id);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      el.style.transition = 'box-shadow 0.3s ease, border-color 0.3s ease';
      el.style.borderColor = '#062B63';
      el.style.boxShadow = '0 0 0 4px rgba(6, 43, 99, 0.15)';
      setTimeout(() => {
        el.style.boxShadow = '';
      }, 1500);
    }
  }

  function toggleMaintWarning(checkbox) {
    const panel = document.getElementById('sec-maintenance');
    if (checkbox.checked) {
      if (confirm('Are you sure you want to turn ON Maintenance Mode?\n\nThis will hide the website from public visitors and show the Coming Soon screen.')) {
        panel.style.border = '2px solid #D97706';
        panel.style.background = '#FFFCF7';
      } else {
        checkbox.checked = false;
      }
    } else {
      panel.style.border = '';
      panel.style.background = '';
    }
  }
</script>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
