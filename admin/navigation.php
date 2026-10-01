<?php
// Zuvio Global School - Admin Navigation Menu Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Default initial navigation hierarchy
$default_nav = [
    ['id' => 1, 'label' => 'Home', 'url' => '/', 'parent_id' => null, 'sort_order' => 1, 'is_active' => 1],
    ['id' => 2, 'label' => 'About Us', 'url' => '/about', 'parent_id' => null, 'sort_order' => 2, 'is_active' => 1],
    ['id' => 3, 'label' => 'About Zuvio', 'url' => '/about-zuvio', 'parent_id' => 2, 'sort_order' => 1, 'is_active' => 1],
    ['id' => 4, 'label' => 'Our Team', 'url' => '/our-team', 'parent_id' => 2, 'sort_order' => 2, 'is_active' => 1],
    ['id' => 5, 'label' => 'Founder’s Message', 'url' => '/founder-message', 'parent_id' => 2, 'sort_order' => 3, 'is_active' => 1],
    ['id' => 6, 'label' => 'Accreditations', 'url' => '/affiliations-accreditations', 'parent_id' => 2, 'sort_order' => 4, 'is_active' => 1],
    ['id' => 7, 'label' => 'Academics', 'url' => '/academics', 'parent_id' => null, 'sort_order' => 3, 'is_active' => 1],
    ['id' => 8, 'label' => 'Curriculum Framework', 'url' => '/curriculum', 'parent_id' => 7, 'sort_order' => 1, 'is_active' => 1],
    ['id' => 9, 'label' => 'Technology & AI Labs', 'url' => '/technology', 'parent_id' => 7, 'sort_order' => 2, 'is_active' => 1],
    ['id' => 10, 'label' => 'Special Education', 'url' => '/special-education', 'parent_id' => 7, 'sort_order' => 3, 'is_active' => 1],
    ['id' => 11, 'label' => 'Electives & Languages', 'url' => '/electives', 'parent_id' => 7, 'sort_order' => 4, 'is_active' => 1],
    ['id' => 12, 'label' => 'NEP 2020 Guidelines', 'url' => '/nep-2020', 'parent_id' => 7, 'sort_order' => 5, 'is_active' => 1],
    ['id' => 13, 'label' => 'Academic Resources', 'url' => '/resources', 'parent_id' => 7, 'sort_order' => 6, 'is_active' => 1],
    ['id' => 14, 'label' => 'Admissions', 'url' => '/admissions', 'parent_id' => null, 'sort_order' => 4, 'is_active' => 1],
    ['id' => 15, 'label' => 'Enrol Now (5 Steps)', 'url' => '/admissions/enrol-now', 'parent_id' => 14, 'sort_order' => 1, 'is_active' => 1],
    ['id' => 16, 'label' => 'Eligibility Matrix', 'url' => '/admissions/eligibility', 'parent_id' => 14, 'sort_order' => 2, 'is_active' => 1],
    ['id' => 17, 'label' => 'Academic Calendar', 'url' => '/admissions/calendar', 'parent_id' => 14, 'sort_order' => 3, 'is_active' => 1],
    ['id' => 18, 'label' => 'Fees Structure', 'url' => '/admissions/fees', 'parent_id' => 14, 'sort_order' => 4, 'is_active' => 1],
    ['id' => 19, 'label' => 'Parent FAQs', 'url' => '/faq', 'parent_id' => 14, 'sort_order' => 5, 'is_active' => 1],
    ['id' => 20, 'label' => 'Beyond', 'url' => '/beyond', 'parent_id' => null, 'sort_order' => 5, 'is_active' => 1],
    ['id' => 21, 'label' => 'Co-curricular & Clubs', 'url' => '/beyond/co-curricular', 'parent_id' => 20, 'sort_order' => 1, 'is_active' => 1],
    ['id' => 22, 'label' => 'Student Achievers', 'url' => '/beyond/student-achievers', 'parent_id' => 20, 'sort_order' => 2, 'is_active' => 1],
    ['id' => 23, 'label' => 'Photo Gallery', 'url' => '/beyond/gallery', 'parent_id' => 20, 'sort_order' => 3, 'is_active' => 1],
    ['id' => 24, 'label' => 'Virtual Classroom', 'url' => '/beyond/virtual-classroom', 'parent_id' => 20, 'sort_order' => 4, 'is_active' => 1],
    ['id' => 25, 'label' => 'Blogs & Insights', 'url' => '/blogs', 'parent_id' => null, 'sort_order' => 6, 'is_active' => 1],
    ['id' => 26, 'label' => 'Contact Us', 'url' => '/contact', 'parent_id' => null, 'sort_order' => 7, 'is_active' => 1]
];

// Persistent Navigation Data
$nav_items = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `navigation_items` ORDER BY sort_order ASC, id ASC");
        $nav_items = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Nav Fetch Error] " . $e->getMessage());
    }
}
if (empty($nav_items)) {
    $db_saved = get_json_setting('cms_navigation', []);
    $nav_items = !empty($db_saved) ? $db_saved : $default_nav;
}

function save_navigation_state(&$items) {
    global $db;
    set_json_setting('cms_navigation', $items, 'Main Navigation Menu Items');
    if ($db) {
        try {
            $db->exec("CREATE TABLE IF NOT EXISTS `navigation_items` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `label` VARCHAR(100) NOT NULL,
                `url` VARCHAR(255) NOT NULL,
                `parent_id` INT DEFAULT NULL,
                `sort_order` INT DEFAULT 0,
                `is_active` TINYINT(1) DEFAULT 1
            )");
            // Re-sync
            $db->exec("DELETE FROM `navigation_items`");
            $ins = $db->prepare("INSERT INTO `navigation_items` (`id`, `label`, `url`, `parent_id`, `sort_order`, `is_active`) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($items as $item) {
                $ins->execute([
                    $item['id'],
                    $item['label'],
                    $item['url'],
                    !empty($item['parent_id']) ? $item['parent_id'] : null,
                    $item['sort_order'] ?? 0,
                    !empty($item['is_active']) ? 1 : 0
                ]);
            }
        } catch (Exception $e) {
            error_log("[Nav Sync Error] " . $e->getMessage());
        }
    }
}

// Action: Save / Create / Update Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_item'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed.';
    } else {
        $item_id = (int)($_POST['item_id'] ?? 0);
        $label = trim($_POST['label'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if ($label && $url) {
            if ($item_id > 0) {
                // Update
                foreach ($nav_items as &$it) {
                    if ($it['id'] == $item_id) {
                        $it['label'] = $label;
                        $it['url'] = $url;
                        $it['parent_id'] = $parent_id;
                        $it['sort_order'] = $sort_order;
                        $it['is_active'] = $is_active;
                        break;
                    }
                }
            } else {
                // Create
                $new_id = time();
                $nav_items[] = [
                    'id' => $new_id,
                    'label' => $label,
                    'url' => $url,
                    'parent_id' => $parent_id,
                    'sort_order' => $sort_order ?: count($nav_items) + 1,
                    'is_active' => $is_active
                ];
            }
            save_navigation_state($nav_items);
            header('Location: /admin/navigation.php?msg=saved');
            exit;
        } else {
            $error = 'Label and URL are required.';
        }
    }
}

// Action: Delete Item
if ($action === 'delete' && $id > 0) {
    $nav_items = array_values(array_filter($nav_items, fn($it) => $it['id'] != $id && ($it['parent_id'] ?? null) != $id));
    save_navigation_state($nav_items);
    header('Location: /admin/navigation.php?msg=deleted');
    exit;
}

// Action: Toggle Active
if ($action === 'toggle' && $id > 0) {
    foreach ($nav_items as &$it) {
        if ($it['id'] == $id) {
            $it['is_active'] = empty($it['is_active']) ? 1 : 0;
            break;
        }
    }
    save_navigation_state($nav_items);
    header('Location: /admin/navigation.php?msg=status_updated');
    exit;
}

// Top level items for dropdown
$top_level = array_filter($nav_items, fn($it) => empty($it['parent_id']));

$page_slug = 'admin-navigation';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Navigation Menu Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Manage top-level header links, dropdown menus, sort ordering, and active visibility.</p>
  </div>
  <a href="/" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Preview Main Site &nearr;</a>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Navigation changes saved and updated.
  </div>
<?php elseif ($msg === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Navigation link deleted.
  </div>
<?php elseif ($msg === 'status_updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Visibility status toggled.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
  
  <!-- Left Column: Navigation Items Tree Table -->
  <div class="card" style="border-left: none; padding: 2rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-top: 0; margin-bottom: 1.25rem;">
      Site Navigation Links (<?php echo count($nav_items); ?> Total)
    </h3>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
        <thead>
          <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
            <th style="padding: 0.75rem 1rem;">Menu Label</th>
            <th style="padding: 0.75rem 1rem;">Destination URL</th>
            <th style="padding: 0.75rem 1rem; width: 60px;">Order</th>
            <th style="padding: 0.75rem 1rem;">Status</th>
            <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($top_level as $top): ?>
            <!-- Top Level Item -->
            <tr style="border-bottom: 1px solid var(--color-border); background: #fafbfc;">
              <td style="padding: 0.75rem 1rem; font-weight: 700; color: var(--color-navy);">
                📁 <?php echo h($top['label']); ?>
              </td>
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);">
                <code><?php echo h($top['url']); ?></code>
              </td>
              <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo (int)($top['sort_order'] ?? 0); ?></td>
              <td style="padding: 0.75rem 1rem;">
                <a href="/admin/navigation.php?action=toggle&id=<?php echo $top['id']; ?>" style="text-decoration: none;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.72rem; border-radius: 4px; font-weight: 700; background: <?php echo !empty($top['is_active']) ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?php echo !empty($top['is_active']) ? '#03543F' : '#9B1C1C'; ?>;">
                    <?php echo !empty($top['is_active']) ? 'Active' : 'Hidden'; ?>
                  </span>
                </a>
              </td>
              <td style="padding: 0.75rem 1rem; text-align: right;">
                <a href="/admin/navigation.php?action=delete&id=<?php echo $top['id']; ?>" onclick="return confirm('Delete this link and its sub-links?');" style="color: #EF4444; font-weight: 600; text-decoration: none;">Delete</a>
              </td>
            </tr>

            <!-- Children Sub-links -->
            <?php 
              $children = array_filter($nav_items, fn($c) => ($c['parent_id'] ?? null) == $top['id']);
              foreach ($children as $sub):
            ?>
              <tr style="border-bottom: 1px solid var(--color-border);">
                <td style="padding: 0.6rem 1rem 0.6rem 2.5rem; color: var(--color-text);">
                  ↳ <span style="font-weight: 600;"><?php echo h($sub['label']); ?></span>
                </td>
                <td style="padding: 0.6rem 1rem; color: var(--color-muted);">
                  <code><?php echo h($sub['url']); ?></code>
                </td>
                <td style="padding: 0.6rem 1rem;"><?php echo (int)($sub['sort_order'] ?? 0); ?></td>
                <td style="padding: 0.6rem 1rem;">
                  <a href="/admin/navigation.php?action=toggle&id=<?php echo $sub['id']; ?>" style="text-decoration: none;">
                    <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.72rem; border-radius: 4px; font-weight: 700; background: <?php echo !empty($sub['is_active']) ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?php echo !empty($sub['is_active']) ? '#03543F' : '#9B1C1C'; ?>;">
                      <?php echo !empty($sub['is_active']) ? 'Active' : 'Hidden'; ?>
                    </span>
                  </a>
                </td>
                <td style="padding: 0.6rem 1rem; text-align: right;">
                  <a href="/admin/navigation.php?action=delete&id=<?php echo $sub['id']; ?>" onclick="return confirm('Delete this sub-item?');" style="color: #EF4444; font-weight: 600; text-decoration: none;">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Right Column: Add / Edit Link Form -->
  <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
    <h3 style="color: var(--color-navy); font-size: 1.15rem; margin-top: 0; margin-bottom: 1.25rem;">Add Navigation Item</h3>
    <form method="POST" action="/admin/navigation.php">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="save_item" value="1">

      <div class="admin-form-group">
        <label class="admin-label">Menu Label *</label>
        <input type="text" name="label" required placeholder="e.g. Virtual Classroom" class="admin-input">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Destination URL *</label>
        <input type="text" name="url" required placeholder="e.g. /beyond/virtual-classroom" class="admin-input">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Parent Menu (Leave empty for Top Level)</label>
        <select name="parent_id" class="admin-input" style="height: 38px;">
          <option value="">None (Top-Level Menu)</option>
          <?php foreach ($top_level as $p): ?>
            <option value="<?php echo $p['id']; ?>"><?php echo h($p['label']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div class="admin-form-group">
          <label class="admin-label">Sort Order</label>
          <input type="number" name="sort_order" value="1" min="1" class="admin-input">
        </div>
        <div class="admin-form-group" style="padding-top: 1.5rem;">
          <label style="font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">
            <input type="checkbox" name="is_active" value="1" checked> Active / Visible
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; margin-top: 0.5rem;">Add Menu Link</button>
    </form>
  </div>

</div>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
