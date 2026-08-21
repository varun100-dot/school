<?php
// Zuvio Global School - Admin Profiles Manager CRUD
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('about.view');

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = '';
$error = '';

// Helper function to process uploads and write to media table
function handle_file_upload($file_post_key) {
    global $db;
    if (!isset($_FILES[$file_post_key]) || $_FILES[$file_post_key]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $file = $_FILES[$file_post_key];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    
    // Check MIME Type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed_types)) {
        throw new Exception("Invalid file type. Only JPG, PNG, and WEBP images are allowed.");
    }
    
    // Limit to 4MB
    if ($file['size'] > 4 * 1024 * 1024) {
        throw new Exception("File is too large. Maximum allowed size is 4MB.");
    }
    
    // Generate unique name and target path
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    
    $upload_dir = dirname(__FILE__) . '/../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $storage_path = 'uploads/' . $new_name;
    $target_path = $upload_dir . $new_name;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $public_url = '/' . $storage_path;
        
        // Get dimensions
        $width = null;
        $height = null;
        $sizes = getimagesize($target_path);
        if ($sizes) {
            $width = $sizes[0];
            $height = $sizes[1];
        }
        
        // Insert media registry
        if ($db) {
            $stmt = $db->prepare("
                INSERT INTO `media` (`file_name`, `storage_path`, `public_url`, `mime_type`, `file_size`, `width`, `height`)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$file['name'], $storage_path, $public_url, $mime, $file['size'], $width, $height]);
        }
        
        return $public_url;
    }
    return null;
}

// 1. Action Handler: Delete
if ($action === 'delete' && $id > 0) {
    require_permission('about.delete');
    if (!$db) {
        $error = "Database offline.";
    } else {
        try {
            $s_fetch = $db->prepare("SELECT `id`, `name`, `slug`, `designation`, `image`, `short_description`, `bio`, `message`, `sort_order`, `is_active` FROM `leadership` WHERE `id` = ? LIMIT 1");
            $s_fetch->execute([$id]);
            $old_profile = $s_fetch->fetch();
            
            if ($old_profile) {
                $stmt = $db->prepare("DELETE FROM `leadership` WHERE `id` = ?");
                $stmt->execute([$id]);
                
                log_audit('PROFILE_DELETED', 'about', 'leadership', $id, $old_profile, null, "Deleted profile '{$old_profile['name']}'");
                header('Location: /admin/profiles?msg=deleted');
                exit;
            } else {
                $error = "Profile not found.";
            }
        } catch (Exception $e) {
            $error = "Could not delete profile: " . $e->getMessage();
        }
    }
}

// Fetch media files for library selection
$media_library = [];
if ($db) {
    try {
        $media_library = $db->query("SELECT * FROM `media` ORDER BY `created_at` DESC")->fetchAll();
    } catch (Exception $e) {
        error_log("[Profiles Media Fetch Error] " . $e->getMessage());
    }
}

// Action Handler: Reorder Profiles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'reorder') {
    require_permission('about.edit');
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $ordered_ids = $_POST['ordered_ids'] ?? '';
        if (!empty($ordered_ids)) {
            $ids = explode(',', $ordered_ids);
            try {
                if ($db) {
                    $db->beginTransaction();
                    $stmt = $db->prepare("UPDATE `leadership` SET `sort_order` = ? WHERE `id` = ?");
                    foreach ($ids as $index => $item_id) {
                        $stmt->execute([$index + 1, (int)$item_id]);
                    }
                    $db->commit();
                    log_audit('PROFILE_REORDERED', 'about', 'leadership', null, null, ['order' => $ids], "Reordered profiles");
                    header('Location: /admin/profiles?msg=reordered');
                    exit;
                } else {
                    $error = "Database offline.";
                }
            } catch (Exception $e) {
                if ($db && $db->inTransaction()) {
                    $db->rollBack();
                }
                $error = 'Transaction Error: ' . $e->getMessage();
            }
        } else {
            $error = 'No order data received.';
        }
    }
}

// 2. Action Handler: Save (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    if ($action === 'add') {
        require_permission('about.create');
    } else {
        require_permission('about.edit');
    }
    
    $is_active = isset($_POST['is_active']) && $_POST['is_active'] === '1' ? 1 : 0;
    if ($is_active === 1) {
        require_permission('about.publish');
    }

    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
        
        if (empty($name)) {
            $error = 'Name is required.';
        } else {
            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9\-]+/', '-', $name));
            }
            
            // Handle profile image input selector vs file upload
            $profile_image = $_POST['existing_image'] ?? '';
            
            // Check if user selected an image from media library
            if (!empty($_POST['library_image'])) {
                $profile_image = $_POST['library_image'];
            }
            
            try {
                // Handle new file upload (takes priority if selected)
                $uploaded_url = handle_file_upload('profile_image_file');
                if ($uploaded_url) {
                    $profile_image = $uploaded_url;
                }
                
                if (!$db) {
                    $error = "Database offline. Unable to save changes.";
                } else {
                    if ($action === 'add') {
                        $stmt = $db->prepare("
                            INSERT INTO `leadership` 
                            (`name`, `slug`, `designation`, `image`, `short_description`, `bio`, `message`, `sort_order`, `is_active`)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([
                            $name, $slug, $designation, $profile_image, $short_description, $bio, $message, $sort_order, $is_active
                        ]);
                        $new_id = $db->lastInsertId();
                        
                        log_audit('PROFILE_CREATED', 'about', 'leadership', $new_id, null, ['name' => $name], "Created profile '{$name}'");
                        header('Location: /admin/profiles?msg=added');
                        exit;
                    } else {
                        // Update flow
                        $old_stmt = $db->prepare("SELECT * FROM `leadership` WHERE `id` = ? LIMIT 1");
                        $old_stmt->execute([$id]);
                        $old_profile = $old_stmt->fetch();
                        
                        $stmt = $db->prepare("
                            UPDATE `leadership` 
                            SET `name` = ?, `slug` = ?, `designation` = ?, `image` = ?, `short_description` = ?, `bio` = ?, `message` = ?, `sort_order` = ?, `is_active` = ?
                            WHERE `id` = ?
                        ");
                        $stmt->execute([
                            $name, $slug, $designation, $profile_image, $short_description, $bio, $message, $sort_order, $is_active, $id
                        ]);
                        
                        log_audit('PROFILE_UPDATED', 'about', 'leadership', $id, $old_profile, ['name' => $name], "Updated profile '{$name}'");
                        header('Location: /admin/profiles?msg=updated');
                        exit;
                    }
                }
            } catch (Exception $e) {
                $error = 'Transaction Error: ' . $e->getMessage();
            }
        }
    }
}

// 3. Action Handler: Fetch active item for edit form
$item = null;
if ($action === 'edit' && $id > 0) {
    if ($db) {
        try {
    $stmt = $db->prepare("SELECT `id`, `name`, `slug`, `designation`, `image`, `short_description`, `bio`, `message`, `sort_order`, `is_active` FROM `leadership` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if (!$item) {
                header('Location: /admin/profiles?error=notfound');
                exit;
            }
            // Set defaults for columns that may not exist yet
            $item['slug'] = $item['slug'] ?? '';
            $item['short_description'] = $item['short_description'] ?? '';
        } catch (Exception $e) {
            $error = "Error loading profile details.";
        }
    }
}

// 4. Action Handler: Listing
$profiles = [];
if ($action === 'list' || $action === 'reorder') {
    if ($db) {
        try {
            $profiles = $db->query("SELECT `id`, `name`, `slug`, `designation`, `image`, `short_description`, `bio`, `message`, `sort_order`, `is_active` FROM `leadership` ORDER BY `sort_order` ASC")->fetchAll();
        } catch (Exception $e) {
            $error = "Database queries failed.";
        }
    }
}

$page_slug = 'admin-profiles';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">About Profiles Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Create, edit, publish, or sort leadership bios on the About Us page.</p>
  </div>
  <?php if ($action === 'list'): ?>
    <div style="display: flex; gap: 0.75rem;">
      <a href="/admin/profiles?action=reorder" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">⇅ Reorder Profiles</a>
      <a href="/admin/profiles?action=add" class="btn btn-primary" style="font-size: 0.85rem;">+ Create New Profile</a>
    </div>
  <?php else: ?>
    <a href="/admin/profiles" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to List</a>
  <?php endif; ?>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    <strong>Success!</strong> Profile created successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    <strong>Success!</strong> Profile updated successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    <strong>Success!</strong> Profile deleted successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'reordered'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    <strong>Success!</strong> Profiles order updated successfully.
  </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <div style="background-color: #FEE2E2; border-left: 4px solid #EF4444; padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem;">
    <strong>Error:</strong> <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- LISTING VIEW -->
<?php if ($action === 'list'): ?>
  <div style="background-color: #FFFFFF; border-radius: var(--radius-md); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
      <thead>
        <tr style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
          <th style="padding: 1rem 1.5rem; width: 60px;">Order</th>
          <th style="padding: 1rem 1.5rem; width: 80px;">Photo</th>
          <th style="padding: 1rem 1.5rem;">Name</th>
          <th style="padding: 1rem 1.5rem;">Designation</th>
          <th style="padding: 1rem 1.5rem;">Slug</th>
          <th style="padding: 1rem 1.5rem; width: 100px;">Status</th>
          <th style="padding: 1rem 1.5rem; width: 150px; text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($profiles)): ?>
          <tr>
            <td colspan="7" style="padding: 2.5rem; text-align: center; color: var(--color-muted); font-style: italic;">
              No profiles found in the database. Use fallback display online.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($profiles as $prof): ?>
            <tr style="border-bottom: 1px solid var(--color-border); transition: background var(--transition-fast);" onmouseover="this.style.backgroundColor='var(--color-surface-blue)'" onmouseout="this.style.backgroundColor='transparent'">
              <td style="padding: 1rem 1.5rem; font-weight: bold; color: var(--color-navy);"><?php echo h($prof['sort_order']); ?></td>
              <td style="padding: 1rem 1.5rem;">
                <?php if (!empty($prof['image'])): ?>
                  <img src="<?php echo h($prof['image']); ?>" alt="" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 1px solid var(--color-border);">
                <?php else: ?>
                  <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--color-navy); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    <?php echo h(substr($prof['name'], 0, 1)); ?>
                  </div>
                <?php endif; ?>
              </td>
              <td style="padding: 1rem 1.5rem; font-weight: 500; color: var(--color-navy);"><?php echo h($prof['name']); ?></td>
              <td style="padding: 1rem 1.5rem; color: var(--color-text);"><?php echo h($prof['designation']); ?></td>
              <td style="padding: 1rem 1.5rem; font-family: monospace; color: var(--color-muted);"><?php echo h($prof['slug'] ?? 'N/A'); ?></td>
              <td style="padding: 1rem 1.5rem;">
                <?php if ($prof['is_active']): ?>
                  <span style="background-color: #DEF7EC; color: #03543F; padding: 0.25rem 0.6rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">Published</span>
                <?php else: ?>
                  <span style="background-color: #FBD5D5; color: #9B1C1C; padding: 0.25rem 0.6rem; border-radius: var(--radius-sm); font-size: 0.75rem; font-weight: 600;">Hidden</span>
                <?php endif; ?>
              </td>
              <td style="padding: 1rem 1.5rem; text-align: right; white-space: nowrap;">
                <a href="/admin/profiles?action=edit&id=<?php echo $prof['id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.75rem; font-size: 0.75rem; margin-right: 0.25rem;">Edit</a>
                <a href="/admin/profiles?action=delete&id=<?php echo $prof['id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.75rem; font-size: 0.75rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Are you sure you want to delete this profile?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

<!-- ADD/EDIT FORM VIEW -->
<?php elseif ($action === 'add' || $action === 'edit'): ?>
  <div style="background-color: #FFFFFF; border-radius: var(--radius-md); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); padding: 2.5rem; max-width: 800px;">
    <form action="/admin/profiles?action=<?php echo $action; ?><?php echo $action === 'edit' ? '&id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        
        <!-- Name -->
        <div class="admin-form-group">
          <label class="admin-label" for="name">Full Name *</label>
          <input class="admin-input" type="text" id="name" name="name" value="<?php echo h($item['name'] ?? ''); ?>" required placeholder="e.g. Sharmin Habib">
        </div>

        <!-- Slug -->
        <div class="admin-form-group">
          <label class="admin-label" for="slug">URL Slug (leave blank to auto-generate)</label>
          <input class="admin-input" type="text" id="slug" name="slug" value="<?php echo h($item['slug'] ?? ''); ?>" placeholder="e.g. sharmin-habib">
        </div>

      </div>

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        
        <!-- Role/Designation -->
        <div class="admin-form-group">
          <label class="admin-label" for="designation">Role / Designation *</label>
          <input class="admin-input" type="text" id="designation" name="designation" value="<?php echo h($item['designation'] ?? ''); ?>" required placeholder="e.g. Co-Founder & Director">
        </div>

        <!-- Sort Order -->
        <div class="admin-form-group">
          <label class="admin-label" for="sort_order">Display Order (Display rank ascending)</label>
          <input class="admin-input" type="number" id="sort_order" name="sort_order" value="<?php echo h($item['sort_order'] ?? '0'); ?>">
        </div>

      </div>

      <!-- Image Upload & Selection -->
      <div style="border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.5rem; margin-bottom: 1.5rem; background-color: var(--color-surface-blue);">
        <label class="admin-label" style="font-size: 0.9rem; margin-bottom: 0.75rem;">Profile Image Configuration</label>
        
        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
          
          <!-- Upload New Image -->
          <div style="flex: 1 1 250px;">
            <label class="admin-label" for="profile_image_file" style="font-weight: 500; font-size: 0.75rem;">Upload New Profile Image</label>
            <input type="file" id="profile_image_file" name="profile_image_file" accept="image/jpeg,image/png,image/webp" style="font-size: 0.8rem; margin-bottom: 0.5rem;">
            <span style="font-size: 0.75rem; color: var(--color-muted); display: block;">Supports WEBP, JPG, PNG. Max size 4MB. Automatically added to Media library.</span>
          </div>
          
          <!-- Pick From Existing Media library -->
          <div style="flex: 1 1 250px;">
            <label class="admin-label" for="library_image" style="font-weight: 500; font-size: 0.75rem;">Or Select From Media Library</label>
            <select class="admin-input" id="library_image" name="library_image" style="font-size: 0.8rem;">
              <option value="">-- Choose existing media file --</option>
              <?php foreach ($media_library as $med): ?>
                <?php $selected = ($item['image'] ?? '') === $med['public_url'] ? 'selected' : ''; ?>
                <option value="<?php echo h($med['public_url']); ?>" <?php echo $selected; ?>>
                  <?php echo h($med['file_name']); ?> (<?php echo h($med['public_url']); ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <div style="margin-top: 1rem; border-top: 1px solid rgba(0,0,0,0.06); padding-top: 1rem; display: flex; align-items: center; gap: 1rem;">
          <input type="hidden" name="existing_image" value="<?php echo h($item['image'] ?? ''); ?>">
          <?php if (!empty($item['image'])): ?>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              <img src="<?php echo h($item['image']); ?>" alt="" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 1px solid var(--color-border);">
              <div>
                <span style="font-size: 0.75rem; font-weight: bold; color: var(--color-navy); display: block;">Current Image Path:</span>
                <code style="font-size: 0.7rem; background-color: #FFFFFF; padding: 0.1rem 0.3rem; border-radius: 2px; border: 1px solid var(--color-border);"><?php echo h($item['image']); ?></code>
              </div>
            </div>
          <?php else: ?>
            <span style="font-size: 0.75rem; color: var(--color-muted); font-style: italic;">No image currently configured. Default initials block will render.</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Short Description -->
      <div class="admin-form-group">
        <label class="admin-label" for="short_description">Short Description * (Displayed on the list grid; ~25 to 40 words)</label>
        <textarea class="admin-input" id="short_description" name="short_description" rows="3" required placeholder="A brief profile summary of professional background..."><?php echo h($item['short_description'] ?? ''); ?></textarea>
      </div>

      <!-- Full Biography -->
      <div class="admin-form-group">
        <label class="admin-label" for="bio">Full Professional Biography *</label>
        <textarea class="admin-input" id="bio" name="bio" rows="8" required placeholder="The complete detailed biography page copy..."><?php echo h($item['bio'] ?? ''); ?></textarea>
      </div>

      <!-- Personal Message -->
      <div class="admin-form-group">
        <label class="admin-label" for="message">Personal Message / Quote (Optional; displays in a premium blockquote)</label>
        <textarea class="admin-input" id="message" name="message" rows="3" placeholder="A personal vision quote or message to students..."><?php echo h($item['message'] ?? ''); ?></textarea>
      </div>

      <!-- Publishing Status -->
      <div class="admin-form-group" style="margin-bottom: 2rem;">
        <label class="admin-label" style="display: inline-flex; align-items: center; cursor: pointer;">
          <input type="checkbox" name="is_active" value="1" <?php echo ($item['is_active'] ?? 1) == 1 ? 'checked' : ''; ?> style="margin-right: 0.5rem; width: 16px; height: 16px;">
          <strong>Publish Profile</strong> (Visible to the public on /about page)
        </label>
      </div>

      <!-- Form Actions -->
      <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
        <a href="/admin/profiles" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">Cancel</a>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem;">Save Profile Settings</button>
      </div>

    </form>
  </div>

<!-- REORDER VIEW -->
<?php elseif ($action === 'reorder'): ?>
  <div style="background-color: #FFFFFF; border-radius: var(--radius-md); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); padding: 2.5rem; max-width: 800px;">
    <p style="color: var(--color-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
      Drag and drop the profiles below to rearrange them, or use the <strong>↑</strong> and <strong>↓</strong> buttons. The public About Us page will display leadership team bios in this order.
    </p>

    <div id="reorder-container" style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
      <?php if (empty($profiles)): ?>
        <p style="text-align: center; color: var(--color-muted); font-style: italic; padding: 2rem;">
          No profiles to sort.
        </p>
      <?php else: ?>
        <?php foreach ($profiles as $prof): ?>
          <div class="reorder-item" data-id="<?php echo $prof['id']; ?>" draggable="true" style="display: flex; align-items: center; justify-content: space-between; background-color: #FFFFFF; border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1rem 1.5rem; cursor: move; transition: all var(--transition-fast); box-shadow: var(--shadow-sm); user-select: none;">
            <div style="display: flex; align-items: center; gap: 1.25rem; pointer-events: none;">
              <!-- Drag Handle Icon -->
              <div style="color: var(--color-muted); cursor: grab; display: flex; align-items: center; padding-right: 0.25rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="9" cy="5" r="1"></circle>
                  <circle cx="9" cy="12" r="1"></circle>
                  <circle cx="9" cy="19" r="1"></circle>
                  <circle cx="15" cy="5" r="1"></circle>
                  <circle cx="15" cy="12" r="1"></circle>
                  <circle cx="15" cy="19" r="1"></circle>
                </svg>
              </div>
              
              <!-- Photo -->
              <?php if (!empty($prof['image'])): ?>
                <img src="<?php echo h($prof['image']); ?>" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 1px solid var(--color-border);">
              <?php else: ?>
                <div style="width: 44px; height: 44px; border-radius: 50%; background-color: var(--color-navy); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">
                  <?php echo h(substr($prof['name'], 0, 1)); ?>
                </div>
              <?php endif; ?>
              
              <!-- Info -->
              <div>
                <strong style="color: var(--color-navy); font-size: 0.9rem; display: block;"><?php echo h($prof['name']); ?></strong>
                <span style="color: var(--color-muted); font-size: 0.75rem;"><?php echo h($prof['designation']); ?></span>
              </div>
            </div>
            
            <!-- Up / Down buttons -->
            <div style="display: flex; gap: 0.35rem; align-items: center;">
              <button type="button" class="btn btn-outline btn-up" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; font-weight: bold; border-radius: var(--radius-sm); border-color: var(--color-border); box-shadow: none; display: flex; align-items: center; justify-content: center; height: 32px; width: 32px;">↑</button>
              <button type="button" class="btn btn-outline btn-down" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; font-weight: bold; border-radius: var(--radius-sm); border-color: var(--color-border); box-shadow: none; display: flex; align-items: center; justify-content: center; height: 32px; width: 32px;">↓</button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <form id="reorder-form" action="/admin/profiles?action=reorder" method="POST">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="ordered_ids" id="ordered_ids" value="">
      
      <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
        <a href="/admin/profiles" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">Cancel</a>
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem;">Save New Order</button>
      </div>
    </form>
  </div>

  <style>
    .reorder-item {
      transition: transform var(--transition-fast), box-shadow var(--transition-fast), border-color var(--transition-fast);
    }
    .reorder-item:hover {
      border-color: var(--color-navy) !important;
      box-shadow: var(--shadow-md) !important;
    }
    .reorder-item.dragging {
      opacity: 0.5;
      background-color: var(--color-surface-blue) !important;
      border-style: dashed !important;
    }
    .reorder-item.drag-over {
      border-top: 3px solid var(--color-gold) !important;
    }
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const container = document.getElementById('reorder-container');
      const form = document.getElementById('reorder-form');
      const orderedIdsInput = document.getElementById('ordered_ids');
      
      function updateIds() {
          const items = container.querySelectorAll('.reorder-item');
          const ids = Array.from(items).map(item => item.getAttribute('data-id'));
          orderedIdsInput.value = ids.join(',');
      }
      
      updateIds(); // Initial value
      
      // Up / Down Button Logic
      container.addEventListener('click', function(e) {
          const upBtn = e.target.closest('.btn-up');
          const downBtn = e.target.closest('.btn-down');
          
          if (upBtn) {
              const item = upBtn.closest('.reorder-item');
              const prev = item.previousElementSibling;
              if (prev && prev.classList.contains('reorder-item')) {
                  container.insertBefore(item, prev);
                  updateIds();
                  item.style.transform = 'scale(1.02)';
                  setTimeout(() => item.style.transform = 'none', 200);
              }
          } else if (downBtn) {
              const item = downBtn.closest('.reorder-item');
              const next = item.nextElementSibling;
              if (next && next.classList.contains('reorder-item')) {
                  container.insertBefore(next, item);
                  updateIds();
                  item.style.transform = 'scale(1.02)';
                  setTimeout(() => item.style.transform = 'none', 200);
              }
          }
      });
      
      // HTML5 Drag and Drop logic using standard drag events
      let dragEl = null;
      
      const items = container.querySelectorAll('.reorder-item');
      items.forEach(item => {
          item.addEventListener('dragstart', function(e) {
              dragEl = this;
              this.classList.add('dragging');
              e.dataTransfer.effectAllowed = 'move';
              e.dataTransfer.setData('text/plain', this.getAttribute('data-id'));
          });
          
          item.addEventListener('dragend', function() {
              this.classList.remove('dragging');
              items.forEach(el => el.classList.remove('drag-over'));
              updateIds();
          });
          
          item.addEventListener('dragover', function(e) {
              e.preventDefault();
              e.dataTransfer.dropEffect = 'move';
              return false;
          });
          
          item.addEventListener('dragenter', function(e) {
              if (this !== dragEl) {
                  this.classList.add('drag-over');
              }
          });
          
          item.addEventListener('dragleave', function() {
              this.classList.remove('drag-over');
          });
          
          item.addEventListener('drop', function(e) {
              e.preventDefault();
              this.classList.remove('drag-over');
              if (dragEl && this !== dragEl) {
                  const allItems = Array.from(container.querySelectorAll('.reorder-item'));
                  const dragIdx = allItems.indexOf(dragEl);
                  const dropIdx = allItems.indexOf(this);
                  
                  if (dragIdx < dropIdx) {
                      container.insertBefore(dragEl, this.nextSibling);
                  } else {
                      container.insertBefore(dragEl, this);
                  }
                  updateIds();
              }
          });
      });
  });
  </script>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
