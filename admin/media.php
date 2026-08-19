<?php
// Zuvio Global School - Admin Media Asset Manager (Phase 3 Upgrade)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('media.view');

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$version_id = isset($_GET['version_id']) ? (int)$_GET['version_id'] : 0;
$msg = '';
$error = '';

// Helper to validate and resolve paths safely against directory traversal
function get_absolute_safe_path($relative_path) {
    $root_dir = realpath(dirname(__FILE__) . '/../');
    if (!$root_dir) {
        throw new Exception("Root directory could not be resolved.");
    }
    
    // Normalize relative path
    $relative_path = ltrim(str_replace('\\', '/', $relative_path), '/');
    
    // Resolve absolute path
    $absolute_path = $root_dir . '/' . $relative_path;
    
    // Normalize slashes
    $absolute_path = str_replace('\\', '/', $absolute_path);
    $root_dir = str_replace('\\', '/', $root_dir);
    
    // Prevent directory traversal
    if (strpos($absolute_path, $root_dir) !== 0) {
        throw new Exception("Access Denied: Path traversal detected.");
    }
    
    // Verify directory is allowed: assets/images/, uploads/, assets/media/, assets/
    $allowed_dirs = ['assets/images/', 'uploads/', 'assets/media/', 'assets/'];
    $is_allowed = false;
    foreach ($allowed_dirs as $dir) {
        if (strpos($relative_path, $dir) === 0) {
            $is_allowed = true;
            break;
        }
    }
    
    if (!$is_allowed) {
        throw new Exception("Access Denied: Folder target restricted.");
    }
    
    return $absolute_path;
}

// 1. Action Handler: Delete File
if ($action === 'delete' && $id > 0) {
    require_permission('media.delete');
    try {
        $stmt = $db->prepare("SELECT * FROM `media` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $file_data = $stmt->fetch();
        
        if ($file_data) {
            $full_path = get_absolute_safe_path($file_data['storage_path']);
            if (file_exists($full_path)) {
                unlink($full_path);
            }
            
            $stmt = $db->prepare("DELETE FROM `media` WHERE `id` = ?");
            $stmt->execute([$id]);
            
            log_audit('MEDIA_DELETED', 'media', 'media', $id, $file_data, null, "Deleted media file {$file_data['file_name']}");
            header('Location: /admin/media?msg=deleted');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Failed to delete asset: ' . $e->getMessage();
    }
}

// 2. Action Handler: Replace Image (Atomic Overwrite)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'replace' && $id > 0) {
    require_permission('media.replace');
    
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        try {
            if (!isset($_FILES['replace_file']) || $_FILES['replace_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please select a valid image file.");
            }
            
            $file = $_FILES['replace_file'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime, $allowed_types)) {
                throw new Exception("Invalid image format. Only JPG, PNG, WEBP, and GIF are allowed.");
            }
            
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception("Maximum allowed image file size is 5MB.");
            }
            
            // Get current media record
            $stmt = $db->prepare("SELECT * FROM `media` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$id]);
            $media = $stmt->fetch();
            if (!$media) throw new Exception("Media record not found.");
            
            $target_live_path = get_absolute_safe_path($media['storage_path']);
            
            // Create backup directory
            $backup_dir = dirname(__FILE__) . '/../uploads/backups/';
            if (!is_dir($backup_dir)) mkdir($backup_dir, 0755, true);
            
            // Determine next version number
            $v_stmt = $db->prepare("SELECT COALESCE(MAX(`version_number`), 0) + 1 FROM `media_versions` WHERE `media_id` = ?");
            $v_stmt->execute([$id]);
            $next_version = (int)$v_stmt->fetchColumn();
            
            $ext = pathinfo($target_live_path, PATHINFO_EXTENSION);
            $backup_filename = 'backup_' . $id . '_v' . $next_version . '_' . time() . '.' . $ext;
            $target_backup_path = $backup_dir . $backup_filename;
            $db_backup_path = 'uploads/backups/' . $backup_filename;
            
            // Perform Backup
            if (file_exists($target_live_path)) {
                copy($target_live_path, $target_backup_path);
            }
            
            // Atomic overwrite
            if (move_uploaded_file($file['tmp_name'], $target_live_path)) {
                // Fetch new dimensions
                $width = null;
                $height = null;
                $sizes = getimagesize($target_live_path);
                if ($sizes) {
                    $width = $sizes[0];
                    $height = $sizes[1];
                }
                
                $user_id = $_SESSION['user_id'] ?? null;
                
                // Record snapshot in history
                $stmt = $db->prepare("
                    INSERT INTO `media_versions` 
                    (`media_id`, `version_number`, `original_path`, `backup_path`, `file_name`, `mime_type`, `file_size`, `width`, `height`, `replaced_by`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $id, $next_version, $media['storage_path'], $db_backup_path, $file['name'], $mime, $file['size'], $width, $height, $user_id
                ]);
                
                // Update live record size & dimensions
                $stmt = $db->prepare("
                    UPDATE `media` 
                    SET `file_size` = ?, `width` = ?, `height` = ?, `mime_type` = ?, `updated_at` = CURRENT_TIMESTAMP 
                    WHERE `id` = ?
                ");
                $stmt->execute([$file['size'], $width, $height, $mime, $id]);
                
                log_audit('IMAGE_REPLACED', 'media', 'media', $id, $media, ['file_size' => $file['size']], "Replaced image {$media['file_name']} atomically at stable path");
                header('Location: /admin/media?msg=replaced');
                exit;
            } else {
                throw new Exception("Atomic file overwrite failed.");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// 3. Action Handler: Restore Previous Image Version
if ($action === 'restore' && $version_id > 0) {
    require_permission('media.restore');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
            $error = 'Security check failed. Please submit again.';
        } else {
            try {
                // Fetch target version data
                $stmt = $db->prepare("SELECT * FROM `media_versions` WHERE `id` = ? LIMIT 1");
                $stmt->execute([$version_id]);
                $ver = $stmt->fetch();
                if (!$ver) throw new Exception("Backup version not found.");
                
                // Get live record
                $stmt = $db->prepare("SELECT * FROM `media` WHERE `id` = ? LIMIT 1");
                $stmt->execute([$ver['media_id']]);
                $media = $stmt->fetch();
                if (!$media) throw new Exception("Live media record not found.");
                
                $target_live_path = get_absolute_safe_path($media['storage_path']);
                $target_backup_path = get_absolute_safe_path($ver['backup_path']);
                
                if (!file_exists($target_backup_path)) {
                    throw new Exception("Backup archive file is missing on storage.");
                }
                
                // Create backup of current state before restoring
                $backup_dir = dirname(__FILE__) . '/../uploads/backups/';
                
                $v_stmt = $db->prepare("SELECT COALESCE(MAX(`version_number`), 0) + 1 FROM `media_versions` WHERE `media_id` = ?");
                $v_stmt->execute([$ver['media_id']]);
                $next_version = (int)$v_stmt->fetchColumn();
                
                $ext = pathinfo($target_live_path, PATHINFO_EXTENSION);
                $new_backup_filename = 'backup_' . $ver['media_id'] . '_v' . $next_version . '_' . time() . '.' . $ext;
                $new_target_backup_path = $backup_dir . $new_backup_filename;
                $new_db_backup_path = 'uploads/backups/' . $new_backup_filename;
                
                if (file_exists($target_live_path)) {
                    copy($target_live_path, $new_target_backup_path);
                }
                
                // Copy backup back to live path
                if (copy($target_backup_path, $target_live_path)) {
                    $user_id = $_SESSION['user_id'] ?? null;
                    
                    // Insert the restored state as a new version
                    $stmt = $db->prepare("
                        INSERT INTO `media_versions` 
                        (`media_id`, `version_number`, `original_path`, `backup_path`, `file_name`, `mime_type`, `file_size`, `width`, `height`, `replaced_by`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $ver['media_id'], $next_version, $media['storage_path'], $new_db_backup_path, $ver['file_name'], $ver['mime_type'], $ver['file_size'], $ver['width'], $ver['height'], $user_id
                    ]);
                    
                    // Update live media metadata
                    $stmt = $db->prepare("
                        UPDATE `media` 
                        SET `file_size` = ?, `width` = ?, `height` = ?, `mime_type` = ?, `updated_at` = CURRENT_TIMESTAMP 
                        WHERE `id` = ?
                    ");
                    $stmt->execute([$ver['file_size'], $ver['width'], $ver['height'], $ver['mime_type'], $ver['media_id']]);
                    
                    log_audit('IMAGE_RESTORED', 'media', 'media', $ver['media_id'], $media, $ver, "Restored image {$media['file_name']} to Version " . $ver['version_number']);
                    header('Location: /admin/media?msg=restored');
                    exit;
                } else {
                    throw new Exception("Failed to copy backup archive to target live path.");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }
}

// 4. Handle standard file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_media'])) {
    require_permission('media.upload');
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        try {
            if (!isset($_FILES['media_file']) || $_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please select a file to upload.");
            }
            
            $file = $_FILES['media_file'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime, $allowed_types)) {
                throw new Exception("Invalid file type. Only JPG, PNG, WEBP, and GIF images are allowed.");
            }
            
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception("Maximum allowed file size is 5MB.");
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_name = 'media_' . bin2hex(random_bytes(6)) . '_' . time() . '.' . $ext;
            
            $upload_dir = dirname(__FILE__) . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $storage_path = 'uploads/' . $new_name;
            $target_path = $upload_dir . $new_name;
            
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $public_url = '/' . $storage_path;
                
                $width = null;
                $height = null;
                $sizes = getimagesize($target_path);
                if ($sizes) {
                    $width = $sizes[0];
                    $height = $sizes[1];
                }
                
                $alt_text = trim($_POST['alt_text'] ?? '');
                
                $stmt = $db->prepare("
                    INSERT INTO `media` (`file_name`, `storage_path`, `public_url`, `alt_text`, `mime_type`, `file_size`, `width`, `height`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$file['name'], $storage_path, $public_url, $alt_text, $mime, $file['size'], $width, $height]);
                $new_media_id = $db->lastInsertId();
                
                log_audit('MEDIA_UPLOADED', 'media', 'media', $new_media_id, null, ['file_name' => $file['name']], "Uploaded new media file {$file['name']}");
                header('Location: /admin/media?msg=uploaded');
                exit;
            } else {
                throw new Exception("Failed to save uploaded file.");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// 5. Fetch single media file detail
$target_media = null;
if (($action === 'replace' || $action === 'history') && $id > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM `media` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $target_media = $stmt->fetch();
        if (!$target_media) {
            header('Location: /admin/media?error=notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Failed to load asset details.';
    }
}

// 6. Fetch version history details
$versions = [];
if ($action === 'history' && $id > 0) {
    try {
        $stmt = $db->prepare("
            SELECT mv.*, u.username 
            FROM `media_versions` mv
            LEFT JOIN `users` u ON u.id = mv.replaced_by
            WHERE mv.media_id = ?
            ORDER BY mv.version_number DESC
        ");
        $stmt->execute([$id]);
        $versions = $stmt->fetchAll();
    } catch (Exception $e) {
        $error = 'Failed to load version history.';
    }
}

// 7. View single version details
$ver_details = null;
if ($action === 'version_detail' && $version_id > 0) {
    try {
        $stmt = $db->prepare("
            SELECT mv.*, u.username 
            FROM `media_versions` mv
            LEFT JOIN `users` u ON u.id = mv.replaced_by
            WHERE mv.id = ? LIMIT 1
        ");
        $stmt->execute([$version_id]);
        $ver_details = $stmt->fetch();
        if (!$ver_details) {
            header('Location: /admin/media?error=version_notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = 'Failed to load version details.';
    }
}

// 8. Fetch general media files
$media_files = [];
if ($action === 'list') {
    try {
        $media_files = $db->query("SELECT * FROM `media` ORDER BY `created_at` DESC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Database connection offline.';
    }
}

$page_slug = 'admin-media';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Media Manager & Image Replacer</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Upload new items, or overwrite core files at stable URLs to update layout imagery globally.</p>
  </div>
  <?php if ($action !== 'list'): ?>
    <a href="/admin/media" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to Library</a>
  <?php endif; ?>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'uploaded'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Asset uploaded successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'replaced'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Asset replaced atomically. Stable URL remains unchanged.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'restored'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Backup version restored successfully. Live URL updated.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Asset deleted successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- VIEW: LIST -->
<?php if ($action === 'list'): ?>
  <div class="grid-3" style="align-items: flex-start; gap: 2rem;">
    
    <!-- Gallery -->
    <div class="card" style="grid-column: span 2; border-left: none; padding: 2rem;">
      <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary);">Uploaded Files Library</h3>
      
      <?php if (!empty($media_files)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.25rem;">
          <?php foreach ($media_files as $file): ?>
            <!-- Add dynamic cache bust timestamp to preview -->
            <?php $cache_bust = file_exists(dirname(__FILE__) . '/../' . $file['storage_path']) ? filemtime(dirname(__FILE__) . '/../' . $file['storage_path']) : time(); ?>
            <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden; display: flex; flex-direction: column; height: 250px;">
              <div style="height: 110px; background-color: var(--color-navy); background-image: url('<?php echo h($file['public_url']); ?>?v=<?php echo $cache_bust; ?>'); background-size: cover; background-position: center;"></div>
              <div style="padding: 0.6rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; font-size: 0.75rem;">
                <div>
                  <span style="font-weight: 600; color: var(--color-navy); text-overflow: ellipsis; overflow: hidden; white-space: nowrap; display: block;" title="<?php echo h($file['file_name']); ?>">
                    <?php echo h($file['file_name']); ?>
                  </span>
                  <span style="color: var(--color-muted); display: block; font-size: 0.65rem; margin-top: 0.1rem;">
                    <?php echo $file['width'] ? "{$file['width']}x{$file['height']}" : 'Unknown size'; ?> (<?php echo round($file['file_size']/1024, 1); ?> KB)
                  </span>
                  <span style="color: var(--color-muted); display: block; font-size: 0.65rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="<?php echo h($file['public_url']); ?>">
                    Path: <?php echo h($file['public_url']); ?>
                  </span>
                </div>
                
                <div style="margin-top: 0.5rem; display: flex; flex-direction: column; gap: 0.25rem;">
                  <button onclick="navigator.clipboard.writeText('<?php echo h($file['public_url']); ?>'); alert('URL Copied: <?php echo h($file['public_url']); ?>');" class="btn btn-outline" style="padding: 0.2rem 0.5rem; font-size: 0.65rem; width: 100%; border-radius: 2px;">Copy URL</button>
                  <div style="display: flex; gap: 0.25rem;">
                    <a href="/admin/media?action=replace&id=<?php echo $file['id']; ?>" class="btn btn-primary" style="padding: 0.2rem 0.4rem; font-size: 0.65rem; flex-grow: 1; text-align: center; border-radius: 2px;">Replace</a>
                    <a href="/admin/media?action=history&id=<?php echo $file['id']; ?>" class="btn btn-outline" style="padding: 0.2rem 0.4rem; font-size: 0.65rem; flex-grow: 1; text-align: center; border-radius: 2px;">History</a>
                  </div>
                  <a href="/admin/media?action=delete&id=<?php echo $file['id']; ?>" onclick="return confirm('Are you sure you want to delete this asset? The physical file will be removed.');" style="color: #EF4444; font-weight: 600; text-align: center; display: block; margin-top: 0.15rem; font-size: 0.65rem;">Delete File</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="color: var(--color-muted); text-align: center; padding: 3rem 0;">No media files uploaded yet.</p>
      <?php endif; ?>
    </div>

    <!-- Upload Box -->
    <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-secondary);">Upload New Asset</h3>
      
      <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        
        <div class="admin-form-group">
          <label class="admin-label">Choose Image File *</label>
          <input type="file" name="media_file" required class="admin-input">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Alt Text / Accessibility Label</label>
          <input type="text" name="alt_text" class="admin-input" placeholder="e.g. Logo image descriptor">
        </div>

        <button type="submit" name="upload_media" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Upload File</button>
      </form>
    </div>
  </div>

<!-- VIEW: REPLACE -->
<?php elseif ($action === 'replace' && $target_media): ?>
  <div class="card" style="border-left: none; padding: 2.5rem; max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary);">Atomic Image Overwrite</h3>
    
    <div style="background-color: var(--color-surface-blue); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.8rem; color: var(--color-navy); margin-bottom: 1.5rem; line-height: 1.5;">
      <strong>Target Path:</strong> <code><?php echo h($target_media['public_url']); ?></code><br>
      <strong>Current file name:</strong> <?php echo h($target_media['file_name']); ?><br>
      The new uploaded file will physically overwrite the existing file. The public path will remain unchanged, preserving layouts and cached pages.
    </div>
    
    <!-- Preview current -->
    <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 1.5rem;">
      <label class="admin-label">Current Image Preview</label>
      <?php $cache_bust = file_exists(dirname(__FILE__) . '/../' . $target_media['storage_path']) ? filemtime(dirname(__FILE__) . '/../' . $target_media['storage_path']) : time(); ?>
      <div style="height: 180px; background-color: var(--color-navy); background-image: url('<?php echo h($target_media['public_url']); ?>?v=<?php echo $cache_bust; ?>'); background-size: contain; background-repeat: no-repeat; background-position: left; border-radius: var(--radius-sm);"></div>
    </div>
    
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      
      <div class="admin-form-group">
        <label class="admin-label">Choose Overwrite Image File *</label>
        <input type="file" name="replace_file" required class="admin-input">
        <p style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.35rem;">Only JPG, PNG, WEBP, and GIF images up to 5MB are accepted.</p>
      </div>

      <div style="margin-top: 2rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2.5rem;">Overwrite File</button>
        <a href="/admin/media" class="btn btn-outline" style="padding: 0.8rem 2rem; margin-left: 0.75rem;">Cancel</a>
      </div>
    </form>
  </div>

<!-- VIEW: HISTORY -->
<?php elseif ($action === 'history' && $target_media): ?>
  <div class="card" style="border-left: none; padding: 2rem;">
    <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
      <h3 style="font-size: 1.2rem; color: var(--color-navy); font-family: var(--font-secondary);">Image Version Snapshot Trail: <?php echo h($target_media['file_name']); ?></h3>
      <p style="font-size: 0.80rem; color: var(--color-muted);">Live URL Path: <code><?php echo h($target_media['public_url']); ?></code></p>
    </div>
    
    <?php if (!empty($versions)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Version</th>
              <th style="padding: 0.75rem 1rem;">Changed By</th>
              <th style="padding: 0.75rem 1rem;">Upload Timestamp</th>
              <th style="padding: 0.75rem 1rem;">Original Filename</th>
              <th style="padding: 0.75rem 1rem;">Dimensions</th>
              <th style="padding: 0.75rem 1rem;">File Size</th>
              <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($versions as $v): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem; font-weight: 700;">Version <?php echo $v['version_number']; ?></td>
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($v['username'] ?: 'System'); ?></td>
                <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('d M Y, h:i A', strtotime($v['created_at'])); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($v['file_name']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo $v['width'] ? "{$v['width']}x{$v['height']}" : 'Unknown'; ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo round($v['file_size']/1024, 1); ?> KB</td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/media?action=version_detail&version_id=<?php echo $v['id']; ?>" style="color: var(--color-gold); font-weight: 600;">View & Restore</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center; padding: 2rem 0;">No previous backup versions are available for this asset.</p>
    <?php endif; ?>
  </div>

<!-- VIEW: VERSION DETAIL -->
<?php elseif ($action === 'version_detail' && $ver_details): ?>
  <div class="grid-3" style="align-items: flex-start; gap: 2rem;">
    
    <!-- Snapshot details -->
    <div class="card" style="grid-column: span 2; border-left: none; padding: 2.5rem;">
      <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary); border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
        Image Snapshot: Version <?php echo $ver_details['version_number']; ?>
      </h3>
      
      <!-- Backup preview -->
      <div style="margin-bottom: 2rem;">
        <label class="admin-label" style="margin-bottom: 0.75rem;">Archived Image Preview</label>
        <div style="max-height: 250px; overflow: hidden; border-radius: var(--radius-sm); border: 1px dashed var(--color-border);">
          <img src="/<?php echo h($ver_details['backup_path']); ?>" style="max-width: 100%; display: block; height: auto; max-height: 250px; object-fit: contain;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; font-size: 0.85rem; color: var(--color-text);">
        <div>
          <strong style="color: var(--color-navy);">Uploaded Filename:</strong>
          <span style="display: block; margin-top: 0.2rem;"><?php echo h($ver_details['file_name']); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy);">MIME Type:</strong>
          <span style="display: block; margin-top: 0.2rem;"><?php echo h($ver_details['mime_type'] ?: 'Unknown'); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy);">Dimensions:</strong>
          <span style="display: block; margin-top: 0.2rem;"><?php echo $ver_details['width'] ? "{$ver_details['width']}px wide by {$ver_details['height']}px high" : 'Unknown'; ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy);">Archive File Size:</strong>
          <span style="display: block; margin-top: 0.2rem;"><?php echo round($ver_details['file_size']/1024, 1); ?> KB</span>
        </div>
        <div>
          <strong style="color: var(--color-navy);">Replaced By:</strong>
          <span style="display: block; margin-top: 0.2rem;"><?php echo h($ver_details['username'] ?: 'System'); ?></span>
        </div>
        <div>
          <strong style="color: var(--color-navy);">Replaced Date:</strong>
          <span style="display: block; margin-top: 0.2rem;"><?php echo date('d M Y, h:i A', strtotime($ver_details['created_at'])); ?></span>
        </div>
      </div>
      
      <div style="margin-top: 2.5rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
        <a href="/admin/media?action=history&id=<?php echo $ver_details['media_id']; ?>" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">&larr; Back to History</a>
      </div>
    </div>

    <!-- Restore action box -->
    <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-secondary);">Restore Image Version</h3>
      <p style="font-size: 0.8rem; color: var(--color-muted); line-height: 1.6; margin-bottom: 1.5rem;">
        Restoring <strong>Version <?php echo $ver_details['version_number']; ?></strong> will copy this archived file back onto the active public path: <code>/<?php echo h($ver_details['original_path']); ?></code>. A new version snapshot will be logged automatically.
      </p>
      
      <form method="POST" action="/admin/media?action=restore&version_id=<?php echo $ver_details['id']; ?>" onsubmit="return confirm('Restore Version <?php echo $ver_details['version_number']; ?>? Current image will be backed up in version history and replaced.');">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.9rem;">Confirm Restore</button>
      </form>
    </div>

  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
