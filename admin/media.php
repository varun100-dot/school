<?php
// Zuvio Global School - Admin Media Asset Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_admin_role();

$msg = '';
$error = '';

// Handle file deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    try {
        // Fetch storage path
        $stmt = $db->prepare("SELECT `storage_path` FROM `media` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$del_id]);
        $file_path = $stmt->fetchColumn();
        
        if ($file_path) {
            $full_path = dirname(__FILE__) . '/../' . $file_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM `media` WHERE `id` = ?");
        $stmt->execute([$del_id]);
        header('Location: /admin/media?msg=deleted');
        exit;
    } catch (Exception $e) {
        $error = 'Failed to delete asset: ' . $e->getMessage();
    }
}

// Handle upload action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_media'])) {
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
                
                // Fetch dimensions
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

// Fetch media files
$media_files = [];
if ($db) {
    try {
        $media_files = $db->query("SELECT * FROM `media` ORDER BY `created_at` DESC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Database connection offline.';
    }
}

$page_slug = 'admin-media';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="margin-bottom: 2rem;">
  <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Media Asset Manager</h1>
  <p style="color: var(--color-muted); font-size: 0.85rem;">Upload static images, retrieve their copyable URLs, or clean up assets.</p>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'uploaded'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Asset uploaded successfully.
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

<div class="grid-3" style="align-items: flex-start; gap: 2rem;">
  
  <!-- Left Columns: Gallery list -->
  <div class="card" style="grid-column: span 2; border-left: none; padding: 2rem;">
    <h3 style="font-size: 1.15rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-secondary);">Uploaded Files Library</h3>
    
    <?php if (!empty($media_files)): ?>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1.25rem;">
        <?php foreach ($media_files as $file): ?>
          <div style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-sm); overflow: hidden; display: flex; flex-direction: column; height: 190px;">
            <div style="height: 100px; background-color: var(--color-navy); background-image: url('<?php echo h($file['public_url']); ?>'); background-size: cover; background-position: center;"></div>
            <div style="padding: 0.5rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; font-size: 0.75rem;">
              <span style="font-weight: 600; color: var(--color-navy); text-overflow: ellipsis; overflow: hidden; white-space: nowrap; display: block;" title="<?php echo h($file['file_name']); ?>">
                <?php echo h($file['file_name']); ?>
              </span>
              <button onclick="navigator.clipboard.writeText('<?php echo h($file['public_url']); ?>'); alert('URL Copied: <?php echo h($file['public_url']); ?>');" class="btn btn-outline" style="padding: 0.2rem 0.5rem; font-size: 0.65rem; width: 100%; border-radius: 2px;">Copy URL</button>
              <a href="/admin/media?action=delete&id=<?php echo $file['id']; ?>" onclick="return confirm('Are you sure you want to delete this asset? The physical file will be removed.');" style="color: #EF4444; font-weight: 600; text-align: center; display: block; margin-top: 0.25rem; font-size: 0.65rem;">Delete File</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); text-align: center; padding: 2rem 0;">No media files uploaded yet.</p>
    <?php endif; ?>
  </div>

  <!-- Right Column: Upload Box -->
  <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
    <h3 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-secondary);">Upload New Asset</h3>
    
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      
      <div class="admin-form-group">
        <label class="admin-label">Choose Image File *</label>
        <input type="file" name="media_file" required class="admin-input">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Alt text / Description</label>
        <input type="text" name="alt_text" class="admin-input" placeholder="e.g. Zuvio classroom students studying">
      </div>

      <button type="submit" name="upload_media" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Upload File</button>
    </form>
  </div>

</div>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
