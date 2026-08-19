<?php
// Zuvio Global School - Admin Blogs Manager CRUD
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_permission('blogs.view');

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
    
    if (!in_value_array($mime, $allowed_types)) {
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
        $stmt = $db->prepare("
            INSERT INTO `media` (`file_name`, `storage_path`, `public_url`, `mime_type`, `file_size`, `width`, `height`)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$file['name'], $storage_path, $public_url, $mime, $file['size'], $width, $height]);
        
        return $public_url;
    }
    return null;
}

function in_value_array($val, $arr) {
    return in_array($val, $arr);
}

// 1. Action Handler: Delete
if ($action === 'delete' && $id > 0) {
    require_permission('blogs.delete');
    if (!$db) {
        $error = "Database offline.";
    } else {
        try {
            $s_fetch = $db->prepare("SELECT * FROM `blogs` WHERE `id` = ? LIMIT 1");
            $s_fetch->execute([$id]);
            $old_blog = $s_fetch->fetch();
            
            $stmt = $db->prepare("DELETE FROM `blogs` WHERE `id` = ?");
            $stmt->execute([$id]);
            
            log_audit('BLOG_DELETED', 'blogs', 'blogs', $id, $old_blog, null, "Deleted blog article '{$old_blog['title']}'");
            header('Location: /admin/blogs?msg=deleted');
            exit;
        } catch (Exception $e) {
            $error = "Could not delete article: " . $e->getMessage();
        }
    }
}

// Fetch categories for forms
$categories = [];
if ($db) {
    try {
        $categories = $db->query("SELECT * FROM `blog_categories` ORDER BY `name` ASC")->fetchAll();
    } catch (Exception $e) {
        error_log("[CRUD Category Fetch Error] " . $e->getMessage());
    }
}

// 2. Action Handler: Save (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    if ($action === 'add') {
        require_permission('blogs.create');
    } else {
        require_permission('blogs.edit');
    }
    
    // If attempting to publish, enforce blogs.publish permission
    $status = $_POST['status'] === 'published' ? 'published' : 'draft';
    if ($status === 'published') {
        require_permission('blogs.publish');
    }

    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $author = trim($_POST['author'] ?? '');
        $author_designation = trim($_POST['author_designation'] ?? '');
        $status = $_POST['status'] === 'published' ? 'published' : 'draft';
        $publish_date = !empty($_POST['publish_date']) ? $_POST['publish_date'] : date('Y-m-d');
        
        $seo_title = trim($_POST['seo_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $canonical_url = trim($_POST['canonical_url'] ?? '');
        
        if (empty($title)) {
            $error = 'Title is required.';
        } else {
            if (empty($slug)) {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9\-]+/', '-', $title));
            }
            
            try {
                // Handle optional image upload
                $uploaded_url = handle_file_upload('featured_image_file');
                
                if ($action === 'add') {
                    $featured_image = $uploaded_url ?: '';
                    
                    $stmt = $db->prepare("
                        INSERT INTO `blogs` 
                        (`title`, `slug`, `excerpt`, `content`, `featured_image`, `author`, `author_designation`, `category_id`, `publish_date`, `status`, `seo_title`, `meta_description`, `canonical_url`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $title, $slug, $excerpt, $content, $featured_image, $author, $author_designation, $category_id, $publish_date, $status, $seo_title, $meta_description, $canonical_url
                    ]);
                    $new_id = $db->lastInsertId();
                    
                    log_audit('BLOG_CREATED', 'blogs', 'blogs', $new_id, null, ['title' => $title], "Created blog article '{$title}'");
                    header('Location: /admin/blogs?msg=added');
                    exit;
                } else {
                    // Update flow
                    // Keep existing image if no new upload is selected
                    $featured_image = $_POST['existing_featured_image'] ?? '';
                    if ($uploaded_url) {
                        $featured_image = $uploaded_url;
                    }
                    
                    $old_stmt = $db->prepare("SELECT * FROM `blogs` WHERE `id` = ? LIMIT 1");
                    $old_stmt->execute([$id]);
                    $old_blog = $old_stmt->fetch();
                    
                    $stmt = $db->prepare("
                        UPDATE `blogs` 
                        SET `title` = ?, `slug` = ?, `excerpt` = ?, `content` = ?, `featured_image` = ?, `author` = ?, `author_designation` = ?, `category_id` = ?, `publish_date` = ?, `status` = ?, `seo_title` = ?, `meta_description` = ?, `canonical_url` = ?
                        WHERE `id` = ?
                    ");
                    $stmt->execute([
                        $title, $slug, $excerpt, $content, $featured_image, $author, $author_designation, $category_id, $publish_date, $status, $seo_title, $meta_description, $canonical_url, $id
                    ]);
                    
                    log_audit('BLOG_UPDATED', 'blogs', 'blogs', $id, $old_blog, ['title' => $title], "Updated blog article '{$title}'");
                    header('Location: /admin/blogs?msg=updated');
                    exit;
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
    try {
        $stmt = $db->prepare("SELECT * FROM `blogs` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        if (!$item) {
            header('Location: /admin/blogs?error=notfound');
            exit;
        }
    } catch (Exception $e) {
        $error = "Error loading article details.";
    }
}

// 4. Action Handler: Listing
$posts = [];
if ($action === 'list') {
    try {
        $posts = $db->query("
            SELECT b.*, c.name as category_name 
            FROM `blogs` b 
            LEFT JOIN `blog_categories` c ON c.id = b.category_id 
            ORDER BY b.created_at DESC
        ")->fetchAll();
    } catch (Exception $e) {
        $error = "Database queries failed.";
    }
}

$page_slug = 'admin-blogs';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Blogs & Insights Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Create, edit, publish, or draft school articles.</p>
  </div>
  <?php if ($action === 'list'): ?>
    <a href="/admin/blogs?action=add" class="btn btn-primary" style="font-size: 0.85rem;">+ Create New Blog</a>
  <?php else: ?>
    <a href="/admin/blogs" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to List</a>
  <?php endif; ?>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Article added successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Article updated successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Article deleted successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- Action View: LIST -->
<?php if ($action === 'list'): ?>
  <div class="card" style="border-left: none; padding: 2rem;">
    <?php if (!empty($posts)): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
              <th style="padding: 0.75rem 1rem;">Image</th>
              <th style="padding: 0.75rem 1rem;">Title</th>
              <th style="padding: 0.75rem 1rem;">Category</th>
              <th style="padding: 0.75rem 1rem;">Author</th>
              <th style="padding: 0.75rem 1rem;">Status</th>
              <th style="padding: 0.75rem 1rem;">Publish Date</th>
              <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($posts as $post): ?>
              <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-text);">
                <td style="padding: 0.75rem 1rem;">
                  <div style="width: 60px; height: 40px; background-color: var(--color-navy); background-image: url('<?php echo h($post['featured_image']); ?>'); background-size: cover; background-position: center; border-radius: var(--radius-sm);"></div>
                </td>
                <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo h($post['title']); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($post['category_name'] ?: 'None'); ?></td>
                <td style="padding: 0.75rem 1rem;"><?php echo h($post['author'] ?: 'Zuvio Editorial'); ?></td>
                <td style="padding: 0.75rem 1rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; border-radius: var(--radius-sm); font-weight: 700; background-color: <?php echo $post['status'] === 'published' ? '#D1FAE5' : '#F3F4F6'; ?>; color: <?php echo $post['status'] === 'published' ? '#065F46' : '#374151'; ?>;">
                    <?php echo strtoupper($post['status']); ?>
                  </span>
                </td>
                <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo date('Y-m-d', strtotime($post['publish_date'])); ?></td>
                <td style="padding: 0.75rem 1rem; text-align: right;">
                  <a href="/admin/blogs?action=edit&id=<?php echo $post['id']; ?>" style="color: var(--color-gold); font-weight: 600; margin-right: 1rem;">Edit</a>
                  <a href="/admin/blogs?action=delete&id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this article?');" style="color: #EF4444; font-weight: 600;">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p style="color: var(--color-muted); font-size: 0.85rem; text-align: center; padding: 2rem 0;">No blog articles found. Click "+ Create New Blog" to add your first post.</p>
    <?php endif; ?>
  </div>

<!-- Action View: ADD / EDIT -->
<?php elseif ($action === 'add' || $action === 'edit'): ?>
  <div class="card" style="border-left: none; padding: 2.5rem;">
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <?php if ($action === 'edit'): ?>
        <input type="hidden" name="existing_featured_image" value="<?php echo h($item['featured_image']); ?>">
      <?php endif; ?>

      <div class="admin-form-group">
        <label class="admin-label">Title *</label>
        <input type="text" name="title" required class="admin-input" value="<?php echo h($item['title'] ?? ''); ?>" placeholder="Enter blog title">
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Slug (Optional - auto-generated if left blank)</label>
          <input type="text" name="slug" class="admin-input" value="<?php echo h($item['slug'] ?? ''); ?>" placeholder="e.g. academic-pathways-roadmap">
        </div>
        
        <div class="admin-form-group">
          <label class="admin-label">Category</label>
          <select name="category_id" class="admin-input" style="height: 38px;">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?php echo $cat['id']; ?>" <?php echo (isset($item['category_id']) && $item['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                <?php echo h($cat['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Author Name</label>
          <input type="text" name="author" class="admin-input" value="<?php echo h($item['author'] ?? 'Zuvio Editorial'); ?>">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Author Designation</label>
          <input type="text" name="author_designation" class="admin-input" value="<?php echo h($item['author_designation'] ?? 'Academic Contributor'); ?>">
        </div>
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <label class="admin-label">Publish Date</label>
          <input type="date" name="publish_date" class="admin-input" value="<?php echo h($item['publish_date'] ?? date('Y-m-d')); ?>">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Status</label>
          <select name="status" class="admin-input" style="height: 38px;">
            <option value="draft" <?php echo (isset($item['status']) && $item['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
            <option value="published" <?php echo (isset($item['status']) && $item['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
          </select>
        </div>
      </div>

      <!-- File upload for Featured Image -->
      <div class="admin-form-group">
        <label class="admin-label">Featured Image (Max 4MB: JPG, PNG, WEBP)</label>
        <?php if (!empty($item['featured_image'])): ?>
          <div style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 1rem;">
            <img src="<?php echo h($item['featured_image']); ?>" style="width: 100px; height: 60px; object-fit: cover; border-radius: var(--radius-sm);">
            <span style="font-size: 0.8rem; color: var(--color-muted);">Current image file</span>
          </div>
        <?php endif; ?>
        <input type="file" name="featured_image_file" class="admin-input">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Excerpt (Short summary description for listings)</label>
        <textarea name="excerpt" class="admin-input" rows="2" placeholder="Write a short summary..."><?php echo h($item['excerpt'] ?? ''); ?></textarea>
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Content (HTML or plain copy)</label>
        <textarea name="content" class="admin-input" rows="8" placeholder="Write the main copy here..."><?php echo h($item['content'] ?? ''); ?></textarea>
      </div>

      <!-- SEO section -->
      <div style="border-top: 1px solid var(--color-border); padding-top: 1.5rem; margin-top: 2rem; margin-bottom: 1.5rem;">
        <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 1rem; font-family: var(--font-secondary);">SEO Configuration (Custom overrides)</h4>
        
        <div class="admin-form-group">
          <label class="admin-label">SEO Meta Title</label>
          <input type="text" name="seo_title" class="admin-input" value="<?php echo h($item['seo_title'] ?? ''); ?>" placeholder="Defaults to post title if empty">
        </div>

        <div class="admin-form-group">
          <label class="admin-label">SEO Meta Description</label>
          <textarea name="meta_description" class="admin-input" rows="2" placeholder="Defaults to excerpt if empty"><?php echo h($item['meta_description'] ?? ''); ?></textarea>
        </div>

        <div class="admin-form-group">
          <label class="admin-label">Canonical URL</label>
          <input type="text" name="canonical_url" class="admin-input" value="<?php echo h($item['canonical_url'] ?? ''); ?>" placeholder="e.g. https://zuvioglobalschool.com/blogs/article-slug">
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2.5rem;">Save Article</button>
      <a href="/admin/blogs" class="btn btn-outline" style="padding: 0.8rem 2rem; margin-left: 0.75rem;">Cancel</a>
    </form>
  </div>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
