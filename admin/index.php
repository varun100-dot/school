<?php
// Zuvio Global School - Admin Dashboard Index (Phase 3 Upgrade)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('dashboard.view');

// Handle Logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Log logout audit
    log_audit('USER_LOGOUT', 'auth', 'users', $_SESSION['user_id'] ?? null, null, null, 'User logged out');
    
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: /admin/login');
    exit;
}

// Fetch Metrics counts
$blog_count = 0;
$enquiry_count = 0;
$media_count = 0;

$user_count = 0;
$super_admin_count = 0;
$admin_count = 0;
$editor_count = 0;

$recent_enquiries = [];
$recent_changes = [];
$recent_hero_versions = [];

if ($db) {
    try {
        $blog_count = $db->query("SELECT COUNT(*) FROM `blogs`")->fetchColumn();
        $enquiry_count = $db->query("SELECT COUNT(*) FROM `enquiries`")->fetchColumn();
        $media_count = $db->query("SELECT COUNT(*) FROM `media`")->fetchColumn();
        
        // Fetch User counts
        $user_count = $db->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
        $super_admin_count = $db->query("SELECT COUNT(*) FROM `users` u JOIN `roles` r ON r.id = u.role_id WHERE r.name = 'super_admin'")->fetchColumn();
        $admin_count = $db->query("SELECT COUNT(*) FROM `users` u JOIN `roles` r ON r.id = u.role_id WHERE r.name = 'admin'")->fetchColumn();
        $editor_count = $db->query("SELECT COUNT(*) FROM `users` u JOIN `roles` r ON r.id = u.role_id WHERE r.name = 'editor'")->fetchColumn();
        
        // Fetch 5 recent enquiries
        $stmt = $db->query("
            SELECT e.*, s.name as status_name 
            FROM `enquiries` e
            LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
            ORDER BY e.created_at DESC LIMIT 5
        ");
        $recent_enquiries = $stmt->fetchAll();
        
        // Fetch 5 recent changes (audits)
        $stmt = $db->query("
            SELECT a.*, u.username 
            FROM `audit_logs` a
            LEFT JOIN `users` u ON u.id = a.user_id
            ORDER BY a.created_at DESC LIMIT 5
        ");
        $recent_changes = $stmt->fetchAll();
        
        // Fetch 5 recent hero versions
        $stmt = $db->query("
            SELECT v.*, u.username 
            FROM `hero_slide_versions` v
            LEFT JOIN `users` u ON u.id = v.created_by
            ORDER BY v.created_at DESC LIMIT 5
        ");
        $recent_hero_versions = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Dashboard Query Error] " . $e->getMessage());
    }
}

$page_slug = 'admin-dashboard';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="margin-bottom: 2rem;">
  <h1 style="font-family: var(--font-secondary); font-size: 1.75rem; color: var(--color-navy); margin-bottom: 0.5rem;">Welcome back, <?php echo h($_SESSION['username']); ?>!</h1>
  <p style="color: var(--color-muted); font-size: 0.9rem;">Here is a summary status overview of the Zuvio Global School platform.</p>
</div>

<!-- Primary counts indicators -->
<div class="grid-3" style="margin-bottom: 2rem;">
  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Blogs</span>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $blog_count; ?></h3>
    <a href="/admin/blogs" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 1rem; display: inline-block;">Manage Articles &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Enquiries</span>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $enquiry_count; ?></h3>
    <a href="/admin/enquiries" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 1rem; display: inline-block;">View Admissions &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Media Items</span>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $media_count; ?></h3>
    <a href="/admin/media" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 1rem; display: inline-block;">Upload Assets &rarr;</a>
  </div>
</div>

<!-- Super Admin metrics cards -->
<?php if (has_permission('users.view')): ?>
  <div style="margin-bottom: 2.5rem;">
    <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-secondary);">System User Privileges</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem;">
      
      <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); padding: 1rem 1.5rem; border-radius: var(--radius-sm);">
        <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Users</span>
        <h4 style="font-size: 1.8rem; color: var(--color-navy); margin-top: 0.25rem; font-family: var(--font-secondary);"><?php echo $user_count; ?></h4>
      </div>

      <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); padding: 1rem 1.5rem; border-radius: var(--radius-sm);">
        <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Super Admins</span>
        <h4 style="font-size: 1.8rem; color: var(--color-navy); margin-top: 0.25rem; font-family: var(--font-secondary);"><?php echo $super_admin_count; ?></h4>
      </div>

      <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); padding: 1rem 1.5rem; border-radius: var(--radius-sm);">
        <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Admins</span>
        <h4 style="font-size: 1.8rem; color: var(--color-navy); margin-top: 0.25rem; font-family: var(--font-secondary);"><?php echo $admin_count; ?></h4>
      </div>

      <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); padding: 1rem 1.5rem; border-radius: var(--radius-sm);">
        <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Editors</span>
        <h4 style="font-size: 1.8rem; color: var(--color-navy); margin-top: 0.25rem; font-family: var(--font-secondary);"><?php echo $editor_count; ?></h4>
      </div>

    </div>
  </div>
<?php endif; ?>

<!-- Two columns dashboard widgets -->
<div class="grid-2" style="margin-bottom: 3rem; gap: 2rem;">
  
  <!-- Widget: Audit trail -->
  <?php if (has_permission('audit.view')): ?>
    <div class="card" style="border-left: none; padding: 2rem;">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-secondary);">Recent Changes (System Audits)</h3>
      <?php if (!empty($recent_changes)): ?>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; line-height: 1.6;">
          <?php foreach ($recent_changes as $log): ?>
            <li style="border-bottom: 1px solid var(--color-border); padding: 0.5rem 0; color: var(--color-text);">
              <span style="font-weight: 600; color: var(--color-navy);"><?php echo h($log['username'] ?: 'System'); ?></span>
              uploaded or edited entity inside <strong><?php echo h($log['module']); ?></strong>:
              <span style="color: var(--color-muted); display: block; font-size: 0.75rem;">
                <?php echo h($log['description']); ?> &bull; <?php echo date('Y-m-d H:i', strtotime($log['created_at'])); ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p style="color: var(--color-muted); font-size: 0.8rem;">No changes registered in audit logs yet.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Widget: Recent Hero Slider Versions -->
  <?php if (has_permission('hero.history')): ?>
    <div class="card" style="border-left: none; padding: 2rem;">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-secondary);">Recent Hero Slide Snapshots</h3>
      <?php if (!empty($recent_hero_versions)): ?>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; line-height: 1.6;">
          <?php foreach ($recent_hero_versions as $ver): ?>
            <li style="border-bottom: 1px solid var(--color-border); padding: 0.5rem 0; color: var(--color-text);">
              Slide ID #<?php echo $ver['hero_slide_id']; ?>: <strong>Version <?php echo $ver['version_number']; ?></strong> snapshot captured by <?php echo h($ver['username'] ?: 'System'); ?>
              <span style="color: var(--color-muted); display: block; font-size: 0.75rem;">
                <?php echo h($ver['change_summary'] ?: 'Saved banner changes'); ?> &bull; <?php echo date('Y-m-d H:i', strtotime($ver['created_at'])); ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p style="color: var(--color-muted); font-size: 0.8rem;">No slide changes versioned yet.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

</div>

<!-- Recent enquiries layout -->
<div class="card" style="border-left: none; padding: 2rem;">
  <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary);">Recent Student Enquiries</h3>
  
  <?php if (!empty($recent_enquiries)): ?>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
        <thead>
          <tr style="border-bottom: 2.5px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
            <th style="padding: 0.75rem 1rem;">Parent Name</th>
            <th style="padding: 0.75rem 1rem;">Email</th>
            <th style="padding: 0.75rem 1rem;">Phone</th>
            <th style="padding: 0.75rem 1rem;">Grade</th>
            <th style="padding: 0.75rem 1rem;">Source</th>
            <th style="padding: 0.75rem 1rem;">Status</th>
            <th style="padding: 0.75rem 1rem;">Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recent_enquiries as $enq): ?>
            <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
              <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($enq['parent_name']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['email']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['phone']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['grade']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['source']); ?></td>
              <td style="padding: 0.75rem 1rem;">
                <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 600; background-color: var(--color-surface-blue); color: var(--color-navy);">
                  <?php echo h($enq['status_name'] ?: 'New'); ?>
                </span>
              </td>
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('Y-m-d H:i', strtotime($enq['created_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p style="color: var(--color-muted); font-size: 0.85rem;">No enquiries received yet.</p>
  <?php endif; ?>
</div>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
