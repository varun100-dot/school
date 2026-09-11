<?php
// Zuvio Global School - Admin Testimonials Manager
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
        $db->query("SELECT 1 FROM `testimonials` LIMIT 1");
    } catch (Exception $e) {
        $use_mock = true;
    }
}

// Prepopulate mock testimonials if empty
if ($use_mock) {
    if (!isset($_SESSION['mock_testimonials'])) {
        $_SESSION['mock_testimonials'] = [
            1 => [
                'id' => 1,
                'parent_name' => 'Meera & Rajesh Sharma',
                'student_name' => 'Aarav Sharma',
                'grade_level' => 'Grade 4',
                'rating' => 5,
                'testimonial' => 'Enrolling Aarav in Zuvio has completely transformed his excitement for school. The teachers are empathetic, the 1:15 ratio means he receives genuine attention, and his coding and communication skills have soared.',
                'image_path' => '/assets/images/testimonial_parent1.webp',
                'sort_order' => 1,
                'is_active' => 1
            ],
            2 => [
                'id' => 2,
                'parent_name' => 'Dr. Ananya Iyer',
                'student_name' => 'Diya Iyer',
                'grade_level' => 'Grade 6',
                'rating' => 5,
                'testimonial' => 'As a traveling professional family, Zuvio gave us stability without compromising academic rigor. The Oxford thematic curriculum is outstanding, and the Friday club activities keep Diya deeply engaged.',
                'image_path' => '/assets/images/testimonial_parent2.webp',
                'sort_order' => 2,
                'is_active' => 1
            ],
            3 => [
                'id' => 3,
                'parent_name' => 'Vikram Singhania (Dubai)',
                'student_name' => 'Rohan Singhania',
                'grade_level' => 'Grade 7',
                'rating' => 5,
                'testimonial' => 'Finding a school aligned with CBSE and NEP 2020 while living overseas was a challenge until we found Zuvio. The live sessions and real-time parent updates give us total peace of mind.',
                'image_path' => '/assets/images/testimonial_parent3.webp',
                'sort_order' => 3,
                'is_active' => 1
            ]
        ];
    }
}

// 1. Action: Toggle Active
if ($action === 'toggle' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("UPDATE `testimonials` SET `is_active` = NOT `is_active` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        if (isset($_SESSION['mock_testimonials'][$id])) {
            $_SESSION['mock_testimonials'][$id]['is_active'] = $_SESSION['mock_testimonials'][$id]['is_active'] ? 0 : 1;
        }
    }
    header('Location: /admin/testimonials.php?msg=updated');
    exit;
}

// 2. Action: Delete
if ($action === 'delete' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("DELETE FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        unset($_SESSION['mock_testimonials'][$id]);
    }
    header('Location: /admin/testimonials.php?msg=deleted');
    exit;
}

// 3. Action: Save (Create / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'edit'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $parent_name = trim($_POST['parent_name'] ?? '');
        $student_name = trim($_POST['student_name'] ?? '');
        $grade_level = trim($_POST['grade_level'] ?? '');
        $testimonial = trim($_POST['testimonial'] ?? '');
        $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
        $image_path = trim($_POST['image_path'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($parent_name) || empty($testimonial)) {
            $error = 'Parent Name and Review text are required.';
        } else {
            if ($action === 'edit' && $id > 0) {
                if (!$use_mock && $db) {
                    $stmt = $db->prepare("
                        UPDATE `testimonials` 
                        SET `parent_name` = ?, `student_name` = ?, `grade_level` = ?, `testimonial` = ?, `rating` = ?, `image_path` = ?, `sort_order` = ?, `is_active` = ? 
                        WHERE `id` = ?
                    ");
                    $stmt->execute([$parent_name, $student_name, $grade_level, $testimonial, $rating, $image_path, $sort_order, $is_active, $id]);
                } else {
                    $_SESSION['mock_testimonials'][$id] = [
                        'id' => $id,
                        'parent_name' => $parent_name,
                        'student_name' => $student_name,
                        'grade_level' => $grade_level,
                        'testimonial' => $testimonial,
                        'rating' => $rating,
                        'image_path' => $image_path,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active
                    ];
                }
                header('Location: /admin/testimonials.php?msg=saved');
                exit;
            } elseif ($action === 'create') {
                if (!$use_mock && $db) {
                    $stmt = $db->prepare("
                        INSERT INTO `testimonials` (`parent_name`, `student_name`, `grade_level`, `testimonial`, `rating`, `image_path`, `sort_order`, `is_active`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$parent_name, $student_name, $grade_level, $testimonial, $rating, $image_path, $sort_order, $is_active]);
                } else {
                    $new_id = empty($_SESSION['mock_testimonials']) ? 1 : max(array_keys($_SESSION['mock_testimonials'])) + 1;
                    $_SESSION['mock_testimonials'][$new_id] = [
                        'id' => $new_id,
                        'parent_name' => $parent_name,
                        'student_name' => $student_name,
                        'grade_level' => $grade_level,
                        'testimonial' => $testimonial,
                        'rating' => $rating,
                        'image_path' => $image_path,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active
                    ];
                }
                header('Location: /admin/testimonials.php?msg=created');
                exit;
            }
        }
    }
}

// Fetch testimonials
$test_list = [];
if (!$use_mock && $db) {
    try {
        $test_list = $db->query("SELECT * FROM `testimonials` ORDER BY `sort_order` ASC, `id` ASC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Database query failed.';
    }
} else {
    $test_list = array_values($_SESSION['mock_testimonials']);
    usort($test_list, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
}

// Edit item data
$edit_item = null;
if ($action === 'edit' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("SELECT * FROM `testimonials` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $edit_item = $stmt->fetch();
    } else {
        $edit_item = $_SESSION['mock_testimonials'][$id] ?? null;
    }
}

$page_slug = 'admin-testimonials';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Parent Testimonials & Reviews
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Manage parent reviews, star ratings, and student details displayed on the homepage and review sections.
    </p>
  </div>
  <div>
    <?php if ($action === 'list'): ?>
      <a href="/admin/testimonials.php?action=create" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
        + Add New Testimonial
      </a>
    <?php else: ?>
      <a href="/admin/testimonials.php" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
        &larr; Back to Testimonials List
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($msg === 'saved' || $msg === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Testimonial updated successfully.
  </div>
<?php elseif ($msg === 'created'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Testimonial added successfully.
  </div>
<?php elseif ($msg === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-danger, #d9534f); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Testimonial removed.
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
      <?php echo $action === 'create' ? 'Add New Testimonial' : 'Edit Testimonial: #' . $edit_item['id']; ?>
    </h3>
    
    <form method="POST" action="/admin/testimonials.php?action=<?php echo $action; ?><?php echo $id ? '&id=' . $id : ''; ?>">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Parent Name(s)</label>
          <input type="text" name="parent_name" required value="<?php echo h($edit_item['parent_name'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="e.g. Meera & Rajesh Sharma">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Student Name & Location</label>
          <input type="text" name="student_name" value="<?php echo h($edit_item['student_name'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="e.g. Aarav Sharma (Bengaluru)">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Grade / Stage</label>
          <input type="text" name="grade_level" value="<?php echo h($edit_item['grade_level'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="e.g. Grade 4">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Rating (Stars 1 - 5)</label>
          <select name="rating" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            <option value="5" <?php echo ($edit_item['rating'] ?? 5) == 5 ? 'selected' : ''; ?>>★★★★★ (5 Stars)</option>
            <option value="4" <?php echo ($edit_item['rating'] ?? 5) == 4 ? 'selected' : ''; ?>>★★★★☆ (4 Stars)</option>
            <option value="3" <?php echo ($edit_item['rating'] ?? 5) == 3 ? 'selected' : ''; ?>>★★★☆☆ (3 Stars)</option>
          </select>
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Review / Testimonial Content</label>
        <textarea name="testimonial" required rows="5" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($edit_item['testimonial'] ?? ''); ?></textarea>
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Photo / Avatar URL (optional)</label>
        <input type="text" name="image_path" value="<?php echo h($edit_item['image_path'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="/assets/images/testimonial_parent1.webp">
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
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem;">Save Testimonial</button>
        <a href="/admin/testimonials.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">Cancel</a>
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
            <th style="padding: 0.75rem; width: 220px; color: var(--color-navy);">Parent & Grade</th>
            <th style="padding: 0.75rem; color: var(--color-navy);">Testimonial</th>
            <th style="padding: 0.75rem; width: 90px; text-align: center; color: var(--color-navy);">Rating</th>
            <th style="padding: 0.75rem; width: 90px; text-align: center; color: var(--color-navy);">Status</th>
            <th style="padding: 0.75rem; width: 130px; text-align: right; color: var(--color-navy);">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($test_list)): ?>
            <tr>
              <td colspan="6" style="padding: 2rem; text-align: center; color: var(--color-muted);">No testimonials found.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($test_list as $t): ?>
              <tr style="border-bottom: 1px solid var(--color-border);">
                <td style="padding: 0.75rem; font-weight: 700; color: var(--color-muted);">
                  #<?php echo (int)$t['sort_order']; ?>
                </td>
                <td style="padding: 0.75rem;">
                  <div style="font-weight: 700; color: var(--color-navy);"><?php echo h($t['parent_name']); ?></div>
                  <div style="font-size: 0.75rem; color: var(--color-muted);">
                    <?php echo h($t['student_name']); ?> <?php echo !empty($t['grade_level']) ? '• ' . h($t['grade_level']) : ''; ?>
                  </div>
                </td>
                <td style="padding: 0.75rem;">
                  <div style="font-style: italic; color: #4b5563; line-height: 1.4;">
                    "<?php echo h(substr($t['testimonial'], 0, 160)); ?>..."
                  </div>
                </td>
                <td style="padding: 0.75rem; text-align: center; color: #f59e0b; font-size: 0.9rem;">
                  <?php echo str_repeat('★', (int)$t['rating']); ?>
                </td>
                <td style="padding: 0.75rem; text-align: center;">
                  <a href="/admin/testimonials.php?action=toggle&id=<?php echo $t['id']; ?>" style="text-decoration: none;">
                    <?php if (!empty($t['is_active'])): ?>
                      <span style="background: #e8f5e9; color: #2e7d32; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Active</span>
                    <?php else: ?>
                      <span style="background: #ffebee; color: #c62828; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Inactive</span>
                    <?php endif; ?>
                  </a>
                </td>
                <td style="padding: 0.75rem; text-align: right; white-space: nowrap;">
                  <a href="/admin/testimonials.php?action=edit&id=<?php echo $t['id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; margin-right: 0.35rem;">
                    Edit
                  </a>
                  <a href="/admin/testimonials.php?action=delete&id=<?php echo $t['id']; ?>" onclick="return confirm('Are you sure you want to delete this testimonial?');" style="color: #c62828; font-size: 0.75rem; text-decoration: none; padding: 0.3rem;">
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
