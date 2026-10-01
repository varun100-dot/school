<?php
// Zuvio Global School - Admin Admissions Enquiries
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('enquiries.view');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? 'list';
$msg = '';
$error = '';

// Update status handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status']) && $id > 0) {
    require_permission('enquiries.update');
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $status_id = (int)$_POST['status_id'];
        try {
            $old_stmt = $db->prepare("SELECT * FROM `enquiries` WHERE `id` = ? LIMIT 1");
            $old_stmt->execute([$id]);
            $old_enquiry = $old_stmt->fetch();
            
            $stmt = $db->prepare("UPDATE `enquiries` SET `status_id` = ? WHERE `id` = ?");
            $stmt->execute([$status_id, $id]);
            
            log_audit('ENQUIRY_UPDATED', 'enquiries', 'enquiries', $id, $old_enquiry, ['status_id' => $status_id], "Updated enquiry ID {$id} status");
            header('Location: /admin/enquiries?msg=status_updated');
            exit;
        } catch (Exception $e) {
            $error = 'Failed to update status: ' . $e->getMessage();
        }
    }
}

// Fetch all status definitions
$statuses = [];
if ($db) {
    try {
        $statuses = $db->query("SELECT * FROM `enquiry_statuses` ORDER BY `id` ASC")->fetchAll();
    } catch (Exception $e) {
        error_log("[Enquiry Status Fetch Error] " . $e->getMessage());
    }
}

// Fetch single enquiry details
$enq = null;
if ($action === 'view' && $id > 0) {
    try {
        $stmt = $db->prepare("
            SELECT e.*, s.name as status_name 
            FROM `enquiries` e
            LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
            WHERE e.id = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        $enq = $stmt->fetch();
        if (!$enq) {
            header('Location: /admin/enquiries?error=notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = "Error loading enquiry details.";
    }
}

// Handle CSV Export
if ($action === 'export_csv') {
    require_permission('enquiries.view');
    $export_rows = [];
    if ($db) {
        try {
            $export_rows = $db->query("
                SELECT e.id, e.parent_name, e.student_name, e.email, e.phone, e.grade, e.source, s.name as status_name, e.message, e.created_at
                FROM `enquiries` e
                LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
                ORDER BY e.created_at DESC
            ")->fetchAll();
        } catch (Exception $e) {
            error_log("[CSV Export Error] " . $e->getMessage());
        }
    }
    if (!empty($_SESSION['mock_enquiries'])) {
        foreach ($_SESSION['mock_enquiries'] as $mock_lead) {
            $export_rows[] = [
                'id' => $mock_lead['id'] ?? time(),
                'parent_name' => $mock_lead['parent_name'] ?? '',
                'student_name' => $mock_lead['student_name'] ?? '',
                'email' => $mock_lead['email'] ?? '',
                'phone' => $mock_lead['phone'] ?? '',
                'grade' => $mock_lead['grade'] ?? '',
                'source' => $mock_lead['source'] ?? 'Website Form',
                'status_name' => 'New',
                'message' => $mock_lead['message'] ?? '',
                'created_at' => $mock_lead['created_at'] ?? date('Y-m-d H:i:s')
            ];
        }
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=zuvio_enquiries_' . date('Y-m-d_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Parent Name', 'Student Name', 'Email', 'Phone', 'Grade', 'Source', 'Status', 'Message', 'Created At']);
    foreach ($export_rows as $r) {
        fputcsv($output, [
            $r['id'] ?? '',
            $r['parent_name'] ?? '',
            $r['student_name'] ?? '',
            $r['email'] ?? '',
            $r['phone'] ?? '',
            $r['grade'] ?? '',
            $r['source'] ?? '',
            $r['status_name'] ?? 'NEW',
            $r['message'] ?? '',
            $r['created_at'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Listing all enquiries
$search_q = trim($_GET['q'] ?? '');
$status_filter = (int)($_GET['status_id'] ?? 0);

$enquiries = [];
if ($action === 'list') {
    if ($db) {
        try {
            $sql = "
                SELECT e.*, s.name as status_name 
                FROM `enquiries` e
                LEFT JOIN `enquiry_statuses` s ON s.id = e.status_id
                WHERE 1=1
            ";
            $params = [];
            if ($status_filter > 0) {
                $sql .= " AND e.status_id = ?";
                $params[] = $status_filter;
            }
            if ($search_q !== '') {
                $sql .= " AND (e.parent_name LIKE ? OR e.student_name LIKE ? OR e.email LIKE ? OR e.phone LIKE ?)";
                $wild = "%$search_q%";
                $params = array_merge($params, [$wild, $wild, $wild, $wild]);
            }
            $sql .= " ORDER BY e.created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $enquiries = $stmt->fetchAll();
        } catch (Exception $e) {
            $error = "Database queries failed: " . $e->getMessage();
        }
    }
    
    // Merge mock enquiries from session if available
    if (!empty($_SESSION['mock_enquiries'])) {
        foreach ($_SESSION['mock_enquiries'] as $mock_lead) {
            if ($status_filter > 0 && ($mock_lead['status_id'] ?? 1) !== $status_filter) continue;
            if ($search_q !== '' && stripos($mock_lead['parent_name'] . ' ' . $mock_lead['email'] . ' ' . $mock_lead['phone'], $search_q) === false) continue;
            $mock_lead['status_name'] = 'New';
            array_unshift($enquiries, $mock_lead);
        }
    }
}

$page_slug = 'admin-enquiries';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Admissions Enquiries CRM</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Review, filter, update pipeline status and export leads.</p>
  </div>
  <div style="display: flex; gap: 0.75rem; align-items: center;">
    <?php if ($action === 'list'): ?>
      <a href="/admin/enquiries?action=export_csv" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; background: #047857; border-color: #047857;">
        📥 Export CSV
      </a>
    <?php else: ?>
      <a href="/admin/enquiries" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to List</a>
    <?php endif; ?>
  </div>
</div>

<?php if ($action === 'list'): ?>
  <!-- Filters Bar -->
  <form method="GET" action="/admin/enquiries" style="background: #fff; padding: 1rem 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border); margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <input type="text" name="q" value="<?php echo h($search_q); ?>" placeholder="Search name, email, phone..." style="flex-grow: 1; min-width: 220px; padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem;">
    <select name="status_id" style="padding: 0.5rem 0.75rem; border: 1px solid var(--color-border); border-radius: 4px; font-size: 0.85rem; background: #fff;">
      <option value="0">All Statuses</option>
      <?php foreach ($statuses as $st): ?>
        <option value="<?php echo $st['id']; ?>" <?php echo $status_filter == $st['id'] ? 'selected' : ''; ?>>
          <?php echo h($st['name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">Filter</button>
    <?php if ($search_q !== '' || $status_filter > 0): ?>
      <a href="/admin/enquiries" style="font-size: 0.85rem; color: #EF4444; text-decoration: none;">Clear</a>
    <?php endif; ?>
  </form>
<?php endif; ?>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'status_updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Enquiry status updated successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- LIST -->
<?php if ($action === 'list'): ?>
  <div class="card" style="border-left: none; padding: 2rem;">
    <?php if (!empty($enquiries)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Parent Name</th>
              <th style="padding: 0.75rem 1rem;">Student Name</th>
              <th style="padding: 0.75rem 1rem;">Email</th>
              <th style="padding: 0.75rem 1rem;">Phone</th>
              <th style="padding: 0.75rem 1rem;">Grade</th>
              <th style="padding: 0.75rem 1rem;">Source</th>
              <th style="padding: 0.75rem 1rem;">Status</th>
              <th style="padding: 0.75rem 1rem;">Date Received</th>
              <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($enquiries as $row): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($row['parent_name']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($row['student_name']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($row['email']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($row['phone']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($row['grade']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($row['source']); ?></td>
                <td style="padding: 0.75rem 1rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 700; background-color: var(--color-surface-blue); color: var(--color-navy);">
                    <?php echo h($row['status_name'] ?: 'NEW'); ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/enquiries?action=view&id=<?php echo $row['id']; ?>" style="color: var(--color-gold); font-weight: 600;">View Details</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center;">No admissions enquiries received yet.</p>
    <?php endif; ?>
  </div>

<!-- VIEW DETAILS -->
<?php elseif ($action === 'view' && $enq): ?>
  <div class="grid-3" style="align-items: flex-start; gap: 2rem;">
    
    <!-- Left Column: Details -->
    <div class="card" style="grid-column: span 2; border-left: none; padding: 2.5rem;">
      <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">Enquiry Submission Details</h3>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
        <div>
          <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Parent Name:</strong>
          <span><?php echo h($enq['parent_name']); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Student Name:</strong>
          <span><?php echo h($enq['student_name']); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Email Address:</strong>
          <a href="mailto:<?php echo h($enq['email']); ?>" style="color: var(--color-gold); font-weight: 600;"><?php echo h($enq['email']); ?></a>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Phone Number:</strong>
          <a href="tel:<?php echo h($enq['phone']); ?>" style="color: var(--color-gold); font-weight: 600;">+91 <?php echo h($enq['phone']); ?></a>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Grade Level Request:</strong>
          <span><?php echo h($enq['grade']); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block; margin-bottom: 0.25rem;">Form Source Location:</strong>
          <span><?php echo h($enq['source']); ?></span>
        </div>
      </div>

      <div style="background-color: var(--color-surface-blue); padding: 1.5rem; border-radius: var(--radius-sm); border-left: 3px solid var(--color-gold); margin-bottom: 2rem;">
        <strong style="color: var(--color-navy); display: block; font-size: 0.85rem; margin-bottom: 0.5rem;">Message Body:</strong>
        <p style="font-size: 0.9rem; line-height: 1.6; color: var(--color-text); margin: 0; white-space: pre-wrap;"><?php echo h($enq['message']); ?></p>
      </div>
      
      <a href="/admin/enquiries" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">&larr; Back to List</a>
    </div>

    <!-- Right Column: Status Controls -->
    <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-secondary);">Status Pipeline</h3>
      
      <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        
        <div class="admin-form-group">
          <label class="admin-label">Assign New Status</label>
          <select name="status_id" class="admin-input" style="height: 38px; margin-bottom: 1rem;">
            <?php foreach ($statuses as $st): ?>
              <option value="<?php echo $st['id']; ?>" <?php echo $enq['status_id'] == $st['id'] ? 'selected' : ''; ?>>
                <?php echo h($st['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" name="update_status" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Update Status</button>
      </form>
    </div>

  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
