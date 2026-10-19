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
    if (!isset($_FILES[$file_post_key])) {
        return null;
    }
    
    $file_error = $_FILES[$file_post_key]['error'];
    if ($file_error === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    if ($file_error === UPLOAD_ERR_INI_SIZE || $file_error === UPLOAD_ERR_FORM_SIZE) {
        throw new Exception("The uploaded image exceeds the maximum allowed file size (4MB).");
    } elseif ($file_error === UPLOAD_ERR_PARTIAL) {
        throw new Exception("The image file was only partially uploaded. Please try again.");
    } elseif ($file_error !== UPLOAD_ERR_OK) {
        throw new Exception("File upload failed (error code: " . (int)$file_error . ").");
    }
    
    $file = $_FILES[$file_post_key];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
    
    // 1. Check extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_exts)) {
        throw new Exception("Invalid file extension (." . $ext . "). Only JPG, PNG, and WEBP images are allowed.");
    }
    
    // 2. Limit to 4MB
    if ($file['size'] > 4 * 1024 * 1024) {
        throw new Exception("File is too large. Maximum allowed size is 4MB.");
    }

    // 3. Ensure temporary file exists and is readable
    if (!file_exists($file['tmp_name']) || !is_readable($file['tmp_name']) || filesize($file['tmp_name']) === 0) {
        throw new Exception("Uploaded file could not be read or is empty.");
    }

    // 4. Verify server has reliable image inspection capabilities
    $has_finfo = function_exists('finfo_open');
    $has_mime_content_type = function_exists('mime_content_type');
    $has_getimagesize = function_exists('getimagesize');

    if (!$has_finfo && !$has_mime_content_type && !$has_getimagesize) {
        throw new Exception("Image upload rejected: reliable image validation capabilities are unavailable on this server.");
    }

    // 5. Inspect image content using getimagesize
    $img_info = false;
    if ($has_getimagesize) {
        $img_info = @getimagesize($file['tmp_name']);
        if ($img_info === false) {
            throw new Exception("The uploaded file does not contain valid image data.");
        }
    }

    // 6. Detect MIME type directly from file content (never trust extension alone)
    $mime = null;
    if ($has_finfo) {
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mime = @finfo_file($finfo, $file['tmp_name']);
            @finfo_close($finfo);
        }
    }
    if (!$mime && $has_mime_content_type) {
        $mime = @mime_content_type($file['tmp_name']);
    }
    if (!$mime && $img_info && !empty($img_info['mime'])) {
        $mime = $img_info['mime'];
    }

    if (!$mime) {
        throw new Exception("Unable to verify the image content type. Please ensure the file is a genuine JPG, PNG, or WEBP image.");
    }

    // Normalize variant mime types
    if ($mime === 'image/pjpeg') $mime = 'image/jpeg';
    if ($mime === 'image/x-png') $mime = 'image/png';

    if (!in_array($mime, $allowed_types)) {
        throw new Exception("Invalid file content type ({$mime}). Only genuine JPG, PNG, and WEBP images are allowed.");
    }

    // Double-check image signature matches supported types if getimagesize is available
    if ($img_info && isset($img_info[2])) {
        $img_type = $img_info[2];
        $valid_types = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
        if (defined('IMAGETYPE_WEBP')) {
            $valid_types[] = IMAGETYPE_WEBP;
        }
        if (!in_array($img_type, $valid_types)) {
            throw new Exception("The uploaded file content does not match a supported image format.");
        }
    }

    // Generate unique name and target path
    $new_name = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    
    $upload_dir = dirname(__FILE__) . '/../uploads/';
    if (!is_dir($upload_dir)) {
        if (!@mkdir($upload_dir, 0755, true) && !is_dir($upload_dir)) {
            throw new Exception("Upload directory could not be created. Please verify server permissions on uploads/.");
        }
    }
    
    $storage_path = 'uploads/' . $new_name;
    $target_path = $upload_dir . $new_name;
    
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $public_url = '/' . $storage_path;
        
        // Get dimensions safely
        $width = null;
        $height = null;
        if (function_exists('getimagesize')) {
            $sizes = @getimagesize($target_path);
            if ($sizes) {
                $width = $sizes[0];
                $height = $sizes[1];
            }
        }
        
        // Insert media registry safely (wrapped so media table failure doesn't abort blog creation)
        if ($db) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO `media` (`file_name`, `storage_path`, `public_url`, `mime_type`, `file_size`, `width`, `height`)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$file['name'], $storage_path, $public_url, $mime ?: ('image/' . $ext), $file['size'], $width, $height]);
            } catch (Throwable $me) {
                error_log("[Media Registry Warning] " . $me->getMessage());
            }
        }
        
        return $public_url;
    }
    
    throw new Exception("Failed to move uploaded file. Please verify directory permissions for uploads/.");
}

// Helper function to resolve deterministic unique slug
function resolve_unique_blog_slug($db, $base_slug, $exclude_id = 0) {
    if (!$db || empty($base_slug)) {
        return $base_slug ?: 'article';
    }
    $slug = $base_slug;
    $counter = 1;
    while (true) {
        if ($exclude_id > 0) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM `blogs` WHERE `slug` = ? AND `id` != ?");
            $stmt->execute([$slug, $exclude_id]);
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM `blogs` WHERE `slug` = ?");
            $stmt->execute([$slug]);
        }
        $count = (int)$stmt->fetchColumn();
        if ($count === 0) {
            return $slug;
        }
        $counter++;
        $slug = $base_slug . '-' . $counter;
    }
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
            
            try {
                log_audit('BLOG_DELETED', 'blogs', 'blogs', $id, $old_blog, null, "Deleted blog article '" . ($old_blog['title'] ?? '') . "'");
            } catch (Throwable $ae) {
                error_log("[Blog Delete Audit Warning] " . $ae->getMessage());
            }
            
            header('Location: /admin/blogs?msg=deleted');
            exit;
        } catch (Throwable $e) {
            error_log("[Blog Delete Error] " . $e->getMessage());
            $error = "Could not delete article: " . $e->getMessage();
        }
    }
}

// Fetch categories for forms
$categories = [];
if ($db) {
    try {
        $categories = $db->query("SELECT * FROM `blog_categories` ORDER BY `name` ASC")->fetchAll();
    } catch (Throwable $e) {
        error_log("[CRUD Category Fetch Error] " . $e->getMessage());
    }
}

// Category Action Handlers
if ($action === 'add_category' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_permission('blogs.edit');
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed.';
    } else {
        $cat_name = trim($_POST['cat_name'] ?? '');
        $cat_slug = trim($_POST['cat_slug'] ?? '') ?: generate_blog_slug($cat_name);
        $cat_desc = trim($_POST['cat_desc'] ?? '');
        if ($cat_name && $db) {
            try {
                try {
                    $stmt = $db->prepare("INSERT INTO `blog_categories` (`name`, `slug`, `description`) VALUES (?, ?, ?)");
                    $stmt->execute([$cat_name, $cat_slug, $cat_desc]);
                } catch (PDOException $pe) {
                    // Fallback if description column does not exist in schema
                    $stmt = $db->prepare("INSERT INTO `blog_categories` (`name`, `slug`) VALUES (?, ?)");
                    $stmt->execute([$cat_name, $cat_slug]);
                }
                header('Location: /admin/blogs?tab=categories&msg=cat_added');
                exit;
            } catch (Throwable $e) {
                error_log("[Category Add Error] " . $e->getMessage());
                $error = 'Category error: ' . $e->getMessage();
            }
        }
    }
}

if ($action === 'delete_category' && $id > 0) {
    require_permission('blogs.delete');
    if ($db) {
        try {
            $stmt = $db->prepare("DELETE FROM `blog_categories` WHERE `id` = ?");
            $stmt->execute([$id]);
            header('Location: /admin/blogs?tab=categories&msg=cat_deleted');
            exit;
        } catch (Throwable $e) {
            error_log("[Category Delete Error] " . $e->getMessage());
            $error = 'Category delete error: ' . $e->getMessage();
        }
    }
}

// AJAX Image Upload from Rich Text Editor
if ($action === 'upload_editor_image' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_permission('blogs.edit');
    header('Content-Type: application/json');
    
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'Security check failed. Please refresh and try again.']);
        exit;
    }
    
    try {
        $uploaded_url = handle_file_upload('image');
        if (!$uploaded_url) {
            echo json_encode(['success' => false, 'error' => 'No image uploaded or upload failed.']);
            exit;
        }
        echo json_encode(['success' => true, 'url' => $uploaded_url]);
        exit;
    } catch (Throwable $e) {
        error_log("[Editor Upload Error] " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// AJAX Media Library List for Editor
if ($action === 'get_media_library') {
    require_permission('blogs.view');
    header('Content-Type: application/json');
    $media_list = [];
    if ($db) {
        try {
            $stmt = $db->query("
                SELECT `id`, `file_name`, `public_url`, `created_at` 
                FROM `media` 
                WHERE `mime_type` LIKE 'image/%' 
                ORDER BY `id` DESC 
                LIMIT 24
            ");
            $media_list = $stmt->fetchAll();
        } catch (Throwable $e) {
            error_log("[Media Library Fetch Error] " . $e->getMessage());
        }
    }
    $static_images = [
        ['public_url' => '/assets/images/logo.png', 'file_name' => 'logo.png'],
        ['public_url' => '/assets/images/zuvio-logo.png', 'file_name' => 'zuvio-logo.png'],
        ['public_url' => '/assets/images/Hero image 1.png', 'file_name' => 'Hero image 1.png'],
    ];
    echo json_encode(['success' => true, 'media' => $media_list, 'static' => $static_images]);
    exit;
}

// 2. Action Handler: Save (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'add' || $action === 'edit')) {
    if ($action === 'add') {
        require_permission('blogs.create');
    } else {
        require_permission('blogs.edit');
    }
    
    // If attempting to publish, enforce blogs.publish permission
    $status = (isset($_POST['status']) && $_POST['status'] === 'published') ? 'published' : 'draft';
    if ($status === 'published') {
        require_permission('blogs.publish');
    }

    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
        $item = [
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'slug_mode' => trim($_POST['slug_mode'] ?? ($action === 'edit' ? 'manual' : 'auto')),
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'author' => trim($_POST['author'] ?? ''),
            'author_designation' => trim($_POST['author_designation'] ?? ''),
            'publish_date' => !empty($_POST['publish_date']) ? $_POST['publish_date'] : date('Y-m-d'),
            'status' => $status,
            'featured_image' => $_POST['existing_featured_image'] ?? '',
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => $_POST['content'] ?? '',
            'seo_title' => trim($_POST['seo_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'canonical_url' => trim($_POST['canonical_url'] ?? ''),
        ];
    } elseif (!$db) {
        $error = 'Database service is currently unavailable. Please try again.';
        error_log("[Blog CMS Error] DB connection is null during save POST.");
        $item = [
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'slug_mode' => trim($_POST['slug_mode'] ?? ($action === 'edit' ? 'manual' : 'auto')),
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'author' => trim($_POST['author'] ?? ''),
            'author_designation' => trim($_POST['author_designation'] ?? ''),
            'publish_date' => !empty($_POST['publish_date']) ? $_POST['publish_date'] : date('Y-m-d'),
            'status' => $status,
            'featured_image' => $_POST['existing_featured_image'] ?? '',
            'excerpt' => trim($_POST['excerpt'] ?? ''),
            'content' => $_POST['content'] ?? '',
            'seo_title' => trim($_POST['seo_title'] ?? ''),
            'meta_description' => trim($_POST['meta_description'] ?? ''),
            'canonical_url' => trim($_POST['canonical_url'] ?? ''),
        ];
    } else {
        $title = trim($_POST['title'] ?? '');
        $raw_slug = trim($_POST['slug'] ?? '');
        $submitted_slug_mode = trim($_POST['slug_mode'] ?? '');
        
        // Distinguish automatic and manual slug intent reliably:
        // Automatic mode is active if explicitly submitted as 'auto' or if raw_slug is empty.
        // Otherwise (including explicit 'manual', or non-empty slug with default mode), it is treated as manual intent.
        $is_auto_slug = (empty($raw_slug) || $submitted_slug_mode === 'auto');
        $slug_mode = $is_auto_slug ? 'auto' : 'manual';
        
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = sanitize_blog_html(trim($_POST['content'] ?? ''));
        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $author = trim($_POST['author'] ?? '');
        $author_designation = trim($_POST['author_designation'] ?? '');
        $publish_date = !empty($_POST['publish_date']) ? $_POST['publish_date'] : date('Y-m-d');
        
        $seo_title = trim($_POST['seo_title'] ?? '');
        $meta_description = trim($_POST['meta_description'] ?? '');
        $canonical_url = trim($_POST['canonical_url'] ?? '');

        // Validate category FK against database to prevent FK constraint failures
        if ($category_id !== null) {
            try {
                $c_check = $db->prepare("SELECT `id` FROM `blog_categories` WHERE `id` = ? LIMIT 1");
                $c_check->execute([$category_id]);
                if (!$c_check->fetch()) {
                    $category_id = null;
                }
            } catch (Throwable $cte) {
                $category_id = null;
            }
        }
        
        // Populate $item so user never loses their form inputs if any error occurs
        $item = [
            'title' => $title,
            'slug' => $raw_slug,
            'slug_mode' => $slug_mode,
            'category_id' => $category_id,
            'author' => $author,
            'author_designation' => $author_designation,
            'publish_date' => $publish_date,
            'status' => $status,
            'featured_image' => $_POST['existing_featured_image'] ?? '',
            'excerpt' => $excerpt,
            'content' => $_POST['content'] ?? '',
            'seo_title' => $seo_title,
            'meta_description' => $meta_description,
            'canonical_url' => $canonical_url,
        ];

        if (empty($title)) {
            $error = 'Title is required.';
        } else {
            // Slug derivation and normalization
            $slug = !empty($raw_slug) ? generate_blog_slug($raw_slug) : generate_blog_slug($title);
            if (empty($slug)) {
                $slug = generate_blog_slug($title);
            }
            if (empty($slug)) {
                $error = 'A valid URL-friendly slug or title is required.';
            } else {
                $item['slug'] = $slug;
                
                try {
                    // Check for slug collisions in database
                    $slug_check_query = "SELECT `id` FROM `blogs` WHERE `slug` = ?" . ($action === 'edit' && $id > 0 ? " AND `id` != ?" : "") . " LIMIT 1";
                    $slug_check_stmt = $db->prepare($slug_check_query);
                    $slug_check_params = ($action === 'edit' && $id > 0) ? [$slug, $id] : [$slug];
                    $slug_check_stmt->execute($slug_check_params);
                    $existing_slug_record = $slug_check_stmt->fetch();

                    if ($existing_slug_record) {
                        // Automatically resolve collisions only for genuinely automatic slugs;
                        // show a clear validation error for duplicate manually entered slugs.
                        if ($is_auto_slug) {
                            $slug = resolve_unique_blog_slug($db, $slug, ($action === 'edit' && $id > 0) ? $id : 0);
                            $item['slug'] = $slug;
                        } else {
                            $error = "The slug '" . h($slug) . "' is already in use by another article. Please enter a unique slug.";
                        }
                    }

                    if (empty($error)) {
                        // Handle optional image upload
                        $uploaded_url = handle_file_upload('featured_image_file');
                        
                        if ($action === 'add') {
                            $featured_image = $uploaded_url ?: '';
                            $item['featured_image'] = $featured_image;
                            
                            $stmt = $db->prepare("
                                INSERT INTO `blogs` 
                                (`title`, `slug`, `excerpt`, `content`, `featured_image`, `author`, `author_designation`, `category_id`, `publish_date`, `status`, `seo_title`, `meta_description`, `canonical_url`)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                            ");
                            $stmt->execute([
                                $title, $slug, $excerpt, $content, $featured_image, $author, $author_designation, $category_id, $publish_date, $status, $seo_title, $meta_description, $canonical_url
                            ]);
                            $new_id = (int)$db->lastInsertId();
                            
                            // Safe audit logging (post-insert failure will not break redirect or retry)
                            try {
                                log_audit('BLOG_CREATED', 'blogs', 'blogs', $new_id, null, ['title' => $title, 'slug' => $slug], "Created blog article '{$title}'");
                            } catch (Throwable $ae) {
                                error_log("[Blog Audit Log Warning] " . $ae->getMessage());
                            }
                            
                            header('Location: /admin/blogs?msg=added');
                            exit;
                        } else {
                            // Update flow
                            $featured_image = $_POST['existing_featured_image'] ?? '';
                            if ($uploaded_url) {
                                $featured_image = $uploaded_url;
                            }
                            $item['featured_image'] = $featured_image;
                            
                            $old_blog = null;
                            try {
                                $old_stmt = $db->prepare("SELECT * FROM `blogs` WHERE `id` = ? LIMIT 1");
                                $old_stmt->execute([$id]);
                                $old_blog = $old_stmt->fetch();
                            } catch (Throwable $fe) {
                                // Ignore error reading old blog state
                            }
                            
                            $stmt = $db->prepare("
                                UPDATE `blogs` 
                                SET `title` = ?, `slug` = ?, `excerpt` = ?, `content` = ?, `featured_image` = ?, `author` = ?, `author_designation` = ?, `category_id` = ?, `publish_date` = ?, `status` = ?, `seo_title` = ?, `meta_description` = ?, `canonical_url` = ?
                                WHERE `id` = ?
                            ");
                            $stmt->execute([
                                $title, $slug, $excerpt, $content, $featured_image, $author, $author_designation, $category_id, $publish_date, $status, $seo_title, $meta_description, $canonical_url, $id
                            ]);
                            
                            try {
                                log_audit('BLOG_UPDATED', 'blogs', 'blogs', $id, $old_blog, ['title' => $title, 'slug' => $slug], "Updated blog article '{$title}'");
                            } catch (Throwable $ae) {
                                error_log("[Blog Audit Log Warning] " . $ae->getMessage());
                            }
                            
                            header('Location: /admin/blogs?msg=updated');
                            exit;
                        }
                    }
                } catch (PDOException $pe) {
                    error_log("[Blog CMS DB Error] " . $pe->getMessage());
                    if ($pe->getCode() == 23000 || strpos($pe->getMessage(), 'Duplicate entry') !== false || strpos($pe->getMessage(), 'UNIQUE') !== false) {
                        $error = "An article with the slug '" . h($slug) . "' already exists. Please choose a different slug.";
                    } else {
                        $error = "A database error occurred while saving the article. Please check your inputs and try again.";
                    }
                } catch (Throwable $e) {
                    error_log("[Blog CMS Error] " . $e->getMessage() . "\n" . $e->getTraceAsString());
                    $error = $e->getMessage();
                }
            }
        }
    }
}

// 3. Action Handler: Fetch active item for edit form (only if not already loaded from POST)
$item = $item ?? null;
if ($action === 'edit' && $id > 0 && $item === null) {
    if (!$db) {
        $error = "Database offline.";
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM `blogs` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if (!$item) {
                header('Location: /admin/blogs?error=notfound');
                exit;
            }
        } catch (Throwable $e) {
            error_log("[Blog Edit Load Error] " . $e->getMessage());
            $error = "Error loading article details.";
        }
    }
}

// 4. Action Handler: Listing
$posts = [];
if ($action === 'list') {
    if ($db) {
        try {
            $posts = $db->query("
                SELECT b.*, c.name as category_name 
                FROM `blogs` b 
                LEFT JOIN `blog_categories` c ON c.id = b.category_id 
                ORDER BY b.created_at DESC
            ")->fetchAll();
        } catch (Throwable $e) {
            error_log("[Blog Listing Error] " . $e->getMessage());
            $error = "Database queries failed.";
        }
    } else {
        $error = "Database service offline.";
    }
}

$page_slug = 'admin-blogs';
$tab = $_GET['tab'] ?? 'posts';

$page_slug = 'admin-blogs';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">Blogs & Insights Manager</h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">Create, edit, publish, draft articles and manage blog categories.</p>
  </div>
  <?php if ($action === 'list'): ?>
    <div style="display: flex; gap: 0.6rem;">
      <a href="/admin/blogs?tab=categories" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Manage Categories (<?php echo count($categories); ?>)</a>
      <a href="/admin/blogs?action=add" class="btn btn-primary" style="font-size: 0.85rem;">+ Create New Blog</a>
    </div>
  <?php else: ?>
    <a href="/admin/blogs" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">&larr; Back to List</a>
  <?php endif; ?>
</div>

<!-- Tabs -->
<?php if ($action === 'list'): ?>
  <div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); margin-bottom: 1.5rem;">
    <a href="/admin/blogs?tab=posts" style="padding: 0.6rem 1.25rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'posts' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'posts' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      All Articles (<?php echo count($posts); ?>)
    </a>
    <a href="/admin/blogs?tab=categories" style="padding: 0.6rem 1.25rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'categories' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'categories' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      Blog Categories (<?php echo count($categories); ?>)
    </a>
  </div>
<?php endif; ?>

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
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'cat_added'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    New category created successfully.
  </div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'cat_deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    Category deleted successfully.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<?php if ($action === 'list' && $tab === 'categories'): ?>
  <!-- Category Management Tab -->
  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
    <div class="card" style="border-left: none; padding: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.2rem; margin-top: 0; margin-bottom: 1rem;">Active Blog Categories</h3>
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
        <thead>
          <tr style="border-bottom: 2px solid var(--color-border); color: var(--color-navy); font-weight: 600;">
            <th style="padding: 0.75rem 1rem;">Category Name</th>
            <th style="padding: 0.75rem 1rem;">Slug</th>
            <th style="padding: 0.75rem 1rem;">Description</th>
            <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $c): ?>
            <tr style="border-bottom: 1px solid var(--color-border);">
              <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--color-navy);"><?php echo h($c['name']); ?></td>
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><code><?php echo h($c['slug']); ?></code></td>
              <td style="padding: 0.75rem 1rem; color: var(--color-muted);"><?php echo h($c['description'] ?? '—'); ?></td>
              <td style="padding: 0.75rem 1rem; text-align: right;">
                <a href="/admin/blogs?action=delete_category&id=<?php echo $c['id']; ?>" onclick="return confirm('Delete this category?');" style="color: #EF4444; font-weight: 600; text-decoration: none;">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Add Category Form -->
    <div class="card" style="border-left: none; padding: 2rem; border-top: 4px solid var(--color-gold);">
      <h3 style="color: var(--color-navy); font-size: 1.15rem; margin-top: 0; margin-bottom: 1.25rem;">Add New Category</h3>
      <form method="POST" action="/admin/blogs?action=add_category">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <div class="admin-form-group">
          <label class="admin-label">Category Name *</label>
          <input type="text" name="cat_name" required placeholder="e.g. EdTech & AI" class="admin-input">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Slug (Optional)</label>
          <input type="text" name="cat_slug" placeholder="e.g. edtech-and-ai" class="admin-input">
        </div>
        <div class="admin-form-group">
          <label class="admin-label">Description</label>
          <textarea name="cat_desc" rows="3" placeholder="Brief summary of topic..." class="admin-input"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Create Category</button>
      </form>
    </div>
  </div>

<?php elseif ($action === 'list' && $tab === 'posts'): ?>
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
  <link rel="stylesheet" href="/css/rich-editor.css">
  <div class="card" style="border-left: none; padding: 2.5rem;">
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
      <input type="hidden" name="slug_mode" id="blog-slug-mode" value="<?php echo h($item['slug_mode'] ?? ($action === 'edit' ? 'manual' : 'auto')); ?>">
      <?php if ($action === 'edit'): ?>
        <input type="hidden" name="existing_featured_image" value="<?php echo h($item['featured_image']); ?>">
      <?php endif; ?>

      <div class="admin-form-group">
        <label class="admin-label" for="blog-title-input">Title *</label>
        <input type="text" name="title" id="blog-title-input" required class="admin-input" value="<?php echo h($item['title'] ?? ''); ?>" placeholder="Enter blog title" autocomplete="off">
      </div>

      <div class="grid-2">
        <div class="admin-form-group">
          <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.35rem;">
            <label class="admin-label" for="blog-slug-input" style="margin-bottom: 0;">Slug (URL identifier)</label>
            <span id="slug-status-hint" style="font-size: 0.75rem; color: var(--color-muted); font-weight: 500;"></span>
          </div>
          <input type="text" name="slug" id="blog-slug-input" class="admin-input" value="<?php echo h($item['slug'] ?? ''); ?>" placeholder="e.g. why-online-learning-is-transforming-education">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.35rem; font-size: 0.78rem; color: var(--color-muted); flex-wrap: wrap; gap: 0.25rem;">
            <span>URL: <code style="color: var(--color-navy); font-weight: 600;">/blogs/<span id="slug-preview-text"><?php echo h($item['slug'] ?? 'url-slug'); ?></span></code></span>
            <button type="button" id="slug-resync-btn" style="background: none; border: none; color: var(--color-teal); cursor: pointer; padding: 0; font-size: 0.78rem; text-decoration: underline; display: none;">Re-sync with Title</button>
          </div>
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
        <label class="admin-label">Article Content *</label>
        <textarea name="content" id="blog-content-field" class="admin-input" rows="8" placeholder="Write the main copy here..."><?php echo h($item['content'] ?? ''); ?></textarea>
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
  <script src="/js/rich-editor.js"></script>
  <script>
  (function() {
    'use strict';
    var titleInput = document.getElementById('blog-title-input');
    var slugInput = document.getElementById('blog-slug-input');
    var slugModeInput = document.getElementById('blog-slug-mode');
    var previewText = document.getElementById('slug-preview-text');
    var statusHint = document.getElementById('slug-status-hint');
    var resyncBtn = document.getElementById('slug-resync-btn');
    var isEditMode = <?php echo ($action === 'edit' && !empty($item['id'])) ? 'true' : 'false'; ?>;

    if (!titleInput || !slugInput) return;

    // Clean reusable slug generator matching server rules
    function generateSlug(text) {
      if (!text) return '';
      return text
        .toString()
        .replace(/['’]/g, '')            // Remove apostrophes: e.g. Parent's -> Parents
        .replace(/[–—_]/g, '-')          // Convert en/em-dashes and underscores to hyphen
        .toLowerCase()                   // Convert to lowercase
        .trim()                          // Trim whitespace
        .replace(/[^a-z0-9\-]+/g, '-')   // Replace punctuation and spaces with single hyphen
        .replace(/-+/g, '-')             // Remove repeated hyphens
        .replace(/^-+|-+$/g, '');        // Remove hyphens from start and end
    }

    // Determine initial mode:
    // 1. If hidden input has an explicit mode ('auto' or 'manual'), use it
    // 2. Otherwise in edit mode, default to 'manual' (preserve saved slug)
    // 3. In add mode, default to 'auto' unless a custom slug was already entered
    var currentMode = 'auto';
    if (slugModeInput && (slugModeInput.value === 'auto' || slugModeInput.value === 'manual')) {
      currentMode = slugModeInput.value;
    } else if (isEditMode) {
      currentMode = 'manual';
    } else if (slugInput.value.trim() !== '') {
      currentMode = 'manual';
    }

    function updateUiState() {
      if (slugModeInput) {
        slugModeInput.value = currentMode;
      }

      var currentSlug = slugInput.value.trim();
      var derivedSlug = generateSlug(titleInput.value);

      if (previewText) {
        previewText.textContent = currentSlug || derivedSlug || 'url-slug';
      }

      if (statusHint) {
        if (currentMode === 'manual') {
          statusHint.textContent = '(manually edited)';
          statusHint.style.color = '#B45309';
        } else {
          statusHint.textContent = '(auto-generated)';
          statusHint.style.color = 'var(--color-muted)';
        }
      }

      if (resyncBtn) {
        // Show re-sync button when in manual mode and title is present
        var canResync = (currentMode === 'manual') && (titleInput.value.trim() !== '');
        resyncBtn.style.display = canResync ? 'inline-block' : 'none';
      }
    }

    // Dynamic auto-generation while typing title in automatic mode
    titleInput.addEventListener('input', function() {
      if (currentMode === 'auto') {
        slugInput.value = generateSlug(titleInput.value);
      }
      updateUiState();
    });

    // Detect manual editing or clearing of slug field
    slugInput.addEventListener('input', function() {
      // If admin completely clears the slug field, restore automatic mode
      if (slugInput.value.trim() === '') {
        currentMode = 'auto';
        var derived = generateSlug(titleInput.value);
        if (derived !== '') {
          slugInput.value = derived;
        }
      } else {
        // Any explicit typing marks it as manual intent,
        // even if the text matches the title-derived slug
        currentMode = 'manual';
      }
      updateUiState();
    });

    // One-click re-sync with title: restores automatic mode
    if (resyncBtn) {
      resyncBtn.addEventListener('click', function(e) {
        e.preventDefault();
        currentMode = 'auto';
        slugInput.value = generateSlug(titleInput.value);
        updateUiState();
        slugInput.focus();
      });
    }

    // Initial state setup on load
    // In add mode, if slug is empty but title exists, auto-populate
    if (!isEditMode && currentMode === 'auto' && slugInput.value.trim() === '' && titleInput.value.trim() !== '') {
      slugInput.value = generateSlug(titleInput.value);
    }
    updateUiState();
  })();
  </script>
<?php endif; ?>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
