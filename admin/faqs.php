<?php
// Zuvio Global School - Admin Parent FAQs Manager (18 Official FAQs)
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
        $db->query("SELECT 1 FROM `faqs` LIMIT 1");
    } catch (Exception $e) {
        $use_mock = true;
    }
}

// Prepopulate mock faqs if empty
if ($use_mock) {
    if (!isset($_SESSION['mock_faqs'])) {
        $_SESSION['mock_faqs'] = [
            1 => ['id' => 1, 'category' => 'Academics', 'question' => 'What are the timings for online classes?', 'answer' => "Live interactive classes are scheduled between 9:00 AM and 1:30 PM (IST), Monday to Friday.\n\nEach session lasts 40–45 minutes, with built-in screen breaks and offline activity time to prevent digital fatigue. Timetables are structured to balance core academics with co-curricular activities.", 'sort_order' => 1, 'is_active' => 1, 'show_on_home' => 1],
            2 => ['id' => 2, 'category' => 'Schooling & Board', 'question' => 'How does online schooling actually work at Zuvio?', 'answer' => "Online schooling at Zuvio is a complete, structured alternative to traditional brick-and-mortar schools.\n\nStudents attend live, teacher-led classes via our secure learning platform, collaborate with peers in small breakout groups, submit assignments digitally, and receive continuous feedback. It combines the rigor of a regular school with the flexibility and safety of home.", 'sort_order' => 2, 'is_active' => 1, 'show_on_home' => 1],
            3 => ['id' => 3, 'category' => 'Schooling & Board', 'question' => 'Is Zuvio’s curriculum mapped to CBSE?', 'answer' => "Yes. Zuvio's curriculum is comprehensively aligned with the Central Board of Secondary Education (CBSE) framework and the National Curriculum Framework (NCF) under NEP 2020.\n\nStudents cover all core competencies, learning outcomes, and subject milestones required by national standards.", 'sort_order' => 3, 'is_active' => 1, 'show_on_home' => 1],
            4 => ['id' => 4, 'category' => 'Academics', 'question' => 'What is the Oxford theme-based curriculum and how does it benefit my child?', 'answer' => "Our curriculum integrates Oxford University Press thematic learning resources, which connect different subjects under overarching, real-world themes (e.g., sustainability, exploration, community).\n\nThis interdisciplinary approach helps children see connections across subjects rather than learning in silos, fostering critical thinking, curiosity, and deeper conceptual understanding.", 'sort_order' => 4, 'is_active' => 1, 'show_on_home' => 1],
            5 => ['id' => 5, 'category' => 'Academics', 'question' => 'How is Project-Based Learning (PBL) incorporated?', 'answer' => "Every term includes hands-on, inquiry-driven projects where students investigate real-world problems and develop creative solutions.\n\nFor example, students might design a sustainable city model, create a podcast on historical events, or build an app prototype. PBL develops collaboration, research, problem-solving, and public speaking skills.", 'sort_order' => 5, 'is_active' => 1, 'show_on_home' => 1],
            6 => ['id' => 6, 'category' => 'Technology & AI', 'question' => 'How does Zuvio integrate Artificial Intelligence (AI) in education?', 'answer' => "At Zuvio, AI is used thoughtfully to enhance — never replace — the human teacher.\n\nWe utilize AI-driven diagnostic tools to identify individual learning gaps, recommend personalized practice pathways, and track mastery at each student's pace. Furthermore, students learn age-appropriate AI literacy, preparing them to be creators and critical users of technology.", 'sort_order' => 6, 'is_active' => 1, 'show_on_home' => 1],
            7 => ['id' => 7, 'category' => 'Academics', 'question' => 'How are assessments and examinations conducted?', 'answer' => "We follow a Continuous and Comprehensive Evaluation (CCE) model.\n\nAssessments include formative quizzes, project evaluations, oral presentations, and periodic proctored summative exams conducted via our secure platform with screen monitoring and identity verification.", 'sort_order' => 7, 'is_active' => 1, 'show_on_home' => 1],
            8 => ['id' => 8, 'category' => 'Support & Interaction', 'question' => 'How do parents stay informed about their child’s progress?', 'answer' => "Parents receive dedicated access to our Parent Portal and mobile app, featuring real-time attendance, grade reports, and assignment status.\n\nWe hold regular scheduled Parent-Teacher Meetings (PTMs) every quarter, alongside monthly progress reports and open communication channels with academic mentors.", 'sort_order' => 8, 'is_active' => 1, 'show_on_home' => 1],
            9 => ['id' => 9, 'category' => 'Schooling & Board', 'question' => 'Can my child appear for board exams through NIOS or other boards?', 'answer' => "Yes. Zuvio prepares students to seamlessly register and appear as candidates through the National Institute of Open Schooling (NIOS), which is recognized by the Government of India, AIU, and universities worldwide on par with CBSE and ICSE.\n\nWe provide complete guidance throughout the registration, examination centre selection, and practical submission process.", 'sort_order' => 9, 'is_active' => 1, 'show_on_home' => 1],
            10 => ['id' => 10, 'category' => 'Schooling & Board', 'question' => 'Can my child transition back to an offline/physical school later?', 'answer' => "Absolutely. Because our curriculum follows CBSE and NCF standards, and student transcripts and report cards are formally certified, credits and grade levels transfer smoothly to any recognized offline school across India or internationally.", 'sort_order' => 10, 'is_active' => 1, 'show_on_home' => 1],
            11 => ['id' => 11, 'category' => 'Support & Interaction', 'question' => 'What co-curricular activities and clubs are offered?', 'answer' => "Education extends well beyond textbooks. Zuvio offers a wide spectrum of virtual clubs including Coding & Robotics, Public Speaking & Debating, Visual Arts & Animation, Chess, Creative Writing, Environmental & Science Club, and Yoga & Mindfulness.\n\nClub sessions happen every Friday afternoon.", 'sort_order' => 11, 'is_active' => 1, 'show_on_home' => 1],
            12 => ['id' => 12, 'category' => 'Support & Interaction', 'question' => 'How do students interact and make friends in an online environment?', 'answer' => "Social-emotional learning is core to Zuvio. Students interact during daily morning circle assemblies, collaborative team projects, supervised club meetups, and house system events.\n\nWe also organize regional offline student meetups and celebration days where families can connect in person.", 'sort_order' => 12, 'is_active' => 1, 'show_on_home' => 1],
            13 => ['id' => 13, 'category' => 'Support & Interaction', 'question' => 'How is student progress monitored and supported if a child falls behind?', 'answer' => "Our 1:15 educator-to-student ratio ensures no child is overlooked. If diagnostic metrics indicate a student is struggling with a topic, personalized remedial sessions and one-on-one doubt clearing are scheduled at no extra cost.", 'sort_order' => 13, 'is_active' => 1, 'show_on_home' => 0],
            14 => ['id' => 14, 'category' => 'Support & Interaction', 'question' => 'What academic and emotional support is provided for special learners?', 'answer' => "Zuvio maintains an inclusive Special Education Needs (SEN) wing. We provide Individualized Education Plans (IEPs), modified pacing, differentiated instruction materials, and access to certified child counselors and special educators.", 'sort_order' => 14, 'is_active' => 1, 'show_on_home' => 0],
            15 => ['id' => 15, 'category' => 'Technology & AI', 'question' => 'What about screen time? Is so much screen exposure healthy for children?', 'answer' => "We follow strict age-appropriate digital wellness guidelines.\n\nScreen time is limited, lessons feature frequent physical movement breaks, audio-only modules, and mandatory offline reading and creative assignments. We partner with parents to ensure healthy posture, eye-rest cycles, and screen-free evenings.", 'sort_order' => 15, 'is_active' => 1, 'show_on_home' => 0],
            16 => ['id' => 16, 'category' => 'Admissions', 'question' => 'Can international and NRI students enrol at Zuvio?', 'answer' => "Yes! We welcome students from across the globe, including GCC, Southeast Asia, Europe, and the Americas.\n\nOur timetable accommodates multiple international time-zones, and we assist international students with transcript attestation and equivalence certificates.", 'sort_order' => 16, 'is_active' => 1, 'show_on_home' => 0],
            17 => ['id' => 17, 'category' => 'Admissions', 'question' => 'Can a child enrol mid-term or during Term 2?', 'answer' => "Yes. We accept mid-year enrolments subject to seat availability. Our academic team conducts a bridge assessment and provides personalized catch-up modules to bring the student comfortably up to speed with their cohort.", 'sort_order' => 17, 'is_active' => 1, 'show_on_home' => 0],
            18 => ['id' => 18, 'category' => 'Schooling & Board', 'question' => 'What makes Zuvio fundamentally different from other online schools?', 'answer' => "Zuvio is built on personalized mastery, not broadcast lecturing. We feature small cohorts (max 15 students), NEP 2020 & Oxford curriculum alignment, hands-on Project-Based Learning, AI-powered diagnostic tutoring, verified mental wellness support, and transparent parent involvement.\n\nWe don't replace childhood with a screen; we enrich education with purposeful innovation.", 'sort_order' => 18, 'is_active' => 1, 'show_on_home' => 0]
        ];
    }
}

// 1. Action: Toggle Active or Toggle Home
if ($action === 'toggle_active' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("UPDATE `faqs` SET `is_active` = NOT `is_active` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        if (isset($_SESSION['mock_faqs'][$id])) {
            $_SESSION['mock_faqs'][$id]['is_active'] = $_SESSION['mock_faqs'][$id]['is_active'] ? 0 : 1;
        }
    }
    header('Location: /admin/faqs.php?msg=updated');
    exit;
}

if ($action === 'toggle_home' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("UPDATE `faqs` SET `show_on_home` = NOT `show_on_home` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        if (isset($_SESSION['mock_faqs'][$id])) {
            $_SESSION['mock_faqs'][$id]['show_on_home'] = $_SESSION['mock_faqs'][$id]['show_on_home'] ? 0 : 1;
        }
    }
    header('Location: /admin/faqs.php?msg=updated');
    exit;
}

// 2. Action: Delete
if ($action === 'delete' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("DELETE FROM `faqs` WHERE `id` = ?");
        $stmt->execute([$id]);
    } else {
        unset($_SESSION['mock_faqs'][$id]);
    }
    header('Location: /admin/faqs.php?msg=deleted');
    exit;
}

// 3. Action: Save (Create / Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'edit'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $question = trim($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $category = trim($_POST['category'] ?? 'Academics');
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $show_on_home = isset($_POST['show_on_home']) ? 1 : 0;

        if (empty($question) || empty($answer)) {
            $error = 'Question and Answer are required.';
        } else {
            if ($action === 'edit' && $id > 0) {
                if (!$use_mock && $db) {
                    $stmt = $db->prepare("
                        UPDATE `faqs` 
                        SET `question` = ?, `answer` = ?, `category` = ?, `sort_order` = ?, `is_active` = ?, `show_on_home` = ? 
                        WHERE `id` = ?
                    ");
                    $stmt->execute([$question, $answer, $category, $sort_order, $is_active, $show_on_home, $id]);
                } else {
                    $_SESSION['mock_faqs'][$id] = [
                        'id' => $id,
                        'question' => $question,
                        'answer' => $answer,
                        'category' => $category,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active,
                        'show_on_home' => $show_on_home
                    ];
                }
                header('Location: /admin/faqs.php?msg=saved');
                exit;
            } elseif ($action === 'create') {
                if (!$use_mock && $db) {
                    $stmt = $db->prepare("
                        INSERT INTO `faqs` (`question`, `answer`, `category`, `sort_order`, `is_active`, `show_on_home`) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$question, $answer, $category, $sort_order, $is_active, $show_on_home]);
                } else {
                    $new_id = empty($_SESSION['mock_faqs']) ? 1 : max(array_keys($_SESSION['mock_faqs'])) + 1;
                    $_SESSION['mock_faqs'][$new_id] = [
                        'id' => $new_id,
                        'question' => $question,
                        'answer' => $answer,
                        'category' => $category,
                        'sort_order' => $sort_order,
                        'is_active' => $is_active,
                        'show_on_home' => $show_on_home
                    ];
                }
                header('Location: /admin/faqs.php?msg=created');
                exit;
            }
        }
    }
}

// Fetch FAQs
$faqs_list = [];
if (!$use_mock && $db) {
    try {
        $faqs_list = $db->query("SELECT * FROM `faqs` ORDER BY `sort_order` ASC, `id` ASC")->fetchAll();
    } catch (Exception $e) {
        $error = 'Database query failed.';
    }
} else {
    $faqs_list = array_values($_SESSION['mock_faqs']);
    usort($faqs_list, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
}

// Edit item data if editing
$edit_item = null;
if ($action === 'edit' && $id > 0) {
    if (!$use_mock && $db) {
        $stmt = $db->prepare("SELECT * FROM `faqs` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);
        $edit_item = $stmt->fetch();
    } else {
        $edit_item = $_SESSION['mock_faqs'][$id] ?? null;
    }
}

$page_slug = 'admin-faqs';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.5rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Parent FAQ Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Official 18-question FAQ knowledge base from <em>Zuvio_Parent_FAQ_Online_Schooling.pdf</em>. Toggle homepage placement, edit answers, or reorder questions.
    </p>
  </div>
  <div>
    <?php if ($action === 'list'): ?>
      <a href="/admin/faqs.php?action=create" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
        + Add New Question
      </a>
    <?php else: ?>
      <a href="/admin/faqs.php" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
        &larr; Back to FAQ List
      </a>
    <?php endif; ?>
  </div>
</div>

<?php if ($msg === 'saved' || $msg === 'updated'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    FAQ item updated successfully.
  </div>
<?php elseif ($msg === 'created'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    New FAQ question created successfully.
  </div>
<?php elseif ($msg === 'deleted'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-danger, #d9534f); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.85rem; margin-bottom: 1.5rem;">
    FAQ question removed.
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="error-alert" style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<?php if ($action === 'create' || ($action === 'edit' && $edit_item)): ?>
  <!-- CREATE / EDIT FORM -->
  <div class="card" style="padding: 2rem; max-width: 850px;">
    <h3 style="color: var(--color-navy); font-family: var(--font-secondary); margin-bottom: 1.5rem; font-size: 1.2rem;">
      <?php echo $action === 'create' ? 'Add New Parent FAQ' : 'Edit FAQ: #' . $edit_item['id']; ?>
    </h3>
    
    <form method="POST" action="/admin/faqs.php?action=<?php echo $action; ?><?php echo $id ? '&id=' . $id : ''; ?>">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Category</label>
        <select name="category" class="form-control" style="width: 100%; max-width: 320px; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
          <?php
          $cats = ['Academics', 'Schooling & Board', 'Technology & AI', 'Support & Interaction', 'Admissions'];
          $cur_cat = $edit_item['category'] ?? 'Academics';
          foreach ($cats as $c):
          ?>
            <option value="<?php echo h($c); ?>" <?php echo $cur_cat === $c ? 'selected' : ''; ?>><?php echo h($c); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Question</label>
        <input type="text" name="question" required value="<?php echo h($edit_item['question'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);" placeholder="e.g. Is Zuvio’s curriculum mapped to CBSE?">
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Answer (Verbatim Official Answer)</label>
        <textarea name="answer" required rows="6" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.5;"><?php echo h($edit_item['answer'] ?? ''); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; background: var(--color-surface); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.35rem; font-size: 0.85rem;">Display Order</label>
          <input type="number" name="sort_order" value="<?php echo (int)($edit_item['sort_order'] ?? 10); ?>" style="width: 100px; padding: 0.4rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.25rem;">
          <input type="checkbox" name="show_on_home" id="show_on_home" value="1" <?php echo !empty($edit_item['show_on_home']) ? 'checked' : ''; ?>>
          <label for="show_on_home" style="font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">Feature on Homepage Accordion</label>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.25rem;">
          <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo !isset($edit_item['is_active']) || !empty($edit_item['is_active']) ? 'checked' : ''; ?>>
          <label for="is_active" style="font-size: 0.85rem; font-weight: 600; color: var(--color-navy); cursor: pointer;">Published / Active</label>
        </div>
      </div>

      <div style="display: flex; gap: 1rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.5rem;">Save FAQ</button>
        <a href="/admin/faqs.php" class="btn btn-outline" style="padding: 0.6rem 1.5rem;">Cancel</a>
      </div>
    </form>
  </div>

<?php else: ?>
  <!-- LIST TABLE -->
  <div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
      <h3 style="font-size: 1.1rem; color: var(--color-navy); font-family: var(--font-secondary);">
        All Questions (<?php echo count($faqs_list); ?> total)
      </h3>
      <span style="font-size: 0.75rem; color: var(--color-muted);">
        Drag or edit numbers to reorder • Homepage items are shown on front page
      </span>
    </div>

    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
          <tr style="background-color: var(--color-surface); text-align: left; border-bottom: 2px solid var(--color-border);">
            <th style="padding: 0.75rem; width: 60px; color: var(--color-navy);">Order</th>
            <th style="padding: 0.75rem; width: 140px; color: var(--color-navy);">Category</th>
            <th style="padding: 0.75rem; color: var(--color-navy);">Question & Official Answer Snippet</th>
            <th style="padding: 0.75rem; width: 110px; text-align: center; color: var(--color-navy);">Homepage</th>
            <th style="padding: 0.75rem; width: 90px; text-align: center; color: var(--color-navy);">Status</th>
            <th style="padding: 0.75rem; width: 140px; text-align: right; color: var(--color-navy);">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($faqs_list)): ?>
            <tr>
              <td colspan="6" style="padding: 2rem; text-align: center; color: var(--color-muted);">No FAQs found.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($faqs_list as $f): ?>
              <tr style="border-bottom: 1px solid var(--color-border);">
                <td style="padding: 0.75rem; font-weight: 700; color: var(--color-muted);">
                  #<?php echo (int)$f['sort_order']; ?>
                </td>
                <td style="padding: 0.75rem;">
                  <span style="display: inline-block; padding: 0.2rem 0.5rem; background: var(--color-surface-blue); color: var(--color-navy); border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                    <?php echo h($f['category']); ?>
                  </span>
                </td>
                <td style="padding: 0.75rem;">
                  <div style="font-weight: 600; color: var(--color-navy); margin-bottom: 0.25rem;">
                    <?php echo h($f['question']); ?>
                  </div>
                  <div style="color: var(--color-muted); font-size: 0.75rem; line-height: 1.4; max-height: 38px; overflow: hidden; text-overflow: ellipsis;">
                    <?php echo h(substr($f['answer'], 0, 140)); ?>...
                  </div>
                </td>
                <td style="padding: 0.75rem; text-align: center;">
                  <a href="/admin/faqs.php?action=toggle_home&id=<?php echo $f['id']; ?>" style="text-decoration: none;">
                    <?php if (!empty($f['show_on_home'])): ?>
                      <span style="background: #e1f5fe; color: #0288d1; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Featured</span>
                    <?php else: ?>
                      <span style="background: #f5f5f5; color: #9e9e9e; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Only Full FAQ</span>
                    <?php endif; ?>
                  </a>
                </td>
                <td style="padding: 0.75rem; text-align: center;">
                  <a href="/admin/faqs.php?action=toggle_active&id=<?php echo $f['id']; ?>" style="text-decoration: none;">
                    <?php if (!empty($f['is_active'])): ?>
                      <span style="background: #e8f5e9; color: #2e7d32; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Active</span>
                    <?php else: ?>
                      <span style="background: #ffebee; color: #c62828; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 700;">Inactive</span>
                    <?php endif; ?>
                  </a>
                </td>
                <td style="padding: 0.75rem; text-align: right; white-space: nowrap;">
                  <a href="/admin/faqs.php?action=edit&id=<?php echo $f['id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; margin-right: 0.35rem;">
                    Edit
                  </a>
                  <a href="/admin/faqs.php?action=delete&id=<?php echo $f['id']; ?>" onclick="return confirm('Are you sure you want to delete this FAQ question?');" style="color: #c62828; font-size: 0.75rem; text-decoration: none; padding: 0.3rem;">
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
