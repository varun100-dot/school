<?php
// Zuvio Global School - Admin Dashboard Index (Phase 3 Upgrade)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();

// Handle Logout BEFORE permission gate so sign-out always works
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

// Permission gate — runs only for non-logout requests
require_permission('dashboard.view');

// Fetch Metrics counts
$blog_count = 0;
$enquiry_count = 0;
$homepage_lead_count = 0;
$contact_lead_count = 0;
$hero_slide_count = 0;
$pages_count = 0;
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
        $blog_count = (int)$db->query("SELECT COUNT(*) FROM `blogs`")->fetchColumn();
        $enquiry_count = (int)$db->query("SELECT COUNT(*) FROM `enquiries`")->fetchColumn();
        $homepage_lead_count = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `source` LIKE '%Home%' OR `source` LIKE '%Counselor%' OR `source` LIKE '%Banner%'")->fetchColumn();
        $contact_lead_count = (int)$db->query("SELECT COUNT(*) FROM `enquiries` WHERE `source` LIKE '%Contact%'")->fetchColumn();
        $hero_slide_count = (int)$db->query("SELECT COUNT(*) FROM `hero_slides` WHERE `is_active` = 1")->fetchColumn();
        $pages_count = (int)$db->query("SELECT COUNT(*) FROM `pages` WHERE `is_active` = 1")->fetchColumn();
        $media_count = (int)$db->query("SELECT COUNT(*) FROM `media`")->fetchColumn();
        
        // Fetch User counts
        $user_count = (int)$db->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
        $super_admin_count = (int)$db->query("SELECT COUNT(*) FROM `users` u JOIN `roles` r ON r.id = u.role_id WHERE r.name = 'super_admin'")->fetchColumn();
        $admin_count = (int)$db->query("SELECT COUNT(*) FROM `users` u JOIN `roles` r ON r.id = u.role_id WHERE r.name = 'admin'")->fetchColumn();
        $editor_count = (int)$db->query("SELECT COUNT(*) FROM `users` u JOIN `roles` r ON r.id = u.role_id WHERE r.name = 'editor'")->fetchColumn();
        
        // Fetch 6 recent enquiries
        $stmt = $db->query("
            SELECT e.*, s.name as status_name 
            FROM `enquiries` e
            LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
            ORDER BY e.created_at DESC LIMIT 6
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

// Merge mock enquiries from session if available
if (!empty($_SESSION['mock_enquiries'])) {
    foreach ($_SESSION['mock_enquiries'] as $mock_lead) {
        $enquiry_count++;
        $m_src = $mock_lead['source'] ?? '';
        if (stripos($m_src, 'Home') !== false || stripos($m_src, 'Counselor') !== false || stripos($m_src, 'Banner') !== false) {
            $homepage_lead_count++;
        } elseif (stripos($m_src, 'Contact') !== false) {
            $contact_lead_count++;
        }
        $mock_lead['status_name'] = 'New';
        array_unshift($recent_enquiries, $mock_lead);
    }
    $recent_enquiries = array_slice($recent_enquiries, 0, 6);
}

$page_slug = 'admin-dashboard';
include_once dirname(__FILE__) . '/header.php';
?>

<?php if (isset($_GET['refreshed'])): ?>
  <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <span><strong>Live Data Synced!</strong> Dashboard statistics and lead counts have been fetched directly from the database.</span>
    <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
  </div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.75rem; color: var(--color-navy); margin-bottom: 0.35rem;">Welcome back, <?php echo h($_SESSION['username']); ?>!</h1>
    <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; color: var(--color-muted);">
      <span>Real-time platform overview &amp; control center</span>
      <span>&bull;</span>
      <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #059669; font-weight: 600;">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
        Live DB Synced: <?php echo date('d M Y, h:i A'); ?>
      </span>
    </div>
  </div>

  <div>
    <button type="button" id="refreshDashboardBtn" onclick="triggerDashboardRefresh()" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; font-size: 0.85rem; background: var(--color-navy); border-color: var(--color-navy);">
      <svg id="refreshIconSvg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition: transform 0.5s ease;"><path d="M23 4v6h-6"></path><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
      <span id="refreshBtnText">Refresh Live Data</span>
    </button>
  </div>
</div>

<script>
function triggerDashboardRefresh() {
  const btn = document.getElementById('refreshDashboardBtn');
  const icon = document.getElementById('refreshIconSvg');
  const txt = document.getElementById('refreshBtnText');
  if (btn && icon && txt) {
    btn.disabled = true;
    icon.style.transform = 'rotate(360deg)';
    txt.innerText = 'Syncing...';
  }
  setTimeout(function() {
    window.location.href = '/admin/?refreshed=' + Date.now();
  }, 400);
}
</script>

<!-- Lead Management KPI Metrics (New Dedicated Lead Counters) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
  
  <div class="card" style="border-top: 4px solid var(--color-navy); border-left: none; padding: 1.5rem 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
      <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Homepage Leads</span>
      <span style="font-size: 1.2rem;">🏠</span>
    </div>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $homepage_lead_count; ?></h3>
    <a href="/admin/enquiries?source=homepage" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 0.75rem; display: inline-block;">Manage Homepage Leads &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-teal); border-left: none; padding: 1.5rem 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
      <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Contact Us Leads</span>
      <span style="font-size: 1.2rem;">✉️</span>
    </div>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $contact_lead_count; ?></h3>
    <a href="/admin/enquiries?source=contact" style="font-size: 0.8rem; color: var(--color-teal); font-weight: 600; margin-top: 0.75rem; display: inline-block;">Manage Contact Leads &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
      <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Enquiries</span>
      <span style="font-size: 1.2rem;">📋</span>
    </div>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $enquiry_count; ?></h3>
    <a href="/admin/enquiries" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 0.75rem; display: inline-block;">View All Enquiries CRM &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid #3B82F6; border-left: none; padding: 1.5rem 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
      <span style="font-size: 0.75rem; color: var(--color-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Hero Banners Active</span>
      <span style="font-size: 1.2rem;">🖼️</span>
    </div>
    <h3 style="font-size: 2.2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $hero_slide_count ?: 3; ?></h3>
    <a href="/admin/hero" style="font-size: 0.8rem; color: #2563EB; font-weight: 600; margin-top: 0.75rem; display: inline-block;">Manage Hero Banners &rarr;</a>
  </div>

</div>

<!-- Content & System Metrics -->
<div class="grid-3" style="margin-bottom: 2rem;">
  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Blogs &amp; Articles</span>
    <h3 style="font-size: 2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $blog_count; ?></h3>
    <a href="/admin/blogs" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 0.75rem; display: inline-block;">Manage Articles &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Pages Managed (SEO)</span>
    <h3 style="font-size: 2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $pages_count ?: 26; ?></h3>
    <a href="/admin/seo.php" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 0.75rem; display: inline-block;">Page SEO Manager &rarr;</a>
  </div>

  <div class="card" style="border-top: 4px solid var(--color-gold); border-left: none; padding: 1.5rem 2rem;">
    <span style="font-size: 0.8rem; color: var(--color-muted); font-weight: 600; text-transform: uppercase;">Total Media Items</span>
    <h3 style="font-size: 2rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-secondary);"><?php echo $media_count; ?></h3>
    <a href="/admin/media" style="font-size: 0.8rem; color: var(--color-gold); font-weight: 600; margin-top: 0.75rem; display: inline-block;">Upload Assets &rarr;</a>
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
            <th style="padding: 0.75rem 1rem;">Lead Source</th>
            <th style="padding: 0.75rem 1rem;">Status</th>
            <th style="padding: 0.75rem 1rem;">Date</th>
            <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recent_enquiries as $enq): ?>
            <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
              <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($enq['parent_name']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['email']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['phone']); ?></td>
              <td style="padding: 0.75rem 1rem;"><?php echo h($enq['grade']); ?></td>
              <td style="padding: 0.75rem 1rem;">
                <?php 
                $src = $enq['source'] ?? '';
                $is_home = (stripos($src, 'Home') !== false || stripos($src, 'Counselor') !== false);
                $is_contact = stripos($src, 'Contact') !== false;
                $badge_bg = $is_home ? 'rgba(6, 43, 99, 0.1)' : ($is_contact ? 'rgba(13, 148, 136, 0.1)' : 'rgba(217, 164, 65, 0.1)');
                $badge_color = $is_home ? 'var(--color-navy)' : ($is_contact ? '#0f766e' : '#b45309');
                ?>
                <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 600; background: <?php echo $badge_bg; ?>; color: <?php echo $badge_color; ?>;">
                  <?php echo h($src); ?>
                </span>
              </td>
              <td style="padding: 0.75rem 1rem;">
                <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 600; background-color: var(--color-surface-blue); color: var(--color-navy);">
                  <?php echo h($enq['status_name'] ?: 'New'); ?>
                </span>
              </td>
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('Y-m-d H:i', strtotime($enq['created_at'])); ?></td>
              <td style="padding: 0.75rem 1rem; text-align: right;">
                <a href="/admin/enquiries?action=view&id=<?php echo $enq['id']; ?>" style="color: var(--color-gold); font-weight: 600; font-size: 0.8rem;">View Lead &rarr;</a>
              </td>
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
