<?php
// Zuvio Global School - Dedicated Sliding Strip CMS
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('settings.view');

$msg = $_GET['msg'] ?? '';
$error = '';

// Load persistent sliding strip data
$db_strip = get_json_setting('cms_sliding_strip', []);
if (!isset($_SESSION['mock_sliding_strip']) || !empty($db_strip)) {
    $_SESSION['mock_sliding_strip'] = !empty($db_strip) ? $db_strip : [];
}
$strip_cms = &$_SESSION['mock_sliding_strip'];

// Standard default items
$default_items = [
    [
        'id' => 1,
        'badge' => 'Nursery to Grade 8th',
        'title' => '100% Online Schooling',
        'link' => '/curriculum',
        'sort_order' => 1,
        'is_active' => 1
    ],
    [
        'id' => 2,
        'badge' => '',
        'title' => 'CBSE Mapped Curriculum',
        'link' => '/curriculum',
        'sort_order' => 2,
        'is_active' => 1
    ],
    [
        'id' => 3,
        'badge' => '',
        'title' => 'Inclusive Learning & SEN Support',
        'link' => '/special-education',
        'sort_order' => 3,
        'is_active' => 1
    ],
    [
        'id' => 4,
        'badge' => '',
        'title' => 'Live Small-Group Interactive Classes',
        'link' => '/technology',
        'sort_order' => 4,
        'is_active' => 1
    ],
    [
        'id' => 5,
        'badge' => '',
        'title' => 'Oxford Quality Curriculum Partner',
        'link' => '/affiliations-accreditations',
        'sort_order' => 5,
        'is_active' => 1
    ]
];

// Initialize defaults if empty
if (!isset($strip_cms['enabled'])) {
    $strip_cms['enabled'] = 1;
}
if (empty($strip_cms['items']) || !is_array($strip_cms['items'])) {
    $strip_cms['items'] = $default_items;
}

// -----------------------------------------------------------------------------
// POST HANDLER 1: Save Global Strip Settings (Enable/Disable)
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_strip_settings') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please refresh.';
    } else {
        $strip_cms['enabled'] = isset($_POST['enabled']) ? 1 : 0;
        set_json_setting('cms_sliding_strip', $strip_cms, 'Sliding Strip Ticker Settings');
        header('Location: /admin/sliding-strip.php?msg=settings_saved');
        exit;
    }
}

// -----------------------------------------------------------------------------
// POST HANDLER 2: Save Individual Strip Item
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_individual_item') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed.';
    } else {
        $item_id = (int)($_POST['item_id'] ?? 0);
        $pending_action = trim($_POST['pending_action'] ?? 'save');

        if ($pending_action === 'delete') {
            // Commit deletion only when user confirmed & saved
            $strip_cms['items'] = array_values(array_filter($strip_cms['items'], fn($it) => (int)$it['id'] !== $item_id));
            set_json_setting('cms_sliding_strip', $strip_cms, 'Sliding Strip Ticker Settings');
            header('Location: /admin/sliding-strip.php?msg=deleted&id=' . $item_id);
            exit;
        } else {
            // Update item
            $title = trim($_POST['title'] ?? '');
            $badge = trim($_POST['badge'] ?? '');
            $link = trim($_POST['link'] ?? '');
            $sort_order = (int)($_POST['sort_order'] ?? 1);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($title)) {
                $error = 'Text/Content is required for strip item #' . $item_id;
            } else {
                $found = false;
                foreach ($strip_cms['items'] as &$it) {
                    if ((int)$it['id'] === $item_id) {
                        $it['title'] = $title;
                        $it['badge'] = $badge;
                        $it['link'] = $link;
                        $it['sort_order'] = $sort_order;
                        $it['is_active'] = $is_active;
                        $found = true;
                        break;
                    }
                }
                unset($it);

                if (!$found) {
                    $strip_cms['items'][] = [
                        'id' => $item_id,
                        'badge' => $badge,
                        'title' => $title,
                        'link' => $link,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active
                    ];
                }

                // Sort items by sort_order
                usort($strip_cms['items'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
                set_json_setting('cms_sliding_strip', $strip_cms, 'Sliding Strip Ticker Settings');
                header('Location: /admin/sliding-strip.php?msg=saved&id=' . $item_id);
                exit;
            }
        }
    }
}

// -----------------------------------------------------------------------------
// POST HANDLER 3: Add New Strip Item
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_new_item') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $badge = trim($_POST['badge'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? count($strip_cms['items']) + 1);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title)) {
            $error = 'Text/Content is required for the new item.';
        } else {
            $max_id = 0;
            foreach ($strip_cms['items'] as $it) {
                if ((int)($it['id'] ?? 0) > $max_id) {
                    $max_id = (int)$it['id'];
                }
            }
            $new_id = $max_id + 1;

            $strip_cms['items'][] = [
                'id' => $new_id,
                'badge' => $badge,
                'title' => $title,
                'link' => $link,
                'sort_order' => $sort_order,
                'is_active' => $is_active
            ];

            usort($strip_cms['items'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            set_json_setting('cms_sliding_strip', $strip_cms, 'Sliding Strip Ticker Settings');
            header('Location: /admin/sliding-strip.php?msg=added');
            exit;
        }
    }
}

// -----------------------------------------------------------------------------
// GET ACTION: Safe Idempotent Sync of Default Items
// -----------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'sync_defaults') {
    $existing_by_id = [];
    foreach ($strip_cms['items'] as $it) {
        $existing_by_id[(int)$it['id']] = $it;
    }

    foreach ($default_items as $d) {
        $did = (int)$d['id'];
        if (isset($existing_by_id[$did])) {
            $existing_by_id[$did]['title'] = $d['title'];
            $existing_by_id[$did]['badge'] = $d['badge'];
            $existing_by_id[$did]['link'] = $d['link'];
            $existing_by_id[$did]['sort_order'] = $d['sort_order'];
        } else {
            $existing_by_id[$did] = $d;
        }
    }

    $strip_cms['items'] = array_values($existing_by_id);
    usort($strip_cms['items'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
    set_json_setting('cms_sliding_strip', $strip_cms, 'Sliding Strip Ticker Settings');
    header('Location: /admin/sliding-strip.php?msg=synced');
    exit;
}

$page_slug = 'admin-sliding-strip';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="max-width: 1140px; margin: 0 auto;">
  <!-- Header Title -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
        Header Sliding Strip CMS
      </h1>
      <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
        Manage the animated announcement &amp; USP ticker displayed across the top header of every website page.
      </p>
    </div>
    
    <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
      <a href="/admin/sliding-strip.php?action=sync_defaults" onclick="return confirm('Sync standard 5 strip items safely? This operation is idempotent and will NOT create duplicates.');" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 0.9rem; border-color: var(--color-gold); color: var(--color-navy);">
        ⚡ Safe Idempotent Sync
      </a>
      <a href="#new-item-section" class="btn btn-primary" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        + Add New Strip Item
      </a>
      <a href="/" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 0.9rem;">
        View Live Website ↗
      </a>
    </div>
  </div>

  <!-- Status Notification Alerts -->
  <?php if ($msg === 'saved'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Saved!</strong> Strip Item #<?php echo (int)($_GET['id'] ?? 0); ?> has been successfully updated on the live website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php elseif ($msg === 'settings_saved'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Saved:</strong> Sliding strip visibility settings updated.
    </div>
  <?php elseif ($msg === 'deleted'): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Deleted:</strong> Item was removed from the sliding strip.
    </div>
  <?php elseif ($msg === 'added'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Success:</strong> New sliding strip item added.
    </div>
  <?php elseif ($msg === 'synced'): ?>
    <div style="background-color: #EFF6FF; border-left: 4px solid #3B82F6; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #1E40AF; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Idempotent Sync Complete:</strong> 5 standard items synced with zero duplicates.
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Error:</strong> <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <!-- Global Strip Visibility Control Box -->
  <div class="card" style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2rem;">
    <form method="POST" action="/admin/sliding-strip.php" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <input type="hidden" name="action" value="save_strip_settings">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <div>
        <h3 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">Sliding Strip Global Visibility</h3>
        <p style="color: var(--color-muted); font-size: 0.82rem; margin: 0;">Toggle entire ticker strip ON or OFF across the website header.</p>
      </div>
      <div style="display: flex; align-items: center; gap: 1rem;">
        <label style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 700; color: var(--color-navy); cursor: pointer;">
          <input type="checkbox" name="enabled" value="1" <?php echo !empty($strip_cms['enabled']) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
          <span>Strip Enabled on Website</span>
        </label>
        <button type="submit" class="btn btn-primary" style="padding: 0.45rem 1.2rem; font-size: 0.82rem;">Save Toggle</button>
      </div>
    </form>
  </div>

  <!-- Strip Items List with Left Content / Right Actions Layout -->
  <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 3rem;">
    <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0;">
      Current Strip Items (<?php echo count($strip_cms['items']); ?> items)
    </h3>

    <?php foreach ($strip_cms['items'] as $idx => $it): 
      $iid = (int)$it['id'];
      $is_active = !empty($it['is_active']);
    ?>
      <div class="card" id="strip-card-<?php echo $iid; ?>" style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); border-radius: var(--radius-md); padding: 1.5rem; transition: all 0.2s ease;">
        
        <!-- Pending Deletion Alert -->
        <div id="pending-del-<?php echo $iid; ?>" style="display: none; background: #FEE2E2; border: 1.5px solid #EF4444; border-radius: var(--radius-sm); padding: 0.85rem 1.25rem; color: #991B1B; font-size: 0.85rem; margin-bottom: 1rem;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <span>
              <strong>⚠️ PENDING DELETION:</strong> Marked for deletion. Click <strong>"Confirm Deletion &amp; Save"</strong> to delete, or <strong>"Cancel Deletion"</strong> to keep it.
            </span>
            <button type="button" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.25rem 0.65rem; background: #fff; color: #991B1B; border-color: #EF4444;" onclick="cancelStripItemDeletion(<?php echo $iid; ?>)">
              Cancel Deletion
            </button>
          </div>
        </div>

        <form method="POST" action="/admin/sliding-strip.php" id="form-strip-<?php echo $iid; ?>">
          <input type="hidden" name="action" value="save_individual_item">
          <input type="hidden" name="item_id" value="<?php echo $iid; ?>">
          <input type="hidden" name="pending_action" id="pending-action-<?php echo $iid; ?>" value="save">
          <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

          <!-- Left Content / Right Actions Responsive Row -->
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem; flex-wrap: wrap;">
            
            <!-- LEFT CONTENT: Inputs -->
            <div style="flex: 1; min-width: 300px; display: grid; grid-template-columns: 140px 1.5fr 1fr 90px; gap: 1rem;">
              <div>
                <label class="admin-label" style="font-size: 0.75rem;">Badge / Pill (Opt)</label>
                <input type="text" name="badge" value="<?php echo h($it['badge'] ?? ''); ?>" class="admin-input" placeholder="e.g. Nursery–8th">
              </div>

              <div>
                <label class="admin-label" style="font-size: 0.75rem;">Strip Text / Content *</label>
                <input type="text" name="title" value="<?php echo h($it['title']); ?>" required class="admin-input" placeholder="e.g. CBSE Mapped Curriculum">
              </div>

              <div>
                <label class="admin-label" style="font-size: 0.75rem;">Link URL (Optional)</label>
                <input type="text" name="link" value="<?php echo h($it['link'] ?? ''); ?>" class="admin-input" placeholder="/curriculum">
              </div>

              <div>
                <label class="admin-label" style="font-size: 0.75rem;">Order</label>
                <input type="number" name="sort_order" value="<?php echo (int)($it['sort_order'] ?? 1); ?>" min="1" max="99" class="admin-input">
              </div>
            </div>

            <!-- RIGHT ACTIONS: Status Toggle, Delete & Save -->
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-top: 1.4rem;">
              <label style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" <?php echo $is_active ? 'checked' : ''; ?>>
                <span style="<?php echo $is_active ? 'color:#059669;' : 'color:#64748B;'; ?>">
                  <?php echo $is_active ? 'Active' : 'Hidden'; ?>
                </span>
              </label>

              <button type="button" id="mark-del-btn-<?php echo $iid; ?>" class="btn btn-outline" style="color: #DC2626; border-color: #FCA5A5; font-size: 0.8rem; padding: 0.45rem 0.85rem;" onclick="markStripItemForDeletion(<?php echo $iid; ?>)">
                Delete
              </button>

              <button type="submit" id="save-btn-<?php echo $iid; ?>" class="btn btn-primary" style="font-size: 0.82rem; padding: 0.45rem 1.1rem; background: var(--color-navy); border-color: var(--color-navy);">
                Save Item #<?php echo $iid; ?>
              </button>
            </div>

          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- ADD NEW ITEM SECTION -->
  <div class="card" id="new-item-section" style="background: #FFFFFF; border-left: none; border-top: 4px solid var(--color-gold); border-radius: var(--radius-md); padding: 2rem; margin-bottom: 3rem;">
    <h3 style="font-size: 1.15rem; color: var(--color-navy); margin: 0 0 1rem 0; font-family: var(--font-secondary);">
      + Add New Strip / Ticker Item
    </h3>

    <form method="POST" action="/admin/sliding-strip.php">
      <input type="hidden" name="action" value="add_new_item">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

      <div style="display: grid; grid-template-columns: 160px 1.5fr 1fr 100px; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
          <label class="admin-label">Badge / Pill (Optional)</label>
          <input type="text" name="badge" class="admin-input" placeholder="e.g. New">
        </div>
        <div>
          <label class="admin-label">Strip Text / Content *</label>
          <input type="text" name="title" required class="admin-input" placeholder="e.g. Admissions Open 2026-2027">
        </div>
        <div>
          <label class="admin-label">Link URL (Optional)</label>
          <input type="text" name="link" class="admin-input" placeholder="/admissions">
        </div>
        <div>
          <label class="admin-label">Order</label>
          <input type="number" name="sort_order" value="<?php echo count($strip_cms['items']) + 1; ?>" class="admin-input">
        </div>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center;">
        <label style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>Active / Published Immediately</span>
        </label>
        <button type="submit" class="btn btn-primary" style="background: var(--color-gold); border-color: var(--color-gold); color: var(--color-navy-dark); font-weight: 700; padding: 0.6rem 1.75rem;">
          Add Strip Item
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function markStripItemForDeletion(id) {
  const card = document.getElementById('strip-card-' + id);
  const alertBox = document.getElementById('pending-del-' + id);
  const markBtn = document.getElementById('mark-del-btn-' + id);
  const saveBtn = document.getElementById('save-btn-' + id);
  const actionInput = document.getElementById('pending-action-' + id);

  if (card && alertBox && saveBtn && actionInput) {
    card.style.background = '#FFF5F5';
    card.style.borderColor = '#EF4444';
    alertBox.style.display = 'block';
    if (markBtn) markBtn.style.display = 'none';

    saveBtn.innerText = '⚠️ Confirm Deletion & Save';
    saveBtn.style.background = '#DC2626';
    saveBtn.style.borderColor = '#DC2626';
    saveBtn.style.color = '#FFFFFF';

    actionInput.value = 'delete';
  }
}

function cancelStripItemDeletion(id) {
  const card = document.getElementById('strip-card-' + id);
  const alertBox = document.getElementById('pending-del-' + id);
  const markBtn = document.getElementById('mark-del-btn-' + id);
  const saveBtn = document.getElementById('save-btn-' + id);
  const actionInput = document.getElementById('pending-action-' + id);

  if (card && alertBox && saveBtn && actionInput) {
    card.style.background = '#FFFFFF';
    card.style.borderColor = 'rgba(6, 43, 99, 0.14)';
    alertBox.style.display = 'none';
    if (markBtn) markBtn.style.display = 'inline-block';

    saveBtn.innerText = 'Save Item #' + id;
    saveBtn.style.background = 'var(--color-navy)';
    saveBtn.style.borderColor = 'var(--color-navy)';
    saveBtn.style.color = '#FFFFFF';

    actionInput.value = 'save';
  }
}
</script>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
