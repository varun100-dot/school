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
            if (!$db) throw new Exception("Database connection offline.");
            
            $keys_to_update = [
                'phone', 'general_email', 'address', 'office_timings', 
                'logo_url', 'copyright', 'social_instagram', 'social_facebook', 'social_linkedin'
            ];
            
            $db->beginTransaction();
            
            $stmt = $db->prepare("UPDATE `site_settings` SET `setting_value` = ? WHERE `setting_key` = ?");
            foreach ($keys_to_update as $key) {
                if (isset($_POST[$key])) {
                    $val = trim($_POST[$key]);
                    $stmt->execute([$val, $key]);
                }
            }
            
            $db->commit();
            log_audit('SETTINGS_UPDATED', 'settings', 'site_settings', 1, null, null, 'Updated global site settings');
            header('Location: /admin/settings?msg=updated');
            exit;
        } catch (Exception $e) {
            if ($db && $db->inTransaction()) {
                $db->rollBack();
            }
            $error = 'Failed to update settings: ' . $e->getMessage();
        }
    }
}

// Load current site settings
$settings = [];
if ($db) {
    try {
        $rows = $db->query("SELECT * FROM `site_settings`")->fetchAll();
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {
        $error = 'Failed to load settings from database.';
    }
}
$page_slug = 'admin-settings';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="margin-bottom: 2rem;">
  <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">General Site Settings</h1>
  <p style="color: var(--color-muted); font-size: 0.85rem;">Manage institution contact information, logo paths, office hours, and social media anchors.</p>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Site settings updated successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<div class="card" style="border-left: none; padding: 2.5rem;">
  <form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
    
    <div style="border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-bottom: 1.5rem;">
      <h3 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-secondary);">Institutional Contacts</h3>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Office Phone Number</label>
        <input type="text" name="phone" class="admin-input" value="<?php echo h($settings['phone'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">General Office Email</label>
        <input type="email" name="general_email" class="admin-input" value="<?php echo h($settings['general_email'] ?? ''); ?>">
      </div>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Office Physical Address</label>
      <textarea name="address" class="admin-input" rows="3"><?php echo h($settings['address'] ?? ''); ?></textarea>
    </div>

    <div class="grid-2">
      <div class="admin-form-group">
        <label class="admin-label">Office Hours / Timings Description</label>
        <input type="text" name="office_timings" class="admin-input" value="<?php echo h($settings['office_timings'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Global Logo Path / URL</label>
        <input type="text" name="logo_url" class="admin-input" value="<?php echo h($settings['logo_url'] ?? ''); ?>">
      </div>
    </div>

    <div style="border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-top: 2rem; margin-bottom: 1.5rem;">
      <h3 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-secondary);">Footer & Social Media Handles</h3>
    </div>

    <div class="admin-form-group">
      <label class="admin-label">Footer Copyright Notice</label>
      <input type="text" name="copyright" class="admin-input" value="<?php echo h($settings['copyright'] ?? ''); ?>">
    </div>

    <div class="grid-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
      <div class="admin-form-group">
        <label class="admin-label">Instagram Link URL</label>
        <input type="text" name="social_instagram" class="admin-input" value="<?php echo h($settings['social_instagram'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Facebook Link URL</label>
        <input type="text" name="social_facebook" class="admin-input" value="<?php echo h($settings['social_facebook'] ?? ''); ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">LinkedIn Link URL</label>
        <input type="text" name="social_linkedin" class="admin-input" value="<?php echo h($settings['social_linkedin'] ?? ''); ?>">
      </div>
    </div>

    <div style="margin-top: 2rem;">
      <button type="submit" name="save_settings" class="btn btn-primary" style="padding: 0.8rem 2.5rem;">Save Settings</button>
    </div>
  </form>
</div>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
