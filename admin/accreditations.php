<?php
// Zuvio Global School - Admin Accreditations & Affiliations Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$use_mock = !$db;
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = $_GET['msg'] ?? '';
$error = '';

if ($db) {
    try {
        $db->query("SELECT 1 FROM `accreditations` LIMIT 1");
    } catch (Exception $e) {
        $use_mock = true;
    }
}

// Prepopulate mock accreditations if empty
if ($use_mock) {
    if (!isset($_SESSION['mock_accreditations'])) {
        $_SESSION['mock_accreditations'] = [
            1 => [
                'id' => 1,
                'name' => 'IAO — International Accreditation Organization',
                'subtitle' => 'Committed to Global Quality Standards',
                'description' => 'Zuvio’s association with IAO reflects our focus on quality, continuous improvement and internationally benchmarked educational practices. IAO provides quality-assurance and accreditation services to educational institutions, including online and distance-learning providers.',
                'logo_url' => '/assets/images/iao-logo.png',
                'certificate_url' => 'https://www.iao.org/India-Delhi/Zuvio-Global-School',
                'sort_order' => 1,
                'is_active' => 1
            ],
            2 => [
                'id' => 2,
                'name' => 'Oxford Quality',
                'subtitle' => 'Powered by the Excellence of Oxford University Press',
                'description' => 'As part of the Oxford Quality community, Zuvio strengthens learning through high-quality educational resources, teacher professional development and globally connected learning opportunities.',
                'logo_url' => '/assets/images/oxford-logo.png',
                'certificate_url' => 'https://india.oup.com/',
                'sort_order' => 2,
                'is_active' => 1
            ],
            3 => [
                'id' => 3,
                'name' => 'ISSO — International Schools Sports Organisation',
                'subtitle' => 'Building Champions Beyond the Classroom',
                'description' => 'Through its association with ISSO, Zuvio aims to provide learners access to a structured school-sports ecosystem that promotes competition, teamwork, discipline, resilience and sporting excellence.',
                'logo_url' => '/assets/images/isso-logo.png',
                'certificate_url' => 'https://www.issosports.org/',
                'sort_order' => 3,
                'is_active' => 1
            ]
        ];
    }
}

// 1. Action: Toggle Active
if ($action === 'toggle' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("UPDATE `accreditations` SET `is_active` = NOT `is_active` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        if (isset($_SESSION['mock_accreditations'][$id])) {
            $_SESSION['mock_accreditations'][$id]['is_active'] = $_SESSION['mock_accreditations'][$id]['is_active'] ? 0 : 1;
        }
    }
    header('Location: /admin/accreditations.php?msg=updated');
    exit;
}

// 2. Action: Delete
if ($action === 'delete' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("DELETE FROM `accreditations` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        unset($_SESSION['mock_accreditations'][$id]);
    }
    header('Location: /admin/accreditations.php?msg=deleted');
    exit;
}

// 3. Action: Save (Create / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'edit'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $logo_url = trim($_POST['logo_url'] ?? '');
        $certificate_url = trim($_POST['certificate_url'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name)) {
            $error = 'Accreditation name is required.';
        } else {
            if ($action === 'edit' && $id > 0) {
                if (!$use_mock && $db) {
                    $stmt = $db->prepare("
                        UPDATE `accreditations` 
                        SET `name` = ?, `subtitle` = ?, `description` = ?, `logo_url` = ?, `certificate_url` = ?, `sort_order` = ?, `is_active` = ? 
                        WHERE `id` = ?
                    ");
                    $stmt->execute([$name, $subtitle, $description, $logo_url, $certificate_url, $sort_order, $is_active, $id]);
                } else {
                    $_SESSION['mock_accreditations'][$id] = [
                        'id' => $id,
                        'name' => $name,
                        'subtitle' => $subtitle,
                        'description' => $description,
                        'logo_url' => $logo_url,
                        'certificate_url' => $certificate_url,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active
                    ];
                }
                header('Location: /admin/accreditations.php?msg=saved');
                exit;
            } elseif ($action === 'create') {
                if (!$use_mock && $db) {
                    $stmt = $db->prepare("
                        INSERT INTO `accreditations` (`name`, `subtitle`, `description`, `logo_url`, `certificate_url`, `sort_order`, `is_active`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$name, $subtitle, $description, $logo_url, $certificate_url, $sort_order, $is_active]);
                } else {
                    $new_id = empty($_SESSION['mock_accreditations']) ? 1 : max(array_keys($_SESSION['mock_accreditations'])) + 1;
                    $_SESSION['mock_accreditations'][$new_id] = [
                        'id' => $new_id,
                        'name' => $name,
                        'subtitle' => $subtitle,
                        'description' => $description,
                        'logo_url' => $logo_url,
                        'certificate_url' => $certificate_url,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active
                    ];
                }
                header('Location: /admin/accreditations.php?msg=created');
                exit;
            }
        }
    }
}

// Fetch accreditations
$acc_list = [];
if (!$use_mock && $db) {
    try {
        $acc_list = $db->query("SELECT * FROM `accreditations` ORDER BY `sort_order` ASC, `id` ASC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Database query failed.';
    }
} else {
    $acc_list = array_values($_SESSION['mock_accreditations']);
    usort($acc_list, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
}

// Edit item data
$edit_item = null;
if ($action === 'edit' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("SELECT * FROM `accreditations` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $edit_item = $stmt->fetch();
    } else {
        $edit_item = $_SESSION['mock_accreditations'][$id] ?? null;
    }
}

$page_slug = 'admin-accreditations';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Affiliations & Accreditations CMS
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage official affiliations, credentials, and verification certificates (relocated from the old header button into this official section).
    </p>
  </div>
  <div>
    <?php if ($action === 'list'): ?>
      <a href="/admin/accreditations.php?action=create" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
        + Add New Accreditation
      </a>
    <?php else: ?>
      <a href="/admin/accreditations.php" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
        &larr; Back to Accreditations List
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($msg === 'saved' || $msg === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Accreditation updated successfully.
  </div>
<?php elseif ($msg === 'created'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    New accreditation created successfully.
  </div>
<?php elseif ($msg === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-danger, #d9534f); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Accreditation removed.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<?php if ($action === 'create' || ($action === 'edit' && $edit_item)): ?>
  <!-- CREATE / EDIT FORM -->
  <div class="card" style="padding: 2rem; max-width: 800px;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1.5rem; font-size: 1.2rem;">
      <?php echo $action === 'create' ? 'Add New Accreditation' : 'Edit Accreditation: #' . $edit_item['id']; ?>
    </h3>
    
    <form method="POST" action="/admin/accreditations.php?action=<?php echo $action; ?><?php echo $id ? '&id=' . $id : ''; ?>">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Authority / Body Name</label>
          <input type="text" name="name" required value="<?php echo h($edit_item['name'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="e.g. Ministry of Corporate Affairs (MCA)">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Subtitle / Framework</label>
          <input type="text" name="subtitle" value="<?php echo h($edit_item['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="e.g. Government of India Registration">
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
        <textarea name="description" rows="4" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($edit_item['description'] ?? ''); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Logo Image URL</label>
          <input type="text" name="logo_url" value="<?php echo h($edit_item['logo_url'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="/assets/images/reference_docx/image8.png">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Certificate Document URL (PDF/Image)</label>
          <input type="text" name="certificate_url" value="<?php echo h($edit_item['certificate_url'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="/assets/docs/MCA_Certificate.pdf">
          <small style="color: var(--color-muted); font-size: 0.7rem;">Leave empty if no downloadable certificate.</small>
        </div>
      </div>

      <div style="display: flex; gap: 2rem; align-items: center; margin-bottom: 1.5rem; background: var(--color-surface); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.35rem; font-size: 0.85rem;">Display Order</label>
          <input type="number" name="sort_order" value="<?php echo (int)($edit_item['sort_order'] ?? 1); ?>" style="width: 100px; padding: 0.4rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.25rem;">
          <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo !isset($edit_item['is_active']) || !empty($edit_item['is_active']) ? 'checked' : ''; ?>>
          <label for="is_active" style="font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">Published / Visible</label>
        </div>
      </div>

      <div style="display: flex; gap: 1rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem;">Save Accreditation</button>
        <a href="/admin/accreditations.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">Cancel</a>
      </div>
    </form>
  </div>

<?php else: ?>
  <!-- LIST TABLE -->
  <div class="card" style="padding: 1.5rem;">
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
          <tr style="background-color: var(--color-surface); text-align: left; border-bottom: 2px solid var(--color-border);">
            <th style="padding: 0.75rem; width: 60px; color: var(--color-navy);">Order</th>
            <th style="padding: 0.75rem; width: 80px; text-align: center; color: var(--color-navy);">Logo</th>
            <th style="padding: 0.75rem; width: 220px; color: var(--color-navy);">Authority / Name</th>
            <th style="padding: 0.75rem; color: var(--color-navy);">Description</th>
            <th style="padding: 0.75rem; width: 120px; text-align: center; color: var(--color-navy);">Certificate</th>
            <th style="padding: 0.75rem; width: 90px; text-align: center; color: var(--color-navy);">Status</th>
            <th style="padding: 0.75rem; width: 130px; text-align: right; color: var(--color-navy);">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($acc_list)): ?>
            <tr>
              <td colspan="7" style="padding: 2rem; text-align: center; color: var(--color-muted);">No accreditations found.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($acc_list as $a): ?>
              <tr style="border-bottom: 1px solid var(--color-border);">
                <td style="padding: 0.75rem; font-weight: 700; color: var(--color-muted);">
                  #<?php echo (int)$a['sort_order']; ?>
                </td>
                <td style="padding: 0.75rem; text-align: center;">
                  <?php if (!empty($a['logo_url'])): ?>
                    <img src="<?php echo h($a['logo_url']); ?>" alt="Logo" style="height: 36px; max-width: 60px; object-fit: contain; border-radius: 4px; background: #fff; padding: 2px; border: 1px solid var(--color-border);">
                  <?php else: ?>
                    <span style="color: var(--color-muted); font-size: 0.7rem;">None</span>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem;">
                  <div style="font-weight: 700; color: var(--color-navy);"><?php echo h($a['name']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--color-muted);"><?php echo h($a['subtitle'] ?? ''); ?></div>
                </td>
                <td style="padding: 0.75rem; color: #4b5563; font-size: 0.8rem; line-height: 1.4;">
                  <?php echo h($a['description'] ?? ''); ?>
                </td>
                <td style="padding: 0.75rem; text-align: center;">
                  <?php if (!empty($a['certificate_url'])): ?>
                    <a href="<?php echo h($a['certificate_url']); ?>" target="_blank" style="display: inline-block; background: #eff6ff; color: #1d4ed8; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-decoration: none;">
                      📄 View Doc
                    </a>
                  <?php else: ?>
                    <span style="color: var(--color-muted); font-size: 0.7rem;">None</span>
                  <?php endif; ?>
                </td>
                <td style="padding: 0.75rem; text-align: center;">
                  <a href="/admin/accreditations.php?action=toggle&id=<?php echo $a['id']; ?>" style="text-decoration: none;">
                    <?php if (!empty($a['is_active'])): ?>
                      <span style="background: #e8f5e9; color: #2e7d32; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Active</span>
                    <?php else: ?>
                      <span style="background: #ffebee; color: #c62828; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Inactive</span>
                    <?php endif; ?>
                  </a>
                </td>
                <td style="padding: 0.75rem; text-align: right; white-space: nowrap;">
                  <a href="/admin/accreditations.php?action=edit&id=<?php echo $a['id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; margin-right: 0.35rem;">
                    Edit
                  </a>
                  <a href="/admin/accreditations.php?action=delete&id=<?php echo $a['id']; ?>" onclick="return confirm('Are you sure you want to delete this accreditation?');" style="color: #c62828; font-size: 0.75rem; text-decoration: none; padding: 0.3rem;">
                    Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
