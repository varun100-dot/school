<?php
// Zuvio Global School - Admin Database Migrator
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login(); // Ensure user is logged in

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        if (!$db) {
            $error = 'No active database connection found to run migrations.';
        } else {
            $selected_migration = $_POST['migration_file'] ?? 'phase5_cms_redesign.sql';
            // Validate filename to prevent path traversal
            $safe_filename = basename($selected_migration);
            $migration_file = dirname(__FILE__) . '/../database/migrations/' . $safe_filename;
            
            if (!file_exists($migration_file)) {
                $error = 'Migration file not found at: ' . $safe_filename;
            } else {
                try {
                    $sql = file_get_contents($migration_file);
                    $db->exec($sql);
                    
                    // Log audit trail
                    log_audit('MIGRATION_RUN', 'system', 'database', 0, null, null, "Executed {$safe_filename} migration script successfully");
                    
                    $msg = "Successfully executed migration: {$safe_filename}";
                } catch (Exception $e) {
                    $error = 'Migration Execution Error: ' . $e->getMessage();
                }
            }
        }
    }
}

// Scan available migrations
$migrations_dir = dirname(__FILE__) . '/../database/migrations/';
$available_migrations = glob($migrations_dir . '*.sql');

$page_slug = 'admin-migrate';
include dirname(__FILE__) . '/header.php';
?>

<div class="admin-container" style="max-width: 650px; margin: 2rem auto;">
  <div class="card" style="border-left: none; padding: 2.5rem; background-color: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold);">
    <h2 style="font-size: 1.5rem; color: var(--color-navy); margin: 0 0 1rem 0; font-family: var(--font-secondary);">Run Database Migration</h2>
    <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem;">
      Apply the Phase 5 CMS Redesign script or any migration to your active database. This provisions new tables (<code>faqs</code>, <code>testimonials</code>, <code>accreditations</code>, <code>homepage_sections</code>, <code>homepage_cards</code>), updates navigation hierarchy, and seeds all 18 parent FAQs verbatim.
    </p>

    <?php if ($msg): ?>
      <div style="background-color: #DEF7EC; border-left: 4px solid #10B981; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #03543F; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
        <?php echo h($msg); ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
        Error: <?php echo h($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      
      <div class="admin-form-group">
        <label class="admin-label">Select Migration Script:</label>
        <select name="migration_file" class="admin-input" style="font-weight: 600;">
          <?php foreach ($available_migrations as $m_path): 
            $m_name = basename($m_path);
            $selected = ($m_name === 'phase5_cms_redesign.sql') ? 'selected' : '';
          ?>
            <option value="<?php echo h($m_name); ?>" <?php echo $selected; ?>>
              <?php echo h($m_name); ?> <?php echo ($m_name === 'phase5_cms_redesign.sql') ? '(Recommended / Latest)' : ''; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
        <a href="/admin" class="btn btn-outline" style="padding: 0.6rem 1.5rem; font-size: 0.85rem;">Dashboard</a>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem; font-size: 0.85rem; background-color: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700;">Execute Migration</button>
      </div>
    </form>
  </div>
</div>

<?php
include dirname(__FILE__) . '/footer.php';
?>
