<?php
// Zuvio Global School - Admin Announcements Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();

$use_mock = !$db;
$migration_needed = false;

// If DB is online, check if announcements table exists
if ($db) {
    try {
        $stmt = $db->query("SELECT 1 FROM `announcements` LIMIT 1");
    } catch (Exception $e) {
        // Table doesn't exist (e.g. SQLSTATE[42S02] / error 1146) or other DB issue
        $use_mock = true;
        $migration_needed = true;
    }
}

// Handle mock sessions fallback if local DB is offline or table is not migrated yet
if ($use_mock) {
    if (!isset($_SESSION['mock_announcements'])) {
        $_SESSION['mock_announcements'] = [
            [
                'id' => 1,
                'text' => 'Admissions ongoing for Mid-Session 2026–27 | Admissions open for Children with Learning Disabilities.',
                'button_text' => 'Apply Now',
                'button_url' => '/contact',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'id' => 2,
                'text' => 'Unlock Future Skills: Enroll for Coding & AI Workshops at Zuvio Global School.',
                'button_text' => 'Enquire Now',
                'button_url' => '/contact',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'id' => 3,
                'text' => 'Zuvio is now an Oxford Quality Partner, delivering internationally benchmarked educational materials.',
                'button_text' => 'Learn More',
                'button_url' => '/about',
                'sort_order' => 3,
                'is_active' => 1
            ]
        ];
    }
}

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';
$error = '';

// Helper to find announcement
function find_announcement($ann_id) {
    global $db, $use_mock;
    if (!$use_mock && $db) {
        try {
            $stmt = $db->prepare("SELECT * FROM `announcements` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$ann_id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return false;
        }
    } else {
        foreach ($_SESSION['mock_announcements'] as $ann) {
            if ($ann['id'] === $ann_id) return $ann;
        }
    }
    return false;
}

// 1. Action Handler: Delete Announcement
if ($action === 'delete' && $id > 0) {
    if (!$use_mock && $db) {
        try {
            $stmt = $db->prepare("SELECT * FROM `announcements` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$id]);
            $old = $stmt->fetch();
            if ($old) {
                $stmt = $db->prepare("DELETE FROM `announcements` WHERE `id` = ?");
                $stmt->execute([$id]);
                log_audit('ANNOUNCEMENT_DELETED', 'announcements', 'announcements', $id, $old, null, "Deleted announcement {$id}");
                header('Location: /admin/announcements.php?msg=deleted');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Delete Error: ' . $e->getMessage();
        }
    } else {
        foreach ($_SESSION['mock_announcements'] as $idx => $ann) {
            if ($ann['id'] === $id) {
                unset($_SESSION['mock_announcements'][$idx]);
                $_SESSION['mock_announcements'] = array_values($_SESSION['mock_announcements']); // reset indices
                header('Location: /admin/announcements.php?msg=deleted');
                exit;
            }
        }
    }
}

// 2. Action Handler: Save Announcement (Create / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $text = trim($_POST['text'] ?? '');
        $button_text = trim($_POST['button_text'] ?? '');
        $button_url = trim($_POST['button_url'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($text)) {
            $error = 'Announcement text is required.';
        } else {
            if (!$use_mock && $db) {
                try {
                    if ($action === 'add') {
                        $stmt = $db->prepare("
                            INSERT INTO `announcements` (`text`, `button_text`, `button_url`, `sort_order`, `is_active`)
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$text, $button_text, $button_url, $sort_order, $is_active]);
                        $new_id = $db->lastInsertId();
                        log_audit('ANNOUNCEMENT_CREATED', 'announcements', 'announcements', $new_id, null, compact('text', 'button_text', 'button_url', 'sort_order', 'is_active'), "Created announcement {$new_id}");
                        header('Location: /admin/announcements.php?msg=added');
                    } else {
                        $old = find_announcement($id);
                        $stmt = $db->prepare("
                            UPDATE `announcements`
                            SET `text` = ?, `button_text` = ?, `button_url` = ?, `sort_order` = ?, `is_active` = ?
                            WHERE `id` = ?
                        ");
                        $stmt->execute([$text, $button_text, $button_url, $sort_order, $is_active, $id]);
                        log_audit('ANNOUNCEMENT_UPDATED', 'announcements', 'announcements', $id, $old, compact('text', 'button_text', 'button_url', 'sort_order', 'is_active'), "Updated announcement {$id}");
                        header('Location: /admin/announcements.php?msg=updated');
                    }
                    exit;
                } catch (Exception $e) {
                    $error = 'Database Error: ' . $e->getMessage();
                }
            } else {
                // Handle Mock Save
                if ($action === 'add') {
                    $new_id = time();
                    $_SESSION['mock_announcements'][] = [
                        'id' => $new_id,
                        'text' => $text,
                        'button_text' => $button_text,
                        'button_url' => $button_url,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active
                    ];
                    header('Location: /admin/announcements.php?msg=added');
                } else {
                    foreach ($_SESSION['mock_announcements'] as &$ann) {
                        if ($ann['id'] === $id) {
                            $ann['text'] = $text;
                            $ann['button_text'] = $button_text;
                            $ann['button_url'] = $button_url;
                            $ann['sort_order'] = $sort_order;
                            $ann['is_active'] = $is_active;
                        }
                    }
                    header('Location: /admin/announcements.php?msg=updated');
                }
                exit;
            }
        }
    }
}

// Fetch list of announcements
$list = [];
if (!$use_mock && $db) {
    try {
        $list = $db->query("SELECT * FROM `announcements` ORDER BY `sort_order` ASC, `id` DESC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Failed to load announcements: ' . $e->getMessage();
    }
} else {
    $list = $_SESSION['mock_announcements'];
    // Sort array by sort_order ascending
    usort($list, function($a, $b) {
        return $a['sort_order'] <=> $b['sort_order'];
    });
}

$page_slug = 'admin-announcements';
include dirname(__FILE__) . '/header.php';
?>

<div class="admin-container">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem;">
    <div>
      <h1 style="font-size: 1.75rem; color: var(--color-navy); margin: 0; font-family: var(--font-secondary);">Admissions & Announcements Bar</h1>
      <p style="color: var(--color-muted); font-size: 0.85rem; margin: 0.25rem 0 0 0;">Manage sliding announcements bar displayed at the bottom of the public website.</p>
    </div>
    <?php if ($action === 'list'): ?>
      <a href="?action=add" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">+ Add Announcement</a>
    <?php else: ?>
      <a href="/admin/announcements.php" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">Back to List</a>
    <?php endif; ?>
  </div>

  <?php if ($migration_needed): ?>
    <div style="background-color: #FEF3C7; border-left: 4px solid #D97706; padding: 1rem 1.25rem; border-radius: var(--radius-sm); color: #92400E; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 500; line-height: 1.6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <div>
        <strong>⚠️ Database Table Missing:</strong> The database table <code>announcements</code> does not exist yet. Running in simulated preview mode.<br>
        To enable live database storage, you can run the SQL migration script using the button on the right.
      </div>
      <a href="/admin/migrate.php" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; background-color: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; text-decoration: none; border-radius: var(--radius-sm);">Run Migration Now</a>
    </div>
  <?php elseif (!$db): ?>
    <div style="background-color: #FEF3C7; border-left: 4px solid #D97706; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #92400E; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 500;">
      ⚠️ Local Database Offline: Revisions will be stored inside session variables for this local server preview.
    </div>
  <?php endif; ?>

  <?php if ($msg === 'migrated_success'): ?>
    <div style="background-color: #D1FAE5; border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.85rem; margin-bottom: 1.5rem;">
      Database migrated successfully! Table <code>announcements</code> has been created and populated.
    </div>
  <?php elseif ($msg === 'added'): ?>
    <div style="background-color: #D1FAE5; border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.85rem; margin-bottom: 1.5rem;">
      Announcement added successfully!
    </div>
  <?php elseif ($msg === 'updated'): ?>
    <div style="background-color: #D1FAE5; border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.85rem; margin-bottom: 1.5rem;">
      Announcement updated successfully!
    </div>
  <?php elseif ($msg === 'deleted'): ?>
    <div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem;">
      Announcement deleted.
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem; font-weight: 600;">
      Error: <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <?php if ($action === 'list'): ?>
    <div class="card" style="border-left: none; padding: 1.5rem; background-color: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
      <?php if (empty($list)): ?>
        <p style="text-align: center; color: var(--color-muted); padding: 2rem 0; font-size: 0.9rem;">No announcements added yet. Click "+ Add Announcement" to begin.</p>
      <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 700;">
              <th style="padding: 0.75rem 1rem; width: 60px;">Sort</th>
              <th style="padding: 0.75rem 1rem;">Announcement Text</th>
              <th style="padding: 0.75rem 1rem; width: 120px;">Button Text</th>
              <th style="padding: 0.75rem 1rem; width: 120px;">Button URL</th>
              <th style="padding: 0.75rem 1rem; width: 80px; text-align: center;">Active</th>
              <th style="padding: 0.75rem 1rem; width: 150px; text-align: center;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($list as $ann): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo (int)$ann['sort_order']; ?></td>
                <td style="padding: 0.75rem 1rem; font-weight: 500; font-size: 0.88rem; line-height: 1.4;"><?php echo h($ann['text']); ?></td>
                <td style="padding: 0.75rem 1rem; color: var(--color-teal); font-weight: bold;"><?php echo h($ann['button_text'] ?: '-'); ?></td>
                <td style="padding: 0.75rem 1rem; font-family: monospace; color: var(--color-muted);"><?php echo h($ann['button_url'] ?: '-'); ?></td>
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: 20px; font-weight: 700; background-color: <?php echo $ann['is_active'] ? '#D1FAE5' : '#F3F4F6'; ?>; color: <?php echo $ann['is_active'] ? '#065F46' : '#374151'; ?>;">
                    <?php echo $ann['is_active'] ? 'Yes' : 'No'; ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: center;">
                  <a href="?action=edit&id=<?php echo $ann['id']; ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: var(--color-teal); color: var(--color-teal); margin-right: 0.25rem;">Edit</a>
                  <a href="?action=delete&id=<?php echo $ann['id']; ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  <?php elseif ($action === 'add' || $action === 'edit'): 
    $item = ($action === 'edit') ? find_announcement($id) : null;
  ?>
    <div class="card" style="border-left: none; padding: 2rem; background-color: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-md); max-width: 700px; margin: 0 auto; box-shadow: var(--shadow-sm);">
      <h3 style="font-size: 1.15rem; color: var(--color-navy); margin: 0 0 1.5rem 0; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        <?php echo $action === 'add' ? 'Add Announcement' : 'Edit Announcement'; ?>
      </h3>
      <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        
        <div class="admin-form-group">
          <label class="admin-label">Announcement Text *</label>
          <textarea name="text" class="admin-input" rows="3" style="resize: vertical; font-family: inherit;" required placeholder="Enter announcement content here..."><?php echo h($item['text'] ?? ''); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="admin-form-group">
            <label class="admin-label">Action Button Text (Optional)</label>
            <input type="text" name="button_text" class="admin-input" value="<?php echo h($item['button_text'] ?? ''); ?>" placeholder="e.g. Apply Now">
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Action Button Link (Optional)</label>
            <input type="text" name="button_url" class="admin-input" value="<?php echo h($item['button_url'] ?? ''); ?>" placeholder="e.g. /contact">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; align-items: center; margin-top: 0.5rem;">
          <div class="admin-form-group">
            <label class="admin-label">Sort Order</label>
            <input type="number" name="sort_order" class="admin-input" value="<?php echo (int)($item['sort_order'] ?? 0); ?>" placeholder="e.g. 0">
          </div>
          <div class="admin-form-group" style="margin-top: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">
              <input type="checkbox" name="is_active" value="1" <?php echo (!isset($item) || $item['is_active']) ? 'checked' : ''; ?>>
              Display announcement publicly
            </label>
          </div>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
          <a href="/admin/announcements.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem; font-size: 0.85rem; border-color: var(--color-border); color: var(--color-text);">Cancel</a>
          <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem; font-size: 0.85rem; background-color: var(--color-teal); border-color: var(--color-teal);"><?php echo $action === 'add' ? 'Create' : 'Save Changes'; ?></button>
        </div>
      </form>
    </div>
  <?php endif; ?>
</div>

<?php
include dirname(__FILE__) . '/footer.php';
?>
