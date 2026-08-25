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
            $migration_file = dirname(__FILE__) . '/../database/migrations/phase4_announcements.sql';
            if (!file_exists($migration_file)) {
                $error = 'Migration file not found at expected path.';
            } else {
                try {
                    $sql = file_get_contents($migration_file);
                    $db->exec($sql);
                    
                    // Log audit trail
                    log_audit('MIGRATION_RUN', 'system', 'database', 0, null, null, 'Executed phase4_announcements migration script successfully');
                    
                    header('Location: /admin/announcements.php?msg=migrated_success');
                    exit;
                } catch (Exception $e) {
                    $error = 'Migration Execution Error: ' . $e->getMessage();
                }
            }
        }
    }
}

$page_slug = 'admin-migrate';
include dirname(__FILE__) . '/header.php';
?>

<div class="admin-container" style="max-width: 600px; margin: 2rem auto;">
  <div class="card" style="border-left: none; padding: 2.5rem; background-color: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold);">
    <h2 style="font-size: 1.5rem; color: var(--color-navy); margin: 0 0 1rem 0; font-family: var(--font-secondary);">Run Database Migration</h2>
    <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem;">
      This utility will apply the <code>phase4_announcements.sql</code> script to your active database. This will create the required <code>announcements</code> table, seed data, and register administration privileges.
    </p>

    <?php if ($error): ?>
      <div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
        Error: <?php echo h($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      
      <div style="display: flex; gap: 1rem; justify-content: flex-end;">
        <a href="/admin/announcements.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem; font-size: 0.85rem;">Cancel</a>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem; font-size: 0.85rem; background-color: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700;">Execute Migration</button>
      </div>
    </form>
  </div>
</div>

<?php
include dirname(__FILE__) . '/footer.php';
?>
