<?php
// Zuvio Global School - Admin Dashboard Index
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_admin_role();

// Handle Logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
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
$recent_enquiries = [];

if ($db) {
    try {
        $blog_count = $db->query("SELECT COUNT(*) FROM `blogs`")->fetchColumn();
        $enquiry_count = $db->query("SELECT COUNT(*) FROM `enquiries`")->fetchColumn();
        $media_count = $db->query("SELECT COUNT(*) FROM `media`")->fetchColumn();
        
        // Fetch 5 recent enquiries
        $stmt = $db->query("
            SELECT e.*, s.name as status_name 
            FROM `enquiries` e
            LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
            ORDER BY e.created_at DESC LIMIT 5
        ");
        $recent_enquiries = $stmt->fetchAll();
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

<!-- Grid metrics -->
<div class="grid-3" style="margin-bottom: 3rem;">
  
  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Blogs</span>
    <h3 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $blog_count; ?></h3>
    <a href="/admin/blogs" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 1rem; display: inline-block;">Manage Articles &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Enquiries</span>
    <h3 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $enquiry_count; ?></h3>
    <a href="/admin/enquiries" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 1rem; display: inline-block;">View Admissions &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Media Items</span>
    <h3 style="font-size: 2.5rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $media_count; ?></h3>
    <a href="/admin/media" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 1rem; display: inline-block;">Upload Assets &rarr;</a>
  </div>

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
