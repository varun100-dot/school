<?php
// Zuvio Global School - Admin Hero Slides Manager (Individual Save & Idempotent Sync)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();
if (!has_permission('hero.view') && !in_array($_SESSION['role_name'] ?? '', ['admin', 'super_admin'])) {
    header('HTTP/1.1 403 Forbidden');
    echo "<h1>403 Forbidden</h1><p>You do not have administrative privileges to manage hero slides.</p>";
    exit;
}

$action = $_GET['action'] ?? 'list';
$msg = $_GET['msg'] ?? '';
$error = $_GET['error_msg'] ?? '';

// Helper function to snapshot the current state of a slide
function create_slide_snapshot($slide_id, $change_summary = '') {
    global $db;
    if (!$db) return false;
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

// -----------------------------------------------------------------------------
// POST HANDLER 1: Save Individual Banner (Update or Confirm Deletion)
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_individual_banner') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please refresh and try again.';
    } else {
        $banner_id = (int)($_POST['banner_id'] ?? 0);
        $pending_action = trim($_POST['pending_action'] ?? 'save');
        
        if ($banner_id <= 0) {
            $error = 'Invalid banner identifier.';
        } elseif ($pending_action === 'delete') {
            // Commit deletion only after explicit user save
            if ($db) {
                try {
                    $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
                    $stmt->execute([$banner_id]);
                    $old_slide = $stmt->fetch();
                    if ($old_slide) {
                        create_slide_snapshot($banner_id, 'Slide deleted via individual banner save');
                        $del_stmt = $db->prepare("DELETE FROM `hero_slides` WHERE `id` = ?");
                        $del_stmt->execute([$banner_id]);
                        if (function_exists('log_audit')) {
                            log_audit('HERO_DELETED', 'hero', 'hero_slides', $banner_id, $old_slide, null, "Deleted hero slide {$banner_id}");
                        }
                    }
                } catch (Exception $e) {
                    $error = 'Delete error: ' . $e->getMessage();
                }
            } else {
                if (isset($_SESSION['mock_hero_slides'])) {
                    foreach ($_SESSION['mock_hero_slides'] as $k => $sl) {
                        if ($sl['id'] === $banner_id) {
                            unset($_SESSION['mock_hero_slides'][$k]);
                            break;
                        }
                    }
                    $_SESSION['mock_hero_slides'] = array_values($_SESSION['mock_hero_slides']);
                }
            }
            if (!$error) {
                header("Location: /admin/hero.php?msg=deleted&id=" . $banner_id);
                exit;
            }
        } else {
            // Commit update
            $title = trim($_POST['title'] ?? '');
            $subtitle = trim($_POST['subtitle'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $image = trim($_POST['image'] ?? '');
            $primary_cta_text = trim($_POST['primary_cta_text'] ?? '');
            $primary_cta_url = trim($_POST['primary_cta_url'] ?? '');
            $secondary_cta_text = trim($_POST['secondary_cta_text'] ?? '');
            $secondary_cta_url = trim($_POST['secondary_cta_url'] ?? '');
            $sort_order = (int)($_POST['sort_order'] ?? 1);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Handle file upload if provided for this banner
            $file_key = 'slide_image_file_' . $banner_id;
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
                $f = $_FILES[$file_key];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $up_name = 'hero_' . $banner_id . '_' . time() . '.' . $ext;
                    $up_dir = dirname(__FILE__) . '/../uploads/';
                    if (!is_dir($up_dir)) mkdir($up_dir, 0755, true);
                    if (move_uploaded_file($f['tmp_name'], $up_dir . $up_name)) {
                        $image = '/uploads/' . $up_name;
                    }
                }
            }
            
            if (empty($title)) {
                $error = "Banner #{$banner_id} requires a title.";
            } else {
                if ($db) {
                    try {
                        $stmt = $db->prepare("SELECT * FROM `hero_slides` WHERE `id` = ? LIMIT 1");
                        $stmt->execute([$banner_id]);
                        $old_slide = $stmt->fetch();
                        if (empty($image) && $old_slide) {
                            $image = $old_slide['image'];
                        }
                        
                        $up_stmt = $db->prepare("
                            UPDATE `hero_slides` 
                            SET `title` = ?, `subtitle` = ?, `description` = ?, `image` = ?, `primary_cta_text` = ?, `primary_cta_url` = ?, `secondary_cta_text` = ?, `secondary_cta_url` = ?, `sort_order` = ?, `is_active` = ?
                            WHERE `id` = ?
                        ");
                        $up_stmt->execute([
                            $title, $subtitle, $description, $image, $primary_cta_text, $primary_cta_url, $secondary_cta_text, $secondary_cta_url, $sort_order, $is_active, $banner_id
                        ]);
                        
                        create_slide_snapshot($banner_id, 'Updated banner details via individual save');
                        if (function_exists('log_audit')) {
                            log_audit('HERO_UPDATED', 'hero', 'hero_slides', $banner_id, $old_slide, ['title' => $title], "Updated hero slide {$banner_id}");
                        }
                    } catch (Exception $e) {
                        $error = 'Save error: ' . $e->getMessage();
                    }
                } else {
                    if (isset($_SESSION['mock_hero_slides'])) {
                        foreach ($_SESSION['mock_hero_slides'] as &$sl) {
                            if ($sl['id'] === $banner_id) {
                                $sl['title'] = $title;
                                $sl['subtitle'] = $subtitle;
                                $sl['description'] = $description;
                                if (!empty($image)) $sl['image'] = $image;
                                $sl['primary_cta_text'] = $primary_cta_text;
                                $sl['primary_cta_url'] = $primary_cta_url;
                                $sl['secondary_cta_text'] = $secondary_cta_text;
                                $sl['secondary_cta_url'] = $secondary_cta_url;
                                $sl['sort_order'] = $sort_order;
                                $sl['is_active'] = $is_active;
                                break;
                            }
                        }
                        unset($sl);
                    }
                }
                
                if (!$error) {
                    header("Location: /admin/hero.php?msg=saved&id=" . $banner_id);
                    exit;
                }
            }
        }
    }
}

// -----------------------------------------------------------------------------
// POST HANDLER 2: Add New Banner
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_new_banner') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image = trim($_POST['image'] ?? '/assets/images/zuvio_hero_banner_1.png');
        $primary_cta_text = trim($_POST['primary_cta_text'] ?? 'Enrol Now');
        $primary_cta_url = trim($_POST['primary_cta_url'] ?? '/admissions#enrol');
        $secondary_cta_text = trim($_POST['secondary_cta_text'] ?? 'Our Curriculum');
        $secondary_cta_url = trim($_POST['secondary_cta_url'] ?? '/curriculum');
        $sort_order = (int)($_POST['sort_order'] ?? 1);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        // Handle image upload
        if (isset($_FILES['new_slide_image_file']) && $_FILES['new_slide_image_file']['error'] === UPLOAD_ERR_OK) {
            $f = $_FILES['new_slide_image_file'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $up_name = 'hero_new_' . time() . '.' . $ext;
                $up_dir = dirname(__FILE__) . '/../uploads/';
                if (!is_dir($up_dir)) mkdir($up_dir, 0755, true);
                if (move_uploaded_file($f['tmp_name'], $up_dir . $up_name)) {
                    $image = '/uploads/' . $up_name;
                }
            }
        }
        
        if (empty($title)) {
            $error = 'Title is required for the new banner.';
        } else {
            if ($db) {
                try {
                    $stmt = $db->prepare("
                        INSERT INTO `hero_slides` 
                        (`title`, `subtitle`, `description`, `image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $title, $subtitle, $description, $image, $primary_cta_text, $primary_cta_url, $secondary_cta_text, $secondary_cta_url, $sort_order, $is_active
                    ]);
                    $new_id = (int)$db->lastInsertId();
                    create_slide_snapshot($new_id, 'Created new banner');
                    if (function_exists('log_audit')) {
                        log_audit('HERO_CREATED', 'hero', 'hero_slides', $new_id, null, ['title' => $title], "Created hero slide {$new_id}");
                    }
                } catch (Exception $e) {
                    $error = 'Insert error: ' . $e->getMessage();
                }
            } else {
                if (!isset($_SESSION['mock_hero_slides'])) {
                    $_SESSION['mock_hero_slides'] = [];
                }
                $new_id = count($_SESSION['mock_hero_slides']) + 1;
                $_SESSION['mock_hero_slides'][] = [
                    'id' => $new_id,
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'description' => $description,
                    'image' => $image,
                    'primary_cta_text' => $primary_cta_text,
                    'primary_cta_url' => $primary_cta_url,
                    'secondary_cta_text' => $secondary_cta_text,
                    'secondary_cta_url' => $secondary_cta_url,
                    'sort_order' => $sort_order,
                    'is_active' => $is_active
                ];
            }
            if (!$error) {
                header("Location: /admin/hero.php?msg=added");
                exit;
            }
        }
    }
}

// -----------------------------------------------------------------------------
// ACTION HANDLER: Idempotent Sync / Reset to Standard Slides
// -----------------------------------------------------------------------------
if ($action === 'sync_defaults') {
    // IDEMPOTENT: Updates or syncs the 3 standard banners strictly by ID
    // NEVER duplicates banners!
    $standards = [
        1 => [
            'title' => 'Global Standard Learning',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Academic excellence meets personalised online learning for Grades K to 8.',
            'image' => '/assets/images/zuvio_hero_banner_1.png',
            'primary_cta_text' => 'Enrol Now',
            'primary_cta_url' => '/admissions/enrol-now',
            'secondary_cta_text' => 'Explore Curriculum',
            'secondary_cta_url' => '/curriculum',
            'sort_order' => 1,
            'is_active' => 1
        ],
        2 => [
            'title' => 'Personalised Learning Pathways',
            'subtitle' => 'ADAPTIVE ONLINE CLASSROOMS',
            'description' => 'Small-group live classrooms adapting to every child’s unique potential.',
            'image' => '/assets/images/zuvio_hero_banner_2.png',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/curriculum',
            'secondary_cta_text' => 'Request Callback',
            'secondary_cta_url' => 'javascript:openCallbackModal()',
            'sort_order' => 2,
            'is_active' => 1
        ],
        3 => [
            'title' => 'Interactive STEM & Digital Labs',
            'subtitle' => 'FUTURE-READY PEDAGOGY',
            'description' => 'Interactive simulations, coding, AI awareness, and hands-on projects.',
            'image' => '/assets/images/zuvio_hero_banner_3.png',
            'primary_cta_text' => 'Explore Academics',
            'primary_cta_url' => '/academics',
            'secondary_cta_text' => 'Enrol Now',
            'secondary_cta_url' => '/admissions/enrol-now',
            'sort_order' => 3,
            'is_active' => 1
        ]
    ];
    
    if ($db) {
        try {
            foreach ($standards as $s_id => $s_data) {
                $check = $db->prepare("SELECT id FROM `hero_slides` WHERE `id` = ? LIMIT 1");
                $check->execute([$s_id]);
                if ($check->fetch()) {
                    // Update in-place without creating new row
                    $up = $db->prepare("
                        UPDATE `hero_slides` 
                        SET `title` = ?, `subtitle` = ?, `description` = ?, `image` = ?, `primary_cta_text` = ?, `primary_cta_url` = ?, `secondary_cta_text` = ?, `secondary_cta_url` = ?, `sort_order` = ?, `is_active` = ?
                        WHERE `id` = ?
                    ");
                    $up->execute([
                        $s_data['title'], $s_data['subtitle'], $s_data['description'], $s_data['image'],
                        $s_data['primary_cta_text'], $s_data['primary_cta_url'], $s_data['secondary_cta_text'], $s_data['secondary_cta_url'],
                        $s_data['sort_order'], $s_data['is_active'], $s_id
                    ]);
                } else {
                    // Insert with exact ID
                    $ins = $db->prepare("
                        INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `description`, `image`, `primary_cta_text`, `primary_cta_url`, `secondary_cta_text`, `secondary_cta_url`, `sort_order`, `is_active`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $ins->execute([
                        $s_id, $s_data['title'], $s_data['subtitle'], $s_data['description'], $s_data['image'],
                        $s_data['primary_cta_text'], $s_data['primary_cta_url'], $s_data['secondary_cta_text'], $s_data['secondary_cta_url'],
                        $s_data['sort_order'], $s_data['is_active']
                    ]);
                }
            }
            header("Location: /admin/hero.php?msg=synced");
            exit;
        } catch (Exception $e) {
            $error = 'Sync Error: ' . $e->getMessage();
        }
    } else {
        $_SESSION['mock_hero_slides'] = array_values($standards);
        header("Location: /admin/hero.php?msg=synced");
        exit;
    }
}

// -----------------------------------------------------------------------------
// ACTION HANDLER: Deduplicate Banners (Cleans up past repeated inserts)
// -----------------------------------------------------------------------------
if ($action === 'deduplicate') {
    if ($db) {
        try {
            // Remove duplicates with same title keeping lowest ID
            $db->exec("
                DELETE t1 FROM `hero_slides` t1
                INNER JOIN `hero_slides` t2 
                WHERE t1.id > t2.id AND t1.title = t2.title
            ");
            header("Location: /admin/hero.php?msg=deduplicated");
            exit;
        } catch (Exception $e) {
            $error = 'Deduplicate Error: ' . $e->getMessage();
        }
    } else {
        header("Location: /admin/hero.php?msg=deduplicated");
        exit;
    }
}

// -----------------------------------------------------------------------------
// Fetch all existing slides from DB
// -----------------------------------------------------------------------------
$slides = [];
if ($db) {
    try {
        $slides = $db->query("SELECT * FROM `hero_slides` ORDER BY `sort_order` ASC, `id` ASC")->fetchAll();
    } catch (Exception $e) {
        $error = "Failed to load hero slides: " . $e->getMessage();
    }
}

if (empty($slides)) {
    if (!isset($_SESSION['mock_hero_slides']) || empty($_SESSION['mock_hero_slides'])) {
        $_SESSION['mock_hero_slides'] = [
            [
                'id' => 1,
                'title' => 'Global Standard Learning',
                'subtitle' => 'ZUVIO GLOBAL SCHOOL',
                'description' => 'Academic excellence meets personalised online learning for Grades K to 8.',
                'image' => '/assets/images/zuvio_hero_banner_1.png',
                'primary_cta_text' => 'Enrol Now',
                'primary_cta_url' => '/admissions/enrol-now',
                'secondary_cta_text' => 'Explore Curriculum',
                'secondary_cta_url' => '/curriculum',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'id' => 2,
                'title' => 'Personalised Learning Pathways',
                'subtitle' => 'ADAPTIVE ONLINE CLASSROOMS',
                'description' => 'Small-group live classrooms adapting to every child’s unique potential.',
                'image' => '/assets/images/zuvio_hero_banner_2.png',
                'primary_cta_text' => 'Our Curriculum',
                'primary_cta_url' => '/curriculum',
                'secondary_cta_text' => 'Request Callback',
                'secondary_cta_url' => 'javascript:openCallbackModal()',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'id' => 3,
                'title' => 'Interactive STEM & Digital Labs',
                'subtitle' => 'FUTURE-READY PEDAGOGY',
                'description' => 'Interactive simulations, coding, AI awareness, and hands-on projects.',
                'image' => '/assets/images/zuvio_hero_banner_3.png',
                'primary_cta_text' => 'Explore Academics',
                'primary_cta_url' => '/academics',
                'secondary_cta_text' => 'Enrol Now',
                'secondary_cta_url' => '/admissions/enrol-now',
                'sort_order' => 3,
                'is_active' => 1
            ]
        ];
    }
    $slides = $_SESSION['mock_hero_slides'];
}

$page_slug = 'admin-hero';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="max-width: 1100px; margin: 0 auto;">

  <!-- Header & Toolbar -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
        Hero Banner Management
      </h1>
      <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0;">
        Control homepage carousel slides. Each banner has its own dedicated Save button. Changes do not reflect on the live website until explicitly saved.
      </p>
    </div>
    
    <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
      <a href="/admin/hero.php?action=sync_defaults" onclick="return confirm('Sync the standard 3 banners safely? This operation is idempotent and will NOT create duplicates.');" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 0.9rem; border-color: var(--color-gold); color: var(--color-navy);">
        ⚡ Safe Idempotent Sync
      </a>
      <a href="/admin/hero.php?action=deduplicate" onclick="return confirm('Clean up duplicate banner records?');" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 0.9rem; border-color: #94A3B8; color: #475569;">
        🧹 Clean Duplicates
      </a>
      <a href="#new-banner-section" class="btn btn-primary" style="font-size: 0.82rem; padding: 0.5rem 1rem;">
        + Add New Banner
      </a>
      <a href="/" target="_blank" class="btn btn-outline" style="font-size: 0.82rem; padding: 0.5rem 0.9rem;">
        View Live Website ↗
      </a>
    </div>
  </div>

  <!-- Status Notification Alerts -->
  <?php if ($msg === 'saved'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
      <span><strong>Saved!</strong> Banner #<?php echo (int)($_GET['id'] ?? 0); ?> has been successfully updated and published to the live website.</span>
      <span style="font-size: 0.75rem; color: #047857;"><?php echo date('h:i:s A'); ?></span>
    </div>
  <?php elseif ($msg === 'deleted'): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Deleted:</strong> Banner #<?php echo (int)($_GET['id'] ?? 0); ?> was permanently deleted and removed from the website carousel.
    </div>
  <?php elseif ($msg === 'added'): ?>
    <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #065F46; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Success:</strong> New hero banner was created and published.
    </div>
  <?php elseif ($msg === 'synced'): ?>
    <div style="background-color: #EFF6FF; border-left: 4px solid #3B82F6; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #1E40AF; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Idempotent Sync Complete:</strong> Standard banners verified and synced. Zero duplicates were created.
    </div>
  <?php elseif ($msg === 'deduplicated'): ?>
    <div style="background-color: #EFF6FF; border-left: 4px solid #3B82F6; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #1E40AF; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Cleaned:</strong> Duplicate banners with identical titles have been removed.
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: #991B1B; font-size: 0.88rem; margin-bottom: 1.5rem;">
      <strong>Error:</strong> <?php echo h($error); ?>
    </div>
  <?php endif; ?>

  <!-- Summary Stats Bar -->
  <div style="display: flex; gap: 1.5rem; background: #FFFFFF; border: 1px solid var(--color-border); padding: 1rem 1.5rem; border-radius: var(--radius-sm); margin-bottom: 2rem; font-size: 0.88rem; align-items: center;">
    <div><strong>Total Banners:</strong> <span style="color: var(--color-navy);"><?php echo count($slides); ?></span></div>
    <div>&bull;</div>
    <div><strong>Live Published:</strong> <span style="color: #059669; font-weight: 700;"><?php echo count(array_filter($slides, function($s){ return !empty($s['is_active']); })); ?></span></div>
    <div>&bull;</div>
    <div><strong>Draft / Inactive:</strong> <span style="color: #64748B;"><?php echo count(array_filter($slides, function($s){ return empty($s['is_active']); })); ?></span></div>
  </div>

  <!-- =========================================================================
       EXISTING BANNERS: EACH WITH ITS OWN DEDICATED FORM & SAVE BUTTON
       ========================================================================= -->
  <div style="display: flex; flex-direction: column; gap: 2rem; margin-bottom: 3.5rem;">
    <?php foreach ($slides as $idx => $slide): 
      $sid = (int)$slide['id'];
      $is_live = !empty($slide['is_active']);
    ?>
      <div class="card banner-card" id="banner-card-<?php echo $sid; ?>" style="background: #FFFFFF; border: 1.5px solid rgba(6, 43, 99, 0.14); border-radius: var(--radius-md); padding: 2rem; position: relative; transition: all 0.25s ease;">
        
        <!-- Pending Deletion Visual Warning (hidden by default) -->
        <div id="pending-del-alert-<?php echo $sid; ?>" style="display: none; background: #FEE2E2; border: 1.5px solid #EF4444; border-radius: var(--radius-sm); padding: 1rem 1.25rem; color: #991B1B; font-size: 0.85rem; margin-bottom: 1.5rem;">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
            <span>
              <strong>⚠️ PENDING DELETION:</strong> This banner is marked for deletion. It is <strong>NOT yet removed</strong> from the database or website.
              Click <strong>"Confirm Deletion & Save"</strong> below to permanently delete it, or click <strong>"Cancel Deletion"</strong> to keep it active.
            </span>
            <button type="button" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.3rem 0.75rem; background: #FFFFFF; color: #991B1B; border-color: #EF4444;" onclick="cancelBannerDeletion(<?php echo $sid; ?>)">
              Cancel Deletion
            </button>
          </div>
        </div>

        <!-- Banner Header Ribbon -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;">
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="background: var(--color-navy); color: #FFFFFF; font-weight: 700; font-size: 0.82rem; padding: 0.25rem 0.65rem; border-radius: 4px;">
              Banner #<?php echo $sid; ?>
            </span>
            <h3 style="font-size: 1.15rem; color: var(--color-navy); margin: 0; font-family: var(--font-secondary);">
              <?php echo h($slide['title']); ?>
            </h3>
          </div>

          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span id="status-badge-<?php echo $sid; ?>" data-original-status="<?php echo $is_live ? 'Live Published' : 'Draft / Hidden'; ?>" data-original-bg="<?php echo $is_live ? '#DEF7EC' : '#F1F5F9'; ?>" data-original-color="<?php echo $is_live ? '#03543F' : '#475569'; ?>" style="font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 12px; background: <?php echo $is_live ? '#DEF7EC' : '#F1F5F9'; ?>; color: <?php echo $is_live ? '#03543F' : '#475569'; ?>;">
              <?php echo $is_live ? '● Live Published' : '○ Draft / Hidden'; ?>
            </span>
            <span style="font-size: 0.75rem; color: var(--color-muted); background: #F8FAFC; border: 1px solid #E2E8F0; padding: 0.25rem 0.5rem; border-radius: 4px;">
              Order: <strong><?php echo (int)($slide['sort_order'] ?? 1); ?></strong>
            </span>
          </div>
        </div>

        <!-- INDIVIDUAL BANNER FORM -->
        <form method="POST" action="/admin/hero.php" enctype="multipart/form-data" id="form-banner-<?php echo $sid; ?>">
          <input type="hidden" name="action" value="save_individual_banner">
          <input type="hidden" name="banner_id" value="<?php echo $sid; ?>">
          <input type="hidden" name="pending_action" id="pending-action-<?php echo $sid; ?>" value="save">
          <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

          <!-- Fields Grid -->
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.75rem; margin-bottom: 1.5rem;">
            
            <!-- Left Column: Copy & CTAs -->
            <div>
              <div class="admin-form-group">
                <label class="admin-label">Slide Headline / Title *</label>
                <input type="text" name="title" value="<?php echo h($slide['title']); ?>" required class="admin-input" placeholder="e.g. A Future-Ready Online School">
              </div>

              <div class="admin-form-group">
                <label class="admin-label">Eyebrow / Subtitle Badge</label>
                <input type="text" name="subtitle" value="<?php echo h($slide['subtitle'] ?? ''); ?>" class="admin-input" placeholder="e.g. ZUVIO GLOBAL SCHOOL">
              </div>

              <div class="admin-form-group">
                <label class="admin-label">Description Body</label>
                <textarea name="description" rows="3" class="admin-input" style="line-height: 1.5;" placeholder="Key highlights displayed over the hero banner..."><?php echo h($slide['description'] ?? ''); ?></textarea>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="admin-form-group">
                  <label class="admin-label">Primary Button Label</label>
                  <input type="text" name="primary_cta_text" value="<?php echo h($slide['primary_cta_text'] ?? ''); ?>" class="admin-input" placeholder="e.g. Enrol Now">
                </div>
                <div class="admin-form-group">
                  <label class="admin-label">Primary Button URL</label>
                  <input type="text" name="primary_cta_url" value="<?php echo h($slide['primary_cta_url'] ?? ''); ?>" class="admin-input" placeholder="e.g. /admissions#enrol">
                </div>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="admin-form-group">
                  <label class="admin-label">Secondary Button Label</label>
                  <input type="text" name="secondary_cta_text" value="<?php echo h($slide['secondary_cta_text'] ?? ''); ?>" class="admin-input" placeholder="e.g. Our Curriculum">
                </div>
                <div class="admin-form-group">
                  <label class="admin-label">Secondary Button URL</label>
                  <input type="text" name="secondary_cta_url" value="<?php echo h($slide['secondary_cta_url'] ?? ''); ?>" class="admin-input" placeholder="e.g. /curriculum">
                </div>
              </div>
            </div>

            <!-- Right Column: Media Preview & Display Settings -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.25rem; border-radius: var(--radius-sm);">
              <label class="admin-label">Banner Image Preview</label>
              
              <div style="height: 140px; background-color: #0F172A; background-image: url('<?php echo h($slide['image']); ?>'); background-size: cover; background-position: center; border-radius: 6px; margin-bottom: 0.75rem; border: 1px solid rgba(0,0,0,0.1);"></div>
              
              <div class="admin-form-group">
                <label class="admin-label" style="font-size: 0.75rem;">Image URL / Path</label>
                <input type="text" name="image" value="<?php echo h($slide['image']); ?>" class="admin-input" style="font-size: 0.78rem;" placeholder="/assets/images/...">
              </div>

              <div class="admin-form-group">
                <label class="admin-label" style="font-size: 0.75rem;">Or Replace File (JPG/PNG/WEBP)</label>
                <input type="file" name="slide_image_file_<?php echo $sid; ?>" accept="image/*" class="admin-input" style="font-size: 0.75rem; padding: 0.35rem;">
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #E2E8F0;">
                <div>
                  <label class="admin-label" style="font-size: 0.75rem;">Sort Order</label>
                  <input type="number" name="sort_order" value="<?php echo (int)($slide['sort_order'] ?? 1); ?>" min="1" max="99" class="admin-input" style="font-size: 0.8rem; padding: 0.4rem;">
                </div>
                <div>
                  <label class="admin-label" style="font-size: 0.75rem;">Status</label>
                  <label style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; cursor: pointer; margin-top: 0.4rem;">
                    <input type="checkbox" name="is_active" value="1" <?php echo $is_live ? 'checked' : ''; ?>>
                    <span>Published</span>
                  </label>
                </div>
              </div>
            </div>

          </div>

          <!-- INDIVIDUAL ACTION CONTROLS & SAVE BUTTON -->
          <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.25rem; border-top: 1px solid var(--color-border); flex-wrap: wrap; gap: 1rem;">
            <!-- Left Side: Mark for Deletion / Undo -->
            <div style="display: flex; gap: 0.5rem; align-items: center;">
              <button type="button" id="mark-del-btn-<?php echo $sid; ?>" class="btn btn-outline" style="color: #DC2626; border-color: #F87171; font-size: 0.82rem; padding: 0.5rem 1rem;" onclick="markBannerForDeletion(<?php echo $sid; ?>)">
                🗑 Delete Banner #<?php echo $sid; ?>
              </button>
              <button type="button" id="cancel-del-btn-<?php echo $sid; ?>" class="btn btn-outline" style="display: none; color: #475569; border-color: #94A3B8; font-size: 0.82rem; padding: 0.5rem 1rem;" onclick="cancelBannerDeletion(<?php echo $sid; ?>)">
                ↩ Cancel Deletion
              </button>
            </div>

            <!-- Right Side: Dedicated Individual Save Button -->
            <div>
              <button type="submit" id="save-btn-<?php echo $sid; ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.65rem 1.5rem; font-size: 0.88rem; background: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 600;">
                💾 Save Banner #<?php echo $sid; ?> Changes
              </button>
            </div>
          </div>

        </form>

      </div>
    <?php endforeach; ?>
  </div>

  <!-- =========================================================================
       ADD NEW BANNER SECTION: DEDICATED FORM WITH ITS OWN SAVE BUTTON
       ========================================================================= -->
  <div class="card" id="new-banner-section" style="background: #FFFFFF; border-left: none; border-top: 4px solid var(--color-gold); border-radius: var(--radius-md); padding: 2.25rem; margin-bottom: 3rem;">
    <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
      <h2 style="font-size: 1.35rem; color: var(--color-navy); margin: 0 0 0.25rem 0; font-family: var(--font-secondary);">
        + Add New Hero Banner
      </h2>
      <p style="color: var(--color-muted); font-size: 0.82rem; margin: 0;">
        Create a new banner slide for the homepage carousel. It will be assigned a permanent unique database ID.
      </p>
    </div>

    <form method="POST" action="/admin/hero.php" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add_new_banner">
      <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.75rem; margin-bottom: 1.5rem;">
        
        <div>
          <div class="admin-form-group">
            <label class="admin-label">Slide Headline / Title *</label>
            <input type="text" name="title" required class="admin-input" placeholder="e.g. Inspiring Future Leaders">
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Eyebrow / Subtitle Badge</label>
            <input type="text" name="subtitle" class="admin-input" placeholder="e.g. ZUVIO GLOBAL SCHOOL" value="ZUVIO GLOBAL SCHOOL">
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Description Body</label>
            <textarea name="description" rows="3" class="admin-input" placeholder="Clear summary of academic programs..."></textarea>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Primary CTA Label</label>
              <input type="text" name="primary_cta_text" value="Enrol Now" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Primary CTA URL</label>
              <input type="text" name="primary_cta_url" value="/admissions#enrol" class="admin-input">
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="admin-form-group">
              <label class="admin-label">Secondary CTA Label</label>
              <input type="text" name="secondary_cta_text" value="Our Curriculum" class="admin-input">
            </div>
            <div class="admin-form-group">
              <label class="admin-label">Secondary CTA URL</label>
              <input type="text" name="secondary_cta_url" value="/curriculum" class="admin-input">
            </div>
          </div>
        </div>

        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1.25rem; border-radius: var(--radius-sm);">
          <div class="admin-form-group">
            <label class="admin-label">Image URL / Path</label>
            <input type="text" name="image" value="/assets/images/zuvio_hero_banner_1.png" class="admin-input" style="font-size: 0.8rem;">
          </div>

          <div class="admin-form-group">
            <label class="admin-label">Upload Image File (Optional)</label>
            <input type="file" name="new_slide_image_file" accept="image/*" class="admin-input" style="font-size: 0.75rem; padding: 0.35rem;">
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #E2E8F0;">
            <div>
              <label class="admin-label" style="font-size: 0.75rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo count($slides) + 1; ?>" min="1" max="99" class="admin-input" style="font-size: 0.8rem; padding: 0.4rem;">
            </div>
            <div>
              <label class="admin-label" style="font-size: 0.75rem;">Status</label>
              <label style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; cursor: pointer; margin-top: 0.4rem;">
                <input type="checkbox" name="is_active" value="1" checked>
                <span>Published</span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <div style="text-align: right; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 0.9rem; font-weight: 600;">
          + Save &amp; Publish New Banner
        </button>
      </div>

    </form>
  </div>

</div>

<!-- Client-side Interactive Script for Pending Deletion & Save -->
<script>
function markBannerForDeletion(id) {
  const card = document.getElementById('banner-card-' + id);
  const alertBox = document.getElementById('pending-del-alert-' + id);
  const actionInput = document.getElementById('pending-action-' + id);
  const saveBtn = document.getElementById('save-btn-' + id);
  const markBtn = document.getElementById('mark-del-btn-' + id);
  const cancelBtn = document.getElementById('cancel-del-btn-' + id);
  const badge = document.getElementById('status-badge-' + id);

  if (card && alertBox && actionInput && saveBtn && markBtn && cancelBtn) {
    actionInput.value = 'delete';
    alertBox.style.display = 'block';
    card.style.borderColor = '#EF4444';
    card.style.background = '#FEF2F2';
    saveBtn.innerText = '⚠️ Confirm Deletion & Save Banner #' + id;
    saveBtn.style.background = '#DC2626';
    saveBtn.style.borderColor = '#DC2626';
    markBtn.style.display = 'none';
    cancelBtn.style.display = 'inline-flex';
    if (badge) {
      badge.innerText = 'Pending Deletion';
      badge.style.background = '#FEE2E2';
      badge.style.color = '#B91C1C';
    }
    // Scroll card into view gently
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

function cancelBannerDeletion(id) {
  const card = document.getElementById('banner-card-' + id);
  const alertBox = document.getElementById('pending-del-alert-' + id);
  const actionInput = document.getElementById('pending-action-' + id);
  const saveBtn = document.getElementById('save-btn-' + id);
  const markBtn = document.getElementById('mark-del-btn-' + id);
  const cancelBtn = document.getElementById('cancel-del-btn-' + id);
  const badge = document.getElementById('status-badge-' + id);

  if (card && alertBox && actionInput && saveBtn && markBtn && cancelBtn) {
    actionInput.value = 'save';
    alertBox.style.display = 'none';
    card.style.borderColor = 'rgba(6, 43, 99, 0.14)';
    card.style.background = '#FFFFFF';
    saveBtn.innerText = '💾 Save Banner #' + id + ' Changes';
    saveBtn.style.background = 'var(--color-navy)';
    saveBtn.style.borderColor = 'var(--color-navy)';
    markBtn.style.display = 'inline-flex';
    cancelBtn.style.display = 'none';
    if (badge) {
      badge.innerText = badge.dataset.originalStatus || 'Live Published';
      badge.style.background = badge.dataset.originalBg || '#DEF7EC';
      badge.style.color = badge.dataset.originalColor || '#03543F';
    }
  }
}
</script>

<?php
include_once dirname(__FILE__) . '/footer.php';
?>
