<?php
// Zuvio Global School - Admin Hero Slides Manager (Phase 3 Upgrade)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('hero.view');

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$version_id = isset($_GET['version_id']) ? (int)$_GET['version_id'] : 0;
$msg = '';
$error = '';

// Helper function to snapshot the current state of a slide
function create_slide_snapshot($slide_id, $change_summary = '') {
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$slide_id]);
        $slide = $stmt->fetch();
        if (!$slide) return false;
        
        $v_stmt = $db->prepare("SELECT COALESCE(MAX(`version_number`), 0) + 1 FROM `hero_slide_versions` WHERE `hero_slide_id` = ?");
        $v_stmt->execute([$slide_id]);
        $next_version = (int)$v_stmt->fetchColumn();
        
        $user_id = $_SESSION['user_id'] ?? null;
        
        $stmt = $db->prepare("
            INSERT INTO `hero_slide_versions` 
            (`hero_slide_id`, `version_number`, `title`, `subtitle`, `description`, `image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`, `created_by`, `change_summary`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $slide_id, $next_version, $slide['title'], $slide['subtitle'], $slide['description'], $slide['image'],
            $slide['primary_cta_text'], $slide['primary_cta_url'], $slide['secondary_cta_text'], $slide['secondary_cta_url'],
            $slide['sort_order'], $slide['is_active'], $user_id, $change_summary
        ]);
    } catch (Exception $e) {
        error_log("[Hero Snapshot Error] " . $e->getMessage());
        return false;
    }
}

// 1. Action Handler: Delete Slide
if ($action === 'delete' && $id > 0) {
    require_permission('hero.delete');
    try {
        $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $old_slide = $stmt->fetch();
        
        if ($old_slide) {
            // Snapshot current state before deleting
            create_slide_snapshot($id, 'Slide deleted snapshot');
            
            $stmt = $db->prepare("DELETE FROM `hero_slides` WHERE `id` = ?");
            $stmt->execute([$id]);
            
            log_audit('HERO_DELETED', 'hero', 'hero_slides', $id, $old_slide, null, "Deleted hero slide {$id}");
            header('Location: /admin/hero?msg=deleted');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Delete Error: ' . $e->getMessage();
    }
}

// 2. Action Handler: Restore Previous Version
if ($action === 'restore' && $version_id > 0) {
    require_permission('hero.restore');
    
    // Check CSRF
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            $error = 'Security check failed. Please submit again.';
        } else {
            try {
                // Fetch version data
                $stmt = $db->prepare("SELECT * FROM `hero_slide_versions` WHERE `id` = ? LIMIT 1");
                $stmt->execute([$version_id]);
                $ver = $stmt->fetch();
                
                if (!$ver) throw new Exception("Version snapshot not found.");
                
                // Fetch current live data for audit log comparison
                $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
                $stmt->execute([$ver['hero_slide_id']]);
                $old_live = $stmt->fetch();
                
                if (!$old_live) {
                    // Slide was deleted, re-insert it
                    $stmt = $db->prepare("
                        INSERT INTO `hero_slides` 
                        (`id`, `title`, `subtitle`, `description`, `image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $ver['hero_slide_id'], $ver['title'], $ver['subtitle'], $ver['description'], $ver['image'],
                        $ver['primary_cta_text'], $ver['primary_cta_url'], $ver['secondary_cta_text'], $ver['secondary_cta_url'],
                        $ver['sort_order'], $ver['is_active']
                    ]);
                } else {
                    // Update live slide
                    $stmt = $db->prepare("
                        UPDATE `hero_slides` 
                        SET `title` = ?, `subtitle` = ?, `description` = ?, `image` = ?, `primary_cta_text` = ?, `primary_cta_url` = ?, `secondary_cta_text` = ?, `secondary_cta_url` = ?, `sort_order` = ?, `is_active` = ?
                        WHERE `id` = ?
                    ");
                    $stmt->execute([
                        $ver['title'], $ver['subtitle'], $ver['description'], $ver['image'],
                        $ver['primary_cta_text'], $ver['primary_cta_url'], $ver['secondary_cta_text'], $ver['secondary_cta_url'],
                        $ver['sort_order'], $ver['is_active'], $ver['hero_slide_id']
                    ]);
                }
                
                // Snapshot the restored state as a new version
                create_slide_snapshot($ver['hero_slide_id'], "Restored from Version " . $ver['version_number']);
                log_audit('HERO_RESTORED', 'hero', 'hero_slides', $ver['hero_slide_id'], $old_live, $ver, "Restored slide to Version " . $ver['version_number']);
                
                header('Location: /admin/hero?msg=restored');
                exit;
            } catch (Exception $e) {
                $error = 'Restore Error: ' . $e->getMessage();
            }
        }
    }
}

// 3. Action Handler: Save slide (Create / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
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
                $image = trim($_POST['image'] ?? '');
                
                // Process image upload if provided
                if (isset($_FILES['slide_image_file']) && $_FILES['slide_image_file']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['slide_image_file'];
                    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                    
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);
                    
                    if (!in_array($mime, $allowed_types)) {
                        throw new Exception("Invalid image format. Only JPG, PNG, and WEBP are allowed.");
                    }
                    
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $new_name = 'hero_' . bin2hex(random_bytes(4)) . '_' . time() . '.' . $ext;
                    $upload_dir = dirname(__FILE__) . '/../uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    
                    if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
                        $image = '/uploads/' . $new_name;
                    }
                }
                
                // Process video upload if provided
                $video = trim($_POST['video'] ?? '');
                $media_type = 'image';
                if (isset($_FILES['slide_video_file']) && $_FILES['slide_video_file']['error'] === UPLOAD_ERR_OK) {
                    $vfile = $_FILES['slide_video_file'];
                    $allowed_video_types = ['video/mp4', 'video/webm', 'video/ogg'];
                    
                    $finfo2 = finfo_open(FILEINFO_MIME_TYPE);
                    $vmime = finfo_file($finfo2, $vfile['tmp_name']);
                    finfo_close($finfo2);
                    
                    if (!in_array($vmime, $allowed_video_types)) {
                        throw new Exception("Invalid video format. Only MP4, WEBM, and OGG are allowed.");
                    }
                    
                    // Limit video to 50MB
                    if ($vfile['size'] > 50 * 1024 * 1024) {
                        throw new Exception("Video file is too large. Maximum size is 50MB.");
                    }
                    
                    $vext = pathinfo($vfile['name'], PATHINFO_EXTENSION);
                    $vnew_name = 'hero_video_' . bin2hex(random_bytes(4)) . '_' . time() . '.' . $vext;
                    $upload_dir = dirname(__FILE__) . '/../uploads/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    
                    if (move_uploaded_file($vfile['tmp_name'], $upload_dir . $vnew_name)) {
                        $video = '/uploads/' . $vnew_name;
                        $media_type = 'video';
                    }
                } elseif (!empty($video)) {
                    // User provided a video URL manually
                    $media_type = 'video';
                }
                
                if ($action === 'add') {
                    require_permission('hero.create');
                    
                    $stmt = $db->prepare("
                        INSERT INTO `hero_slides` 
                        (`title`, `subtitle`, `description`, `image`, `video`, `media_type`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $title, $subtitle, $description, $image, $video ?? '', $media_type ?? 'image', $primary_cta_text, $primary_cta_url, $secondary_cta_text, $secondary_cta_url, $sort_order, $is_active
                    ]);
                    $new_id = $db->lastInsertId();
                    
                    create_slide_snapshot($new_id, 'Slide created initial version');
                    log_audit('HERO_CREATED', 'hero', 'hero_slides', $new_id, null, ['title' => $title], "Hero slide {$new_id} created");
                    
                    header('Location: /admin/hero?msg=added');
                    exit;
                } else {
                    require_permission('hero.edit');
                    
                    $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
                    $stmt->execute([$id]);
                    $old_slide = $stmt->fetch();
                    
                    // Retain existing image/video if no replacement selected
                    if (empty($image) && $old_slide) {
                        $image = $old_slide['image'];
                    }
                    if (empty($video ?? '') && $old_slide) {
                        $video = $old_slide['video'] ?? '';
                        $media_type = $old_slide['media_type'] ?? 'image';
                    }
                    
                    $stmt = $db->prepare("
                        UPDATE `hero_slides` 
                        SET `title` = ?, `subtitle` = ?, `description` = ?, `image` = ?, `video` = ?, `media_type` = ?, `primary_cta_text` = ?, `primary_cta_url` = ?, `secondary_cta_text` = ?, `secondary_cta_url` = ?, `sort_order` = ?, `is_active` = ?
                        WHERE `id` = ?
                    ");
                    $stmt->execute([
                        $title, $subtitle, $description, $image, $video ?? '', $media_type ?? 'image', $primary_cta_text, $primary_cta_url, $secondary_cta_text, $secondary_cta_url, $sort_order, $is_active, $id
                    ]);
                    
                    // Create version snapshot and log audit
                    $summary_changes = 'Updated slide content details';
                    create_slide_snapshot($id, $summary_changes);
                    log_audit('HERO_UPDATED', 'hero', 'hero_slides', $id, $old_slide, ['title' => $title, 'image' => $image], "Updated hero slide {$id}");
                    
                    header('Location: /admin/hero?msg=updated');
                    exit;
                }
            } catch (Exception $e) {
                $error = 'Save Error: ' . $e->getMessage();
            }
        }
    }
}

// 4. Fetch details for edit form
$item = null;
if (($action === 'edit' && $id > 0)) {
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

// 5. Fetch slide version history
$versions = [];
if ($action === 'history' && $id > 0) {
    require_permission('hero.history');
    try {
        $stmt = $db->prepare("
            SELECT v.*, u.username 
            FROM `hero_slide_versions` v
            LEFT JOIN `users` u ON u.id = v.created_by
            WHERE v.hero_slide_id = ?
            ORDER BY v.version_number DESC
        ");
        $stmt->execute([$id]);
        $versions = $stmt->fetchAll();
        
        // Load slide info for context
        $stmt = $db->prepare("SELECT `title` FROM `hero_slides` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $slide_title = $stmt->fetchColumn() ?: "Slide ID #{$id}";
    } catch (Exception $e) {
        $error = 'Error loading version logs.';
    }
}

// 6. View version details
$ver_details = null;
if ($action === 'version_detail' && $version_id > 0) {
    require_permission('hero.history');
    try {
        $stmt = $db->prepare("
            SELECT v.*, u.username 
            FROM `hero_slide_versions` v
            LEFT JOIN `users` u ON u.id = v.created_by
            WHERE v.id = ? LIMIT 1
        ");
        $stmt->execute([$version_id]);
        $ver_details = $stmt->fetch();
        if (!$ver_details) {
            header('Location: /admin/hero?error=version_notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Error loading version details.';
    }
}

// 7. Listing all slides
$slides = [];
if ($action === 'list') {
    try {
        $slides = $db->query("SELECT * FROM `hero_slides` ORDER BY `sort_order` ASC")->fetchAll();
    } catch (Exception $e) {
        $error = "Database query failure.";
    }
}

$page_slug = 'admin-hero';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Homepage Hero Banners CMS</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Manage sliding banners, button links, images, version history, and restore states.</p>
  </div>
  <?php if ($action === 'list'): ?>
    <a href="/admin/hero?action=add" class="btn btn-primary" style="font-size: 0.85rem;">+ Add Hero Banner</a>
  <?php else: ?>
    <a href="/admin/hero" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to Banners</a>
  <?php endif; ?>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Hero slide added successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Hero slide updated and snapshot version logged.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Hero slide deleted and snapshot stored in history.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'restored'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Previous version restored successfully and new restored version snapshot logged.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- VIEW: LIST -->
<?php if ($action === 'list'): ?>
  <div class="card" style="border-left: none; padding: 2rem;">
    <?php if (!empty($slides)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Preview</th>
              <th style="padding: 0.75rem 1rem;">Title</th>
              <th style="padding: 0.75rem 1rem;">Subtitle / Eyebrow</th>
              <th style="padding: 0.75rem 1rem;">Sort Order</th>
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
                    <?php echo $slide['is_active'] ? 'ACTIVE' : 'DISABLED'; ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/hero?action=edit&id=<?php echo $slide['id']; ?>" style="color: var(--color-gold); font-weight: 600; margin-right: 1.25rem;">Edit</a>
                  <a href="/admin/hero?action=history&id=<?php echo $slide['id']; ?>" style="color: var(--color-navy); font-weight: 600; margin-right: 1.25rem;">History</a>
                  <a href="/admin/hero?action=delete&id=<?php echo $slide['id']; ?>" onclick="return confirm('Are you sure you want to delete this slide? Current content will be saved in version history.');" style="color: #EF4444; font-weight: 600;">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center;">No hero slides found. Click "+ Add Hero Banner" to add one.</p>
    <?php endif; ?>
  </div>

<!-- VIEW: ADD / EDIT -->
<?php elseif (($action === 'add' || $action === 'edit') && ($action === 'add' || $item)): ?>
  <div class="card" style="border-left: none; padding: 2.5rem; max-width: 800px; margin: 0 auto;">
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

      <div class="admin-form-group">
        <label class="admin-label">Slide Title *</label>
        <input type="text" name="title" required class="admin-input" value="<?php echo h($item['title'] ?? ''); ?>" placeholder="e.g. Learning Beyond Boundaries">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Subtitle / Eyebrow Text</label>
        <input type="text" name="subtitle" class="admin-input" value="<?php echo h($item['subtitle'] ?? ''); ?>" placeholder="e.g. ZUVIO GLOBAL SCHOOL">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Supporting Description Paragraph *</label>
        <textarea name="description" required class="admin-input" rows="3" placeholder="Supporting text description..."><?php echo h($item['description'] ?? ''); ?></textarea>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Primary CTA Button Text</label>
          <input type="text" name="primary_cta_text" class="admin-input" value="<?php echo h($item['primary_cta_text'] ?? ''); ?>" placeholder="e.g. Apply Now">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Primary CTA Button Link Target</label>
          <input type="text" name="primary_cta_url" class="admin-input" value="<?php echo h($item['primary_cta_url'] ?? ''); ?>" placeholder="e.g. /contact">
        </div>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Secondary CTA Button Text</label>
          <input type="text" name="secondary_cta_text" class="admin-input" value="<?php echo h($item['secondary_cta_text'] ?? ''); ?>" placeholder="e.g. Read Story">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Secondary CTA Button Link Target</label>
          <input type="text" name="secondary_cta_url" class="admin-input" value="<?php echo h($item['secondary_cta_url'] ?? ''); ?>" placeholder="e.g. /about">
        </div>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Hero Banner Media (Image or Video)</label>
        
        <div style="background-color: var(--color-surface-blue); border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; margin-bottom: 0.5rem;">
          
          <!-- Media Type Toggle -->
          <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">
              <input type="radio" name="media_type_select" value="image" id="media_image" <?php echo (empty($item['media_type']) || ($item['media_type'] ?? 'image') === 'image') ? 'checked' : ''; ?> onchange="document.getElementById('image-section').style.display='block'; document.getElementById('video-section').style.display='none';">
              🖼 Image Background
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: var(--color-navy);">
              <input type="radio" name="media_type_select" value="video" id="media_video" <?php echo (($item['media_type'] ?? '') === 'video') ? 'checked' : ''; ?> onchange="document.getElementById('image-section').style.display='none'; document.getElementById('video-section').style.display='block';">
              🎬 Video Background
            </label>
          </div>

          <!-- Image Section -->
          <div id="image-section" style="<?php echo (($item['media_type'] ?? 'image') === 'video') ? 'display:none;' : ''; ?>">
            <label class="admin-label" style="font-weight: 500;">Image URL / Path</label>
            <input type="text" name="image" class="admin-input" value="<?php echo h($item['image'] ?? ''); ?>" placeholder="e.g. /assets/images/Hero image 1.png" style="margin-bottom: 0.5rem;">
            <label class="admin-label" style="font-weight: normal; font-size: 0.75rem;">Or upload image file (Max 4MB: JPG, PNG, WEBP):</label>
            <input type="file" name="slide_image_file" class="admin-input" accept="image/jpeg,image/png,image/webp">
            <?php if (!empty($item['image']) && ($item['media_type'] ?? 'image') === 'image'): ?>
              <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 100px; height: 56px; background-image: url('<?php echo h($item['image']); ?>'); background-size: cover; background-position: center; border-radius: var(--radius-sm); border: 1px solid var(--color-border);"></div>
                <span style="font-size: 0.75rem; color: var(--color-muted);">Current image</span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Video Section -->
          <div id="video-section" style="<?php echo (($item['media_type'] ?? 'image') !== 'video') ? 'display:none;' : ''; ?>">
            <label class="admin-label" style="font-weight: 500;">Video URL / Path</label>
            <input type="text" name="video" class="admin-input" value="<?php echo h($item['video'] ?? ''); ?>" placeholder="e.g. /uploads/hero_video.mp4" style="margin-bottom: 0.5rem;">
            <label class="admin-label" style="font-weight: normal; font-size: 0.75rem;">Or upload video file (Max 50MB: MP4, WEBM, OGV):</label>
            <input type="file" name="slide_video_file" class="admin-input" accept="video/mp4,video/webm,video/ogg">
            <label class="admin-label" style="font-weight: normal; font-size: 0.75rem; margin-top: 0.5rem; color: var(--color-muted);">Fallback Image for video banner (displays while video loads / on mobile):</label>
            <input type="text" name="image" class="admin-input" value="<?php echo h($item['image'] ?? ''); ?>" placeholder="e.g. /assets/images/Hero image 1.png" style="margin-bottom: 0.25rem;">
            <?php if (!empty($item['video'])): ?>
              <div style="margin-top: 0.5rem;">
                <video src="<?php echo h($item['video']); ?>" style="width: 200px; height: 112px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);" muted playsinline preload="metadata"></video>
                <span style="font-size: 0.75rem; color: var(--color-muted); display: block; margin-top: 0.25rem;">Current video</span>
              </div>
            <?php endif; ?>
          </div>

        </div>
        <span style="font-size: 0.75rem; color: var(--color-muted);">Video backgrounds autoplay silently. Always provide a fallback image for mobile.</span>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Carousel Sort Order</label>
          <input type="number" name="sort_order" class="admin-input" value="<?php echo (int)($item['sort_order'] ?? 0); ?>">
        </div>
        <div class="admin-form-group" style="display: flex; align-items: center; margin-top: 1.75rem;">
          <label style="font-size: 0.9rem; font-weight: 600; color: var(--color-navy); display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
            <input type="checkbox" name="is_active" value="1" <?php echo ($action === 'add' || (isset($item['is_active']) && $item['is_active'] == 1)) ? 'checked' : ''; ?>>
            Active (Display slide in home slider)
          </label>
        </div>
      </div>

      <div style="margin-top: 2rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2.5rem;">Save Banner</button>
        <a href="/admin/hero" class="btn btn-outline" style="padding: 0.8rem 2rem; margin-left: 0.75rem;">Cancel</a>
      </div>
    </form>
  </div>

<!-- VIEW: HISTORY -->
<?php elseif ($action === 'history' && $id > 0): ?>
  <div class="card" style="border-left: none; padding: 2rem;">
    <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
      <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-secondary);">Version Snapshot Log: <?php echo h($slide_title); ?></h3>
    </div>
    
    <?php if (!empty($versions)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Version</th>
              <th style="padding: 0.75rem 1rem;">Changed By</th>
              <th style="padding: 0.75rem 1rem;">Timestamp</th>
              <th style="padding: 0.75rem 1rem;">Change Summary</th>
              <th style="padding: 0.75rem 1rem;">Status</th>
              <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($versions as $v): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem; font-weight: 700;">Version <?php echo $v['version_number']; ?></td>
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($v['username'] ?: 'System'); ?></td>
                <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('d M Y, h:i A', strtotime($v['created_at'])); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($v['change_summary'] ?: 'No summary description'); ?></td>
                <td style="padding: 0.75rem 1rem;">
                  <span style="display: inline-block; padding: 0.15rem 0.4rem; font-size: 0.65rem; border-radius: var(--radius-sm); font-weight: 700; background-color: <?php echo $v['is_active'] ? '#D1FAE5' : '#F3F4F6'; ?>; color: <?php echo $v['is_active'] ? '#065F46' : '#374151'; ?>;">
                    <?php echo $v['is_active'] ? 'ACTIVE' : 'DISABLED'; ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/hero?action=version_detail&version_id=<?php echo $v['id']; ?>" style="color: var(--color-gold); font-weight: 600; margin-right: 1rem;">View Details</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center;">No version snapshots tracked for this slide yet.</p>
    <?php endif; ?>
  </div>

<!-- VIEW: VERSION DETAIL -->
<?php elseif ($action === 'version_detail' && $ver_details): ?>
  <div class="grid-3" style="align-items: flex-start; gap: 2rem;">
    
    <!-- Snapshot details -->
    <div class="card" style="grid-column: span 2; border-left: none; padding: 2.5rem;">
      <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
        Slide Snapshot: Version <?php echo $ver_details['version_number']; ?>
      </h3>
      
      <?php if (!empty($ver_details['image'])): ?>
        <div style="height: 220px; background-color: var(--color-navy); background-image: url('<?php echo h($ver_details['image']); ?>'); background-size: cover; background-position: center; border-radius: var(--radius-md); margin-bottom: 2rem;"></div>
      <?php endif; ?>

      <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem; font-size: 0.9rem; color: var(--color-text); margin-bottom: 2rem;">
        <div>
          <strong style="color: var(--color-navy); display: block;">Slide Title:</strong>
          <span><?php echo h($ver_details['title']); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block;">Eyebrow / Subtitle:</strong>
          <span><?php echo h($ver_details['subtitle'] ?: 'None'); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy); display: block;">Description Body:</strong>
          <span><?php echo h($ver_details['description']); ?></span>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <strong style="color: var(--color-navy); display: block;">Primary CTA Button:</strong>
            <span>"<?php echo h($ver_details['primary_cta_text']); ?>" &rarr; <code><?php echo h($ver_details['primary_cta_url']); ?></code></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block;">Secondary CTA Button:</strong>
            <span>"<?php echo h($ver_details['secondary_cta_text']); ?>" &rarr; <code><?php echo h($ver_details['secondary_cta_url']); ?></code></span>
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <strong style="color: var(--color-navy); display: block;">Carousel Sort Order:</strong>
            <span><?php echo $ver_details['sort_order']; ?></span>
          </div>
          <div>
            <strong style="color: var(--color-navy); display: block;">Visibility Status:</strong>
            <span><?php echo $ver_details['is_active'] ? 'Active (Visible)' : 'Disabled (Hidden)'; ?></span>
          </div>
        </div>
      </div>
      
      <a href="/admin/hero?action=history&id=<?php echo $ver_details['hero_slide_id']; ?>" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">&larr; Back to History</a>
    </div>

    <!-- Restore action box -->
    <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-secondary);">Restore Slide Version</h3>
      <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.5rem;">
        Restoring <strong>Version <?php echo $ver_details['version_number']; ?></strong> will copy its content back to the live slider. A new history version snapshot will be logged automatically.
      </p>
      
      <form method="POST" action="/admin/hero?action=restore&version_id=<?php echo $ver_details['id']; ?>" onsubmit="return confirm('Restore Version <?php echo $ver_details['version_number']; ?>? Current content will be preserved in history and a new version will be created.');">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.9rem;">Confirm Restore</button>
      </form>
    </div>

  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
