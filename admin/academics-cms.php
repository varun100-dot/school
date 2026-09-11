<?php
// Zuvio Global School - Admin Academics CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'technology';

// Ensure $_SESSION['mock_academics_cms'] is populated with structure
if (!isset($_SESSION['mock_academics_cms'])) {
    $_SESSION['mock_academics_cms'] = [];
}
$ac_cms = &$_SESSION['mock_academics_cms'];

// 1. Defaults for Technology
if (!isset($ac_cms['technology'])) {
    $ac_cms['technology'] = [
        'hero_title' => 'Technology Built for Real Learning',
        'hero_subtitle' => 'Our Digital Learning Ecosystem',
        'hero_desc' => 'At Zuvio Global School, technology is never a passive screen. It is an active workspace for curiosity, collaboration, and creative mastery powered by an enterprise-grade online learning environment.',
        'lms_video' => '/assets/images/03_Science_Experiment_Learning.mp4',
        'lms_features' => [
            ['id' => 1, 'title' => 'Child-Friendly and User-Focused', 'desc' => 'Intentionally designed for young learners with intuitive, accessible navigation.', 'icon' => '🧒', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Live, Interactive Classes', 'desc' => 'Real-time daily connections with teachers and peers using live video and whiteboards.', 'icon' => '💻', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Safety and Security First', 'desc' => 'End-to-end encrypted and moderated virtual classrooms ensuring a safe environment.', 'icon' => '🛡️', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Digital Library at Fingertips', 'desc' => 'Instant access to curriculum textbooks in PDF, Oxford graded readers, and guides.', 'icon' => '📚', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'All-in-One Convenience', 'desc' => 'From live schedules to gradebooks and progress reports — everything is one click away.', 'icon' => '⚡', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Diverse Learning Resources', 'desc' => 'Catering to visual, auditory, and kinesthetic learners with multimedia content.', 'icon' => '🎨', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

// 2. Defaults for Special Education
if (!isset($ac_cms['special_ed'])) {
    $ac_cms['special_ed'] = [
        'title' => 'Inclusive Learning & Special Education',
        'kicker' => 'Every Child Learns. Every Child Belongs.',
        'intro' => 'At Zuvio Global School, we believe education must adapt to the learner — never the child to the system. Our inclusive learning programme creates a supportive, flexible, and learner-centred environment where children with diverse needs can take part meaningfully, grow in confidence, and discover their unique strengths.',
        'pillars' => [
            ['id' => 1, 'title' => 'Personalised Learning', 'desc' => 'Flexible learning pace, individualised goals, and customized worksheets.', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'title' => 'Individual Attention', 'desc' => 'Small cohorts (1:15–1:20) and dedicated one-on-one check-ins.', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'title' => 'Flexible Learning Rhythm', 'desc' => 'Learn from the comfort of home, free from sensory overload or peer anxiety.', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'title' => 'Strength-Based Pedagogy', 'desc' => 'Focusing on what children love and do best, cultivating genuine self-esteem.', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'title' => 'Social & Emotional Growth', 'desc' => 'Empathy-driven teacher relationships in an inclusive peer setting.', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'title' => 'Close Family Partnership', 'desc' => 'Regular collaborative reviews with parents to calibrate IEP milestones.', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

// 3. Defaults for Electives
if (!isset($ac_cms['electives'])) {
    $ac_cms['electives'] = [
        'regional_languages' => ['Hindi', 'Sanskrit', 'Urdu', 'Tamil', 'Telugu', 'Kannada', 'Marathi', 'Bengali'],
        'foreign_languages' => ['French', 'Spanish', 'German', 'Arabic', 'Mandarin'],
        'future_skills' => [
            ['id' => 1, 'name' => 'Coding & Robotics', 'desc' => 'Block programming, Python fundamentals, and logic by Discovery Education.', 'sort_order' => 1, 'is_published' => 1],
            ['id' => 2, 'name' => 'Abacus & Rubik\'s Cube', 'desc' => 'Mental arithmetic speed, spatial memory, and focus concentration.', 'sort_order' => 2, 'is_published' => 1],
            ['id' => 3, 'name' => 'Public Speaking & Debate', 'desc' => 'Articulating ideas with poise, persuasive rhetoric, and voice modulation.', 'sort_order' => 3, 'is_published' => 1],
            ['id' => 4, 'name' => 'Creative Writing & Media', 'desc' => 'Authoring short stories, journalistic reporting, and digital publishing.', 'sort_order' => 4, 'is_published' => 1],
            ['id' => 5, 'name' => 'Financial Literacy', 'desc' => 'Foundational concepts of money, saving, budgeting, and ethical commerce.', 'sort_order' => 5, 'is_published' => 1],
            ['id' => 6, 'name' => 'Yoga & Mindfulness', 'desc' => 'Breathing exercises, physical postures, and emotional regulation techniques.', 'sort_order' => 6, 'is_published' => 1]
        ]
    ];
}

// 4. Defaults for NEP 2020 & Resources
if (!isset($ac_cms['nep_2020'])) {
    $ac_cms['nep_2020'] = [
        'title' => 'NEP 2020 & NCF Compliance',
        'subtitle' => 'National Education Policy 2020 Alignment',
        'desc' => 'In full alignment with the National Education Policy (NEP 2020) and National Curriculum Framework (NCF), Zuvio replaces rote memorization with experiential, discovery-based, and interdisciplinary learning.',
        'pdf_title' => 'National Education Policy 2020 — Ministry of Education, Govt. of India',
        'pdf_url' => '/assets/docs/NEP_2020_Policy_Document.pdf'
    ];
}

if (!isset($ac_cms['resources'])) {
    $ac_cms['resources'] = [
        'calendar_title' => 'Academic Calendar 2026–27',
        'calendar_desc' => 'Comprehensive term dates, assessment schedules, project submission deadlines, and school holidays.',
        'calendar_pdf' => '/assets/docs/Zuvio_Academic_Calendar_2026_27.pdf'
    ];
}

// Helper: Handle file uploads for PDFs, Images, and Videos
function handle_cms_upload($file_key, $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'mp4']) {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$file_key];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_extensions)) {
        throw new Exception("Invalid file extension: $ext. Allowed: " . implode(', ', $allowed_extensions));
    }
    $upload_dir = dirname(__FILE__) . '/../uploads/academics/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $filename = bin2hex(random_bytes(6)) . '_' . time() . '.' . $ext;
    $target = $upload_dir . $filename;
    if (move_uploaded_file($file['tmp_name'], $target)) {
        return '/uploads/academics/' . $filename;
    }
    return null;
}

// POST Action Handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. Save Technology Overview
        if ($action === 'save_tech_overview') {
            $ac_cms['technology']['hero_title'] = trim($_POST['hero_title'] ?? '');
            $ac_cms['technology']['hero_subtitle'] = trim($_POST['hero_subtitle'] ?? '');
            $ac_cms['technology']['hero_desc'] = trim($_POST['hero_desc'] ?? '');
            
            try {
                $uploaded_video = handle_cms_upload('lms_video_file', ['mp4', 'webm', 'mov']);
                if ($uploaded_video) {
                    $ac_cms['technology']['lms_video'] = $uploaded_video;
                } elseif (!empty($_POST['lms_video_url'])) {
                    $ac_cms['technology']['lms_video'] = trim($_POST['lms_video_url']);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                header('Location: /admin/academics-cms.php?tab=technology&msg=saved');
                exit;
            }
        }

        // 2. LMS Features CRUD
        if ($action === 'add_lms_feature') {
            $new_id = time();
            $ac_cms['technology']['lms_features'][] = [
                'id' => $new_id,
                'title' => trim($_POST['title'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'icon' => trim($_POST['icon'] ?? '✨'),
                'sort_order' => count($ac_cms['technology']['lms_features']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/academics-cms.php?tab=technology&msg=added');
            exit;
        }

        if ($action === 'edit_lms_feature') {
            $id = (int)$_POST['id'];
            foreach ($ac_cms['technology']['lms_features'] as &$feat) {
                if ($feat['id'] === $id) {
                    $feat['title'] = trim($_POST['title'] ?? '');
                    $feat['desc'] = trim($_POST['desc'] ?? '');
                    $feat['icon'] = trim($_POST['icon'] ?? '✨');
                    $feat['sort_order'] = (int)($_POST['sort_order'] ?? $feat['sort_order']);
                    break;
                }
            }
            usort($ac_cms['technology']['lms_features'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/academics-cms.php?tab=technology&msg=updated');
            exit;
        }

        if ($action === 'delete_lms_feature') {
            $id = (int)$_POST['id'];
            $ac_cms['technology']['lms_features'] = array_values(array_filter($ac_cms['technology']['lms_features'], fn($f) => $f['id'] !== $id));
            header('Location: /admin/academics-cms.php?tab=technology&msg=deleted');
            exit;
        }

        if ($action === 'toggle_publish_lms_feature') {
            $id = (int)$_POST['id'];
            foreach ($ac_cms['technology']['lms_features'] as &$feat) {
                if ($feat['id'] === $id) {
                    $feat['is_published'] = empty($feat['is_published']) ? 1 : 0;
                    break;
                }
            }
            header('Location: /admin/academics-cms.php?tab=technology&msg=status_updated');
            exit;
        }

        // 3. Special Education
        if ($action === 'save_special_ed_overview') {
            $ac_cms['special_ed']['title'] = trim($_POST['title'] ?? '');
            $ac_cms['special_ed']['kicker'] = trim($_POST['kicker'] ?? '');
            $ac_cms['special_ed']['intro'] = trim($_POST['intro'] ?? '');
            header('Location: /admin/academics-cms.php?tab=special_ed&msg=saved');
            exit;
        }

        if ($action === 'add_special_ed_pillar') {
            $new_id = time();
            $ac_cms['special_ed']['pillars'][] = [
                'id' => $new_id,
                'title' => trim($_POST['title'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'sort_order' => count($ac_cms['special_ed']['pillars']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/academics-cms.php?tab=special_ed&msg=added');
            exit;
        }

        if ($action === 'edit_special_ed_pillar') {
            $id = (int)$_POST['id'];
            foreach ($ac_cms['special_ed']['pillars'] as &$p) {
                if ($p['id'] === $id) {
                    $p['title'] = trim($_POST['title'] ?? '');
                    $p['desc'] = trim($_POST['desc'] ?? '');
                    $p['sort_order'] = (int)($_POST['sort_order'] ?? $p['sort_order']);
                    break;
                }
            }
            usort($ac_cms['special_ed']['pillars'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/academics-cms.php?tab=special_ed&msg=updated');
            exit;
        }

        if ($action === 'delete_special_ed_pillar') {
            $id = (int)$_POST['id'];
            $ac_cms['special_ed']['pillars'] = array_values(array_filter($ac_cms['special_ed']['pillars'], fn($p) => $p['id'] !== $id));
            header('Location: /admin/academics-cms.php?tab=special_ed&msg=deleted');
            exit;
        }

        // 4. Electives
        if ($action === 'save_electives_languages') {
            $raw_reg = trim($_POST['regional_languages'] ?? '');
            $ac_cms['electives']['regional_languages'] = array_filter(array_map('trim', explode(',', $raw_reg)));
            
            $raw_for = trim($_POST['foreign_languages'] ?? '');
            $ac_cms['electives']['foreign_languages'] = array_filter(array_map('trim', explode(',', $raw_for)));
            
            header('Location: /admin/academics-cms.php?tab=electives&msg=saved');
            exit;
        }

        if ($action === 'add_skill_elective') {
            $new_id = time();
            $ac_cms['electives']['future_skills'][] = [
                'id' => $new_id,
                'name' => trim($_POST['name'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'sort_order' => count($ac_cms['electives']['future_skills']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/academics-cms.php?tab=electives&msg=added');
            exit;
        }

        if ($action === 'edit_skill_elective') {
            $id = (int)$_POST['id'];
            foreach ($ac_cms['electives']['future_skills'] as &$fs) {
                if ($fs['id'] === $id) {
                    $fs['name'] = trim($_POST['name'] ?? '');
                    $fs['desc'] = trim($_POST['desc'] ?? '');
                    $fs['sort_order'] = (int)($_POST['sort_order'] ?? $fs['sort_order']);
                    break;
                }
            }
            usort($ac_cms['electives']['future_skills'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/academics-cms.php?tab=electives&msg=updated');
            exit;
        }

        if ($action === 'delete_skill_elective') {
            $id = (int)$_POST['id'];
            $ac_cms['electives']['future_skills'] = array_values(array_filter($ac_cms['electives']['future_skills'], fn($fs) => $fs['id'] !== $id));
            header('Location: /admin/academics-cms.php?tab=electives&msg=deleted');
            exit;
        }

        // 5. NEP 2020 & Documents (PDF Upload)
        if ($action === 'save_nep_2020') {
            $ac_cms['nep_2020']['title'] = trim($_POST['title'] ?? '');
            $ac_cms['nep_2020']['subtitle'] = trim($_POST['subtitle'] ?? '');
            $ac_cms['nep_2020']['desc'] = trim($_POST['desc'] ?? '');
            $ac_cms['nep_2020']['pdf_title'] = trim($_POST['pdf_title'] ?? '');

            try {
                $uploaded_pdf = handle_cms_upload('nep_pdf_file', ['pdf']);
                if ($uploaded_pdf) {
                    $ac_cms['nep_2020']['pdf_url'] = $uploaded_pdf;
                } elseif (!empty($_POST['nep_pdf_url'])) {
                    $ac_cms['nep_2020']['pdf_url'] = trim($_POST['nep_pdf_url']);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                header('Location: /admin/academics-cms.php?tab=nep_2020&msg=saved');
                exit;
            }
        }

        // 6. Resources & Calendar PDF
        if ($action === 'save_resources') {
            $ac_cms['resources']['calendar_title'] = trim($_POST['calendar_title'] ?? '');
            $ac_cms['resources']['calendar_desc'] = trim($_POST['calendar_desc'] ?? '');

            try {
                $uploaded_cal_pdf = handle_cms_upload('calendar_pdf_file', ['pdf']);
                if ($uploaded_cal_pdf) {
                    $ac_cms['resources']['calendar_pdf'] = $uploaded_cal_pdf;
                } elseif (!empty($_POST['calendar_pdf_url'])) {
                    $ac_cms['resources']['calendar_pdf'] = trim($_POST['calendar_pdf_url']);
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                header('Location: /admin/academics-cms.php?tab=resources&msg=saved');
                exit;
            }
        }
    }
}

$page_slug = 'admin-academics-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Academics & Technology CMS Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.88rem;">
      Manage Technology (LMS/CRM/ERP), Special Education, Electives, NEP 2020, and Resources with file uploads.
    </p>
  </div>
  <div style="display: flex; gap: 0.6rem;">
    <a href="/academics" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview /academics &nearr;
    </a>
    <a href="/curriculum" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview /curriculum &nearr;
    </a>
  </div>
</div>

<?php if ($msg): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.88rem; margin-bottom: 1.5rem;">
    <?php 
      if ($msg === 'saved') echo 'Content changes successfully saved and synchronized.';
      elseif ($msg === 'added') echo 'New record successfully added.';
      elseif ($msg === 'updated') echo 'Record successfully updated.';
      elseif ($msg === 'deleted') echo 'Record successfully deleted.';
      elseif ($msg === 'status_updated') echo 'Publish/Visibility status toggled.';
    ?>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c; font-size: 0.88rem;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- Navigation Tabs -->
<div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; overflow-x: auto; padding-bottom: 0.25rem;">
  <a href="?tab=technology" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'technology' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    1. Technology & LMS
  </a>
  <a href="?tab=special_ed" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'special_ed' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    2. Special Education
  </a>
  <a href="?tab=electives" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'electives' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    3. Electives & Languages
  </a>
  <a href="?tab=nep_2020" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'nep_2020' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    4. NEP 2020 & Policy PDF
  </a>
  <a href="?tab=resources" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'resources' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    5. Resources & Calendar
  </a>
</div>

<?php if ($tab === 'technology'): ?>
  <!-- TAB 1: Technology & LMS Features -->
  <form method="POST" action="/admin/academics-cms.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="action" value="save_tech_overview">

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Technology Overview & Video
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Hero Title</label>
        <input type="text" name="hero_title" value="<?php echo h($ac_cms['technology']['hero_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Subtitle</label>
        <input type="text" name="hero_subtitle" value="<?php echo h($ac_cms['technology']['hero_subtitle']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
        <textarea name="hero_desc" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit;"><?php echo h($ac_cms['technology']['hero_desc']); ?></textarea>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">LMS Video / Showcase URL</label>
          <input type="text" name="lms_video_url" value="<?php echo h($ac_cms['technology']['lms_video']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Upload New Video (.mp4)</label>
          <input type="file" name="lms_video_file" accept="video/mp4,video/webm" class="form-control" style="width: 100%; padding: 0.4rem;">
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; margin-bottom: 2.5rem;">Save Technology Overview</button>
  </form>

  <!-- LMS Features CRUD -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">LMS Core Features (6 Cards)</h3>
    <button onclick="document.getElementById('add-lms-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add LMS Feature
    </button>
  </div>

  <div id="add-lms-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add LMS Feature</h4>
    <form method="POST" action="/admin/academics-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_lms_feature">
      <div style="display: grid; grid-template-columns: 80px 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Icon</label>
          <input type="text" name="icon" value="💻" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
          <input type="text" name="title" required placeholder="e.g. Live Interactive Classes" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Feature</button>
        <button type="button" onclick="document.getElementById('add-lms-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($ac_cms['technology']['lms_features'] as $f): ?>
      <div class="card" style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 260px;">
          <div style="font-size: 1.5rem; width: 40px; height: 40px; border-radius: 8px; background: var(--pastel-blue); display: flex; align-items: center; justify-content: center;">
            <?php echo $f['icon'] ?? '💻'; ?>
          </div>
          <div>
            <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
              <?php echo h($f['title']); ?>
              <?php if (empty($f['is_published'])): ?>
                <span style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">UNPUBLISHED</span>
              <?php else: ?>
                <span style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">PUBLISHED</span>
              <?php endif; ?>
            </h4>
            <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
              <?php echo h($f['desc']); ?>
            </p>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <form method="POST" action="/admin/academics-cms.php" style="margin: 0;">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="toggle_publish_lms_feature">
            <input type="hidden" name="id" value="<?php echo (int)$f['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
              <?php echo empty($f['is_published']) ? 'Publish' : 'Unpublish'; ?>
            </button>
          </form>

          <button onclick="document.getElementById('edit-feat-<?php echo $f['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>

          <form method="POST" action="/admin/academics-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this LMS feature?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_lms_feature">
            <input type="hidden" name="id" value="<?php echo (int)$f['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal Form -->
      <div id="edit-feat-<?php echo $f['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit LMS Feature #<?php echo $f['id']; ?></h4>
        <form method="POST" action="/admin/academics-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_lms_feature">
          <input type="hidden" name="id" value="<?php echo (int)$f['id']; ?>">
          <div style="display: grid; grid-template-columns: 80px 1fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Icon</label>
              <input type="text" name="icon" value="<?php echo h($f['icon'] ?? '💻'); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
              <input type="text" name="title" value="<?php echo h($f['title']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($f['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($f['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Feature</button>
            <button type="button" onclick="document.getElementById('edit-feat-<?php echo $f['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($tab === 'special_ed'): ?>
  <!-- TAB 2: Special Education -->
  <form method="POST" action="/admin/academics-cms.php">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="action" value="save_special_ed_overview">

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Special Education Overview
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Section Title</label>
        <input type="text" name="title" value="<?php echo h($ac_cms['special_ed']['title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Guiding Motto / Kicker</label>
        <input type="text" name="kicker" value="<?php echo h($ac_cms['special_ed']['kicker']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Introductory Philosophy</label>
        <textarea name="intro" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit;"><?php echo h($ac_cms['special_ed']['intro']); ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top: 1rem; padding: 0.65rem 1.5rem;">Save Philosophy</button>
    </div>
  </form>

  <!-- Benefit Pillars List -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">Special Education Pillars (6 Benefits)</h3>
    <button onclick="document.getElementById('add-pillar-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add Support Pillar
    </button>
  </div>

  <div id="add-pillar-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add Support Pillar</h4>
    <form method="POST" action="/admin/academics-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_special_ed_pillar">
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
        <input type="text" name="title" required placeholder="e.g. Strength-Based Pedagogy" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Pillar</button>
        <button type="button" onclick="document.getElementById('add-pillar-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($ac_cms['special_ed']['pillars'] as $p): ?>
      <div class="card" style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
            🌱 <?php echo h($p['title']); ?>
          </h4>
          <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
            <?php echo h($p['desc']); ?>
          </p>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <button onclick="document.getElementById('edit-pil-<?php echo $p['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>
          <form method="POST" action="/admin/academics-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this pillar?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_special_ed_pillar">
            <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal -->
      <div id="edit-pil-<?php echo $p['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit Pillar #<?php echo $p['id']; ?></h4>
        <form method="POST" action="/admin/academics-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_special_ed_pillar">
          <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
          <div style="display: grid; grid-template-columns: 1fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
              <input type="text" name="title" value="<?php echo h($p['title']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($p['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($p['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Pillar</button>
            <button type="button" onclick="document.getElementById('edit-pil-<?php echo $p['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($tab === 'electives'): ?>
  <!-- TAB 3: Electives & Languages -->
  <form method="POST" action="/admin/academics-cms.php">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="action" value="save_electives_languages">

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Language Electives
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Regional & Classical Languages (comma-separated)</label>
        <input type="text" name="regional_languages" value="<?php echo h(implode(', ', $ac_cms['electives']['regional_languages'])); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Modern Foreign Languages (comma-separated)</label>
        <input type="text" name="foreign_languages" value="<?php echo h(implode(', ', $ac_cms['electives']['foreign_languages'])); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.5rem;">Save Languages</button>
    </div>
  </form>

  <!-- Future Skills Electives CRUD -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">Co-Curricular & Future Skill Electives</h3>
    <button onclick="document.getElementById('add-skill-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add Skill Elective
    </button>
  </div>

  <div id="add-skill-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add Skill Elective</h4>
    <form method="POST" action="/admin/academics-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_skill_elective">
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Skill Name</label>
        <input type="text" name="name" required placeholder="e.g. Coding & Robotics" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Skill</button>
        <button type="button" onclick="document.getElementById('add-skill-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($ac_cms['electives']['future_skills'] as $fs): ?>
      <div class="card" style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
            ⭐ <?php echo h($fs['name']); ?>
          </h4>
          <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
            <?php echo h($fs['desc']); ?>
          </p>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <button onclick="document.getElementById('edit-sk-<?php echo $fs['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>
          <form method="POST" action="/admin/academics-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this skill elective?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_skill_elective">
            <input type="hidden" name="id" value="<?php echo (int)$fs['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal -->
      <div id="edit-sk-<?php echo $fs['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit Skill Elective #<?php echo $fs['id']; ?></h4>
        <form method="POST" action="/admin/academics-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_skill_elective">
          <input type="hidden" name="id" value="<?php echo (int)$fs['id']; ?>">
          <div style="display: grid; grid-template-columns: 1fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Skill Name</label>
              <input type="text" name="name" value="<?php echo h($fs['name']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($fs['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($fs['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Skill</button>
            <button type="button" onclick="document.getElementById('edit-sk-<?php echo $fs['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($tab === 'nep_2020'): ?>
  <!-- TAB 4: NEP 2020 & Policy PDF Management -->
  <form method="POST" action="/admin/academics-cms.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="action" value="save_nep_2020">

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        NEP 2020 Policy Framework & Document
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Section Title</label>
        <input type="text" name="title" value="<?php echo h($ac_cms['nep_2020']['title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Subtitle</label>
        <input type="text" name="subtitle" value="<?php echo h($ac_cms['nep_2020']['subtitle']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
        <textarea name="desc" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit;"><?php echo h($ac_cms['nep_2020']['desc']); ?></textarea>
      </div>

      <div style="border-top: 1px solid var(--color-border); padding-top: 1.25rem; margin-top: 1.5rem;">
        <h4 style="font-size: 1.1rem; color: var(--color-navy); margin-bottom: 1rem;">Policy PDF Resource</h4>
        <div class="form-group" style="margin-bottom: 1.25rem;">
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Document Display Title</label>
          <input type="text" name="pdf_title" value="<?php echo h($ac_cms['nep_2020']['pdf_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div>
            <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Current PDF URL</label>
            <input type="text" name="nep_pdf_url" value="<?php echo h($ac_cms['nep_2020']['pdf_url']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          </div>
          <div>
            <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Upload New Policy PDF</label>
            <input type="file" name="nep_pdf_file" accept="application/pdf" class="form-control" style="width: 100%; padding: 0.4rem;">
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem; padding: 0.75rem 2rem;">Save NEP 2020 Settings</button>
    </div>
  </form>

<?php elseif ($tab === 'resources'): ?>
  <!-- TAB 5: Resources & Academic Calendar -->
  <form method="POST" action="/admin/academics-cms.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="action" value="save_resources">

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Academic Calendar & Downloads
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Calendar Title</label>
        <input type="text" name="calendar_title" value="<?php echo h($ac_cms['resources']['calendar_title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Description</label>
        <textarea name="calendar_desc" rows="2" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit;"><?php echo h($ac_cms['resources']['calendar_desc']); ?></textarea>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Current Calendar PDF URL</label>
          <input type="text" name="calendar_pdf_url" value="<?php echo h($ac_cms['resources']['calendar_pdf']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Upload New Calendar PDF</label>
          <input type="file" name="calendar_pdf_file" accept="application/pdf" class="form-control" style="width: 100%; padding: 0.4rem;">
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem; padding: 0.75rem 2rem;">Save Calendar Settings</button>
    </div>
  </form>

<?php endif; ?>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
