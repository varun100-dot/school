<?php
// Zuvio Global School - Admin Hero Slides Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_admin_role();

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = '';
$error = '';

// Handle Save changes (Update only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit' && $id > 0) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $primary_cta_text = trim($_POST['primary_cta_text'] ?? '');
        $primary_cta_url = trim($_POST['primary_cta_url'] ?? '');
        $secondary_cta_text = trim($_POST['secondary_cta_text'] ?? '');
        $secondary_cta_url = trim($_POST['secondary_cta_url'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($title) || empty($description)) {
            $error = 'Title and description are required.';
        } else {
            try {
                // Image field update (use text input for source files)
                $image = trim($_POST['image'] ?? '');
                
                // Allow optional local file uploads if provided
                if (isset($_FILES['slide_image_file']) && $_FILES['slide_image_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['slide_image_file'];
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $new_name = 'hero_' . bin2hex(random_bytes(4)) . '_' . time() . '.' . $ext;
                    $upload_dir = dirname(__FILE__) . '/../uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
                        $image = '/uploads/' . $new_name;
                    }
                }
                
                $stmt = $db->prepare("
                    UPDATE `hero_slides` 
                    SET `title` = ?, `subtitle` = ?, `description` = ?, `image` = ?, `primary_cta_text` = ?, `primary_cta_url` = ?, `secondary_cta_text` = ?, `secondary_cta_url` = ?, `sort_order` = ?, `is_active` = ?
                    WHERE `id` = ?
                ");
                $stmt->execute([
                    $title, $subtitle, $description, $image, $primary_cta_text, $primary_cta_url, $secondary_cta_text, $secondary_cta_url, $sort_order, $is_active, $id
                ]);
                
                header('Location: /admin/hero?msg=updated');
                exit;
            } catch (Exception $e) {
                $error = 'Transaction Error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch single slide details
$item = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item) {
            header('Location: /admin/hero?error=notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = "Error loading slide details.";
    }
}

// Listing slides
$slides = [];
if ($action === 'list') {
    try {
        $slides = $db->query("SELECT * FROM `hero_slides` ORDER BY `sort_order` ASC")->fetchAll();
    } catch (Exception $e) {
        $error = "Database queries failed.";
    }
}

$page_slug = 'admin-hero';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Homepage Hero Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Modify front banners, titles, descriptions, and CTA targets.</p>
  </div>
  <?php if ($action !== 'list'): ?>
    <a href="/admin/hero" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to List</a>
  <?php endif; ?>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Slide updated successfully.
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
    <?php if (!empty($slides)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Preview</th>
              <th style="padding: 0.75rem 1rem;">Title</th>
              <th style="padding: 0.75rem 1rem;">Eyebrow / Subtitle</th>
              <th style="padding: 0.75rem 1rem;">Order</th>
              <th style="padding: 0.75rem 1rem;">Status</th>
              <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($slides as $slide): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem;">
                  <div style="width: 80px; height: 48px; background-color: var(--color-navy); background-image: url('<?php echo h($slide['image']); ?>'); background-size: cover; background-position: center; border-radius: var(--radius-sm);"></div>
                </td>
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($slide['title']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($slide['subtitle']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo $slide['sort_order']; ?></td>
                <td style="padding: 0.75rem 1rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 700; background-color: <?php echo $slide['is_active'] ? '#D1FAE5' : '#F3F4F6'; ?>; color: <?php echo $slide['is_active'] ? '#065F46' : '#374151'; ?>;">
                    <?php echo $slide['is_active'] ? 'ACTIVE' : 'INACTIVE'; ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/hero?action=edit&id=<?php echo $slide['id']; ?>" style="color: var(--color-gold); font-weight: 600;">Edit Slide</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center;">No slides configured in the database.</p>
    <?php endif; ?>
  </div>

<!-- EDIT -->
<?php elseif ($action === 'edit' && $item): ?>
  <div class="card" style="border-left: none; padding: 2.5rem;">
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

      <div class="admin-form-group">
        <label class="admin-label">Main Slide Title *</label>
        <input type="text" name="title" required class="admin-input" value="<?php echo h($item['title']); ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Eyebrow / Subtitle (Small header text above title)</label>
        <input type="text" name="subtitle" class="admin-input" value="<?php echo h($item['subtitle']); ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Description / Supporting Paragraph *</label>
        <textarea name="description" required class="admin-input" rows="3"><?php echo h($item['description']); ?></textarea>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Primary CTA Button Text</label>
          <input type="text" name="primary_cta_text" class="admin-input" value="<?php echo h($item['primary_cta_text']); ?>">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Primary CTA Button Link Target</label>
          <input type="text" name="primary_cta_url" class="admin-input" value="<?php echo h($item['primary_cta_url']); ?>">
        </div>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Secondary CTA Button Text</label>
          <input type="text" name="secondary_cta_text" class="admin-input" value="<?php echo h($item['secondary_cta_text']); ?>">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Secondary CTA Button Link Target</label>
          <input type="text" name="secondary_cta_url" class="admin-input" value="<?php echo h($item['secondary_cta_url']); ?>">
        </div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Slide Image Path / URL</label>
        <input type="text" name="image" class="admin-input" value="<?php echo h($item['image']); ?>" style="margin-bottom: 0.5rem;">
        <label class="admin-label" style="font-weight: normal; font-size: 0.75rem;">Or upload a replacement image file (JPG/PNG/WEBP):</label>
        <input type="file" name="slide_image_file" class="admin-input">
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Sort Order</label>
          <input type="number" name="sort_order" class="admin-input" value="<?php echo (int)$item['sort_order']; ?>">
        </div>
        <div class="admin-form-group" style="display: flex; align-items: center; margin-top: 1.75rem;">
          <label style="font-size: 0.9rem; font-weight: 600; color: var(--color-navy); display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
            <input type="checkbox" name="is_active" value="1" <?php echo $item['is_active'] ? 'checked' : ''; ?>>
            Active (Display slide in carousel)
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2.5rem;">Save Changes</button>
      <a href="/admin/hero" class="btn btn-outline" style="padding: 0.8rem 2rem; margin-left: 0.75rem;">Cancel</a>
    </form>
  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
