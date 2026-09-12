<?php
// Zuvio Global School - Admin Beyond CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'cocurricular';

// Ensure $_SESSION['mock_beyond_cms'] is populated
if (!isset($_SESSION['mock_beyond_cms'])) {
    $_SESSION['mock_beyond_cms'] = [];
}
$beyond_cms = &$_SESSION['mock_beyond_cms'];

// 1. Defaults for Co-curricular Clubs
if (!isset($beyond_cms['cocurricular_clubs'])) {
    $beyond_cms['cocurricular_clubs'] = [
        [
            'id' => 1,
            'title' => 'Classical Dance',
            'stage' => 'Preparatory & Middle School',
            'stage_key' => 'prep_mid',
            'desc' => 'Bharatnatyam, Kathak and classical dance training conducted by trained classical artists. Focuses on rhythm, expression, and cultural heritage.',
            'icon' => '💃',
            'schedule' => 'Twice weekly live cohort',
            'sort_order' => 1,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'title' => 'Drawing & Painting',
            'stage' => 'All Stages (KG to Grade 8)',
            'stage_key' => 'all',
            'desc' => 'Sketching, watercolour, acrylic and digital art guided by certified visual artists. Encourages self-expression, color theory, and spatial creativity.',
            'icon' => '🎨',
            'schedule' => 'Weekly live studio session',
            'sort_order' => 2,
            'is_published' => 1
        ],
        [
            'id' => 3,
            'title' => 'Public Speaking & Debate',
            'stage' => 'Preparatory & Middle School',
            'stage_key' => 'prep_mid',
            'desc' => 'Debate, elocution, presentation and impromptu speech skills building confident, articulate communicators and future leaders.',
            'icon' => '🎙️',
            'schedule' => 'Weekly live forensics meet',
            'sort_order' => 3,
            'is_published' => 1
        ],
        [
            'id' => 4,
            'title' => 'Rubik’s Cube Club',
            'stage' => 'All Stages (Age 6+)',
            'stage_key' => 'all',
            'desc' => 'Speed-solving methods from beginner layer-by-layer to advanced CFOP algorithms. Develops concentration, memory, and calm problem-solving.',
            'icon' => '🧩',
            'schedule' => 'Weekly speed-solving workshop',
            'sort_order' => 4,
            'is_published' => 1
        ],
        [
            'id' => 5,
            'title' => 'Western Dance',
            'stage' => 'All Stages (KG to Grade 8)',
            'stage_key' => 'all',
            'desc' => 'Hip-hop, contemporary and freestyle dance for all age groups. Enhances stamina, coordination, musicality, and stage confidence.',
            'icon' => '🕺',
            'schedule' => 'Twice weekly active movement',
            'sort_order' => 5,
            'is_published' => 1
        ],
        [
            'id' => 6,
            'title' => 'Yoga & Mindfulness',
            'stage' => 'All Stages (KG to Grade 8)',
            'stage_key' => 'all',
            'desc' => 'Daily guided asanas, pranayama breathing exercises, and mindfulness techniques for physical flexibility, posture, and emotional wellness.',
            'icon' => '🧘',
            'schedule' => 'Morning wellness sessions',
            'sort_order' => 6,
            'is_published' => 1
        ],
        [
            'id' => 7,
            'title' => 'Strategic Chess Club',
            'stage' => 'Preparatory & Middle School',
            'stage_key' => 'prep_mid',
            'desc' => 'Tactical problem-solving, opening strategies, endgame calculation, and competitive tournament preparation with rated coaches.',
            'icon' => '♟️',
            'schedule' => 'Weekly tournaments & review',
            'sort_order' => 7,
            'is_published' => 1
        ],
        [
            'id' => 8,
            'title' => 'Vocal Music',
            'stage' => 'All Stages (KG to Grade 8)',
            'stage_key' => 'all',
            'desc' => 'Hindustani classical and Western vocal training from beginner pitch matching to advanced melodic improvisation and performance.',
            'icon' => '🎵',
            'schedule' => 'Twice weekly vocal lab',
            'sort_order' => 8,
            'is_published' => 1
        ],
        [
            'id' => 9,
            'title' => 'Coding & App Development',
            'stage' => 'Grades 1 to 8',
            'stage_key' => 'prep_mid',
            'desc' => 'Block-based Scratch programming, Python syntax, web development, and algorithmic logic. Students create games, stories, and apps.',
            'icon' => '💻',
            'schedule' => 'Weekly project-based lab',
            'sort_order' => 9,
            'is_published' => 1
        ],
        [
            'id' => 10,
            'title' => 'Robotics & AI Explorers',
            'stage' => 'Middle School (Grades 6–8)',
            'stage_key' => 'middle',
            'desc' => 'Hands-on robotics kits, sensors, AI prompt engineering, machine learning concepts, and STEM innovation challenges.',
            'icon' => '🤖',
            'schedule' => 'Weekly innovation challenge',
            'sort_order' => 10,
            'is_published' => 1
        ]
    ];
}

// 2. Defaults for Hybrid Campus
if (!isset($beyond_cms['hybrid_campus'])) {
    $beyond_cms['hybrid_campus'] = [
        [
            'id' => 1,
            'title' => 'Practical Experiments & Interactive Projects',
            'desc' => 'Guided hands-on project kits shipped directly to families for interactive experiments that reinforce real-world scientific discovery.',
            'icon' => '🔬'
        ],
        [
            'id' => 2,
            'title' => 'AR-VR Astronomy & Immersive Simulations',
            'desc' => 'Augmented reality planetarium experiences, digital cosmos exploration, and interactive simulations that bring abstract concepts to life.',
            'icon' => '🌌'
        ],
        [
            'id' => 3,
            'title' => 'Robotics & Innovation Socialization',
            'desc' => 'Hands-on cohort builds and peer collaboration where young creators build automated systems and share working prototypes.',
            'icon' => '⚙️'
        ],
        [
            'id' => 4,
            'title' => 'Creative Play & Discovery for Younger Learners',
            'desc' => 'Sensory exploration, motor-skill development, storytelling sessions, and tactile craft activities designed specifically for foundational years.',
            'icon' => '🧸'
        ],
        [
            'id' => 5,
            'title' => 'Collaborative Meets & Regional Hubs',
            'desc' => 'Organised regional physical gatherings, local sports meets, and peer community field experiences connecting online classmates in person.',
            'icon' => '🤝'
        ]
    ];
}

// 3. Defaults for Student Achievers
if (!isset($beyond_cms['student_achievers'])) {
    $beyond_cms['student_achievers'] = [
        [
            'id' => 1,
            'name' => 'Fatima Ismath',
            'category' => 'Extracurricular Achievements',
            'category_key' => 'extracurricular',
            'badge' => '🌟 Star Kid',
            'description' => 'Recognized for distinguished multi-disciplinary excellence across creative writing, school leadership, and extracurricular achievements.',
            'image' => '/assets/images/Profile_Images/Student_1.png',
            'sort_order' => 1,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'name' => 'Tahura Riffath',
            'category' => 'Creative Arts & Design',
            'category_key' => 'arts',
            'badge' => '🎨 Arts Winner',
            'description' => 'Secured top honors in the Card-Making Competition & Creative Arts Showcase, demonstrating meticulous aesthetic creativity and visual design.',
            'image' => '/assets/images/Profile_Images/Student_2.png',
            'sort_order' => 2,
            'is_published' => 1
        ],
        [
            'id' => 3,
            'name' => 'Mohammed Owais Shaikh',
            'category' => 'Martial Arts & Sports',
            'category_key' => 'sports',
            'badge' => '🥋 Taekwondo Champion',
            'description' => 'Demonstrating physical excellence, mental discipline, and competitive triumph in Japanese Taekwondo tournaments.',
            'image' => '/assets/images/Profile_Images/Student_3.png',
            'sort_order' => 3,
            'is_published' => 1
        ],
        [
            'id' => 4,
            'name' => 'Venkatesh JSN',
            'category' => 'Mind Sports & Chess',
            'category_key' => 'chess',
            'badge' => '♟️ Chess Tournaments',
            'description' => 'Remarkable strategic performance and competitive success in junior chess tournaments, exhibiting deep analytical foresight and tactical patience.',
            'image' => '/assets/images/Profile_Images/Student_4.png',
            'sort_order' => 4,
            'is_published' => 1
        ],
        [
            'id' => 5,
            'name' => 'Haripriya Banerjee',
            'category' => 'Modeling & Performing Arts',
            'category_key' => 'arts',
            'badge' => '📸 Modeling & Grand Shoots',
            'description' => 'Recognized in the World of Modeling and Grand Shoots, showcasing exceptional confidence, poise, and expressive presentation skills.',
            'image' => '/assets/images/Profile_Images/Student_5.png',
            'sort_order' => 5,
            'is_published' => 1
        ],
        [
            'id' => 6,
            'name' => 'Inaya Shaikh',
            'category' => 'Olympiad & Mathematics',
            'category_key' => 'academics',
            'badge' => '📐 Math Olympiad (IFMO)',
            'description' => 'Achieved top percentiles in the International Finance & Mathematics Olympiad (IFMO), showcasing advanced arithmetic agility and logical problem-solving.',
            'image' => '/assets/images/Profile_Images/Student_6.png',
            'sort_order' => 6,
            'is_published' => 1
        ]
    ];
}

// 4. Defaults for Gallery
if (!isset($beyond_cms['gallery'])) {
    $beyond_cms['gallery'] = [
        [
            'id' => 1,
            'title' => 'Live Interactive Classroom Session',
            'category' => 'Live Classes',
            'category_key' => 'live',
            'caption' => 'Students engaged in active peer dialogue, interactive whiteboard problem-solving, and live questioning with mentors.',
            'image' => '/assets/images/Students learning in classroom.png',
            'sort_order' => 1,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'title' => 'Personalized Teacher Mentorship',
            'category' => 'Live Classes',
            'category_key' => 'live',
            'caption' => 'Dedicated educator providing 1-on-1 pacing support, ensuring every student is seen, heard, and guided with care.',
            'image' => '/assets/images/Teacher interacting with students.png',
            'sort_order' => 2,
            'is_published' => 1
        ],
        [
            'id' => 3,
            'title' => 'Hands-on Robotics & Tech Exploration',
            'category' => 'Projects & STEM',
            'category_key' => 'stem',
            'caption' => 'Young innovators assembling sensors, coding circuits, and testing automated prototypes in live STEM cohort labs.',
            'image' => '/assets/images/Zuvio_Beyond_Website_Images/03_Robotics.jpg',
            'sort_order' => 3,
            'is_published' => 1
        ],
        [
            'id' => 4,
            'title' => 'AI Explorers & Creative Prompting',
            'category' => 'Projects & STEM',
            'category_key' => 'stem',
            'caption' => 'Early introduction to machine learning principles, pattern recognition, and creative digital storytelling.',
            'image' => '/assets/images/Zuvio_Beyond_Website_Images/01_AI_Explorers.jpg',
            'sort_order' => 4,
            'is_published' => 1
        ]
    ];
}

// 5. Defaults for Virtual Classroom
if (!isset($beyond_cms['virtual_classroom'])) {
    $beyond_cms['virtual_classroom'] = [
        [
            'id' => 1,
            'title' => 'Interactive Online Class — Live at Zuvio Global School',
            'category' => 'Live Class',
            'category_key' => 'live',
            'badge' => '📹 Live Class',
            'duration' => '3:45 mins',
            'description' => 'Experience how our teachers engage students through real-time dialogue, digital whiteboarding, active polling, and personalized questioning in small cohorts.',
            'thumbnail' => '/assets/images/Students learning in classroom.png',
            'video_url' => '/assets/images/01_Collaborative_Project_Learning.mp4',
            'sort_order' => 1,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'title' => 'Student Learning & Hands-On Activity Session',
            'category' => 'Student Activity',
            'category_key' => 'activity',
            'badge' => '💡 Student Activity',
            'duration' => '4:12 mins',
            'description' => 'Watch young learners collaborate on interdisciplinary challenges, break down complex concepts, and build creative solutions together.',
            'thumbnail' => '/assets/images/Teacher interacting with students.png',
            'video_url' => '/assets/images/04_Student_Presentation_Learning.mp4',
            'sort_order' => 2,
            'is_published' => 1
        ]
    ];
}

// Handle Form Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // --- Actions for Co-curricular Clubs ---
    if ($action === 'add_club') {
        $title = trim($_POST['title'] ?? '');
        $stage = trim($_POST['stage'] ?? '');
        $stage_key = trim($_POST['stage_key'] ?? 'prep_mid');
        $desc = trim($_POST['desc'] ?? '');
        $icon = trim($_POST['icon'] ?? '🌟');
        $schedule = trim($_POST['schedule'] ?? 'Weekly session');
        
        if ($title) {
            $new_id = time();
            $beyond_cms['cocurricular_clubs'][] = [
                'id' => $new_id,
                'title' => $title,
                'stage' => $stage,
                'stage_key' => $stage_key,
                'desc' => $desc,
                'icon' => $icon,
                'schedule' => $schedule,
                'sort_order' => count($beyond_cms['cocurricular_clubs']) + 1,
                'is_published' => 1
            ];
            header("Location: /admin/beyond-cms.php?tab=cocurricular&msg=Club+added+successfully");
            exit;
        }
    } elseif ($action === 'delete_club') {
        $id = (int)($_POST['id'] ?? 0);
        $beyond_cms['cocurricular_clubs'] = array_values(array_filter($beyond_cms['cocurricular_clubs'], function($c) use ($id) {
            return $c['id'] != $id;
        }));
        header("Location: /admin/beyond-cms.php?tab=cocurricular&msg=Club+deleted+successfully");
        exit;
    } elseif ($action === 'toggle_club_publish') {
        $id = (int)($_POST['id'] ?? 0);
        foreach ($beyond_cms['cocurricular_clubs'] as &$c) {
            if ($c['id'] == $id) {
                $c['is_published'] = empty($c['is_published']) ? 1 : 0;
                break;
            }
        }
        header("Location: /admin/beyond-cms.php?tab=cocurricular&msg=Club+status+updated");
        exit;
    }

    // --- Actions for Student Achievers ---
    elseif ($action === 'add_achiever') {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $category_key = trim($_POST['category_key'] ?? 'extracurricular');
        $badge = trim($_POST['badge'] ?? '🌟 Star Kid');
        $desc = trim($_POST['description'] ?? '');
        
        if ($name) {
            $new_id = time();
            $beyond_cms['student_achievers'][] = [
                'id' => $new_id,
                'name' => $name,
                'category' => $category,
                'category_key' => $category_key,
                'badge' => $badge,
                'description' => $desc,
                'image' => '/assets/images/Profile_Images/Student_1.png',
                'sort_order' => count($beyond_cms['student_achievers']) + 1,
                'is_published' => 1
            ];
            header("Location: /admin/beyond-cms.php?tab=achievers&msg=Achiever+added+successfully");
            exit;
        }
    } elseif ($action === 'delete_achiever') {
        $id = (int)($_POST['id'] ?? 0);
        $beyond_cms['student_achievers'] = array_values(array_filter($beyond_cms['student_achievers'], function($a) use ($id) {
            return $a['id'] != $id;
        }));
        header("Location: /admin/beyond-cms.php?tab=achievers&msg=Achiever+removed+successfully");
        exit;
    } elseif ($action === 'toggle_achiever_publish') {
        $id = (int)($_POST['id'] ?? 0);
        foreach ($beyond_cms['student_achievers'] as &$a) {
            if ($a['id'] == $id) {
                $a['is_published'] = empty($a['is_published']) ? 1 : 0;
                break;
            }
        }
        header("Location: /admin/beyond-cms.php?tab=achievers&msg=Achiever+status+updated");
        exit;
    }

    // --- Actions for Gallery ---
    elseif ($action === 'add_gallery') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'Live Classes');
        $category_key = trim($_POST['category_key'] ?? 'live');
        $caption = trim($_POST['caption'] ?? '');
        $image = trim($_POST['image'] ?? '/assets/images/Students learning in classroom.png');
        
        if ($title) {
            $new_id = time();
            $beyond_cms['gallery'][] = [
                'id' => $new_id,
                'title' => $title,
                'category' => $category,
                'category_key' => $category_key,
                'caption' => $caption,
                'image' => $image,
                'sort_order' => count($beyond_cms['gallery']) + 1,
                'is_published' => 1
            ];
            header("Location: /admin/beyond-cms.php?tab=gallery&msg=Gallery+item+added");
            exit;
        }
    } elseif ($action === 'delete_gallery') {
        $id = (int)($_POST['id'] ?? 0);
        $beyond_cms['gallery'] = array_values(array_filter($beyond_cms['gallery'], function($g) use ($id) {
            return $g['id'] != $id;
        }));
        header("Location: /admin/beyond-cms.php?tab=gallery&msg=Gallery+item+deleted");
        exit;
    }

    // --- Actions for Virtual Classroom Videos ---
    elseif ($action === 'add_video') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'Live Class');
        $category_key = trim($_POST['category_key'] ?? 'live');
        $badge = trim($_POST['badge'] ?? '📹 Live Class');
        $duration = trim($_POST['duration'] ?? '3:30 mins');
        $description = trim($_POST['description'] ?? '');
        $video_url = trim($_POST['video_url'] ?? '/assets/images/01_Collaborative_Project_Learning.mp4');
        $thumbnail = trim($_POST['thumbnail'] ?? '/assets/images/Students learning in classroom.png');
        
        if ($title) {
            $new_id = time();
            $beyond_cms['virtual_classroom'][] = [
                'id' => $new_id,
                'title' => $title,
                'category' => $category,
                'category_key' => $category_key,
                'badge' => $badge,
                'duration' => $duration,
                'description' => $description,
                'video_url' => $video_url,
                'thumbnail' => $thumbnail,
                'sort_order' => count($beyond_cms['virtual_classroom']) + 1,
                'is_published' => 1
            ];
            header("Location: /admin/beyond-cms.php?tab=classroom&msg=Video+item+added");
            exit;
        }
    } elseif ($action === 'delete_video') {
        $id = (int)($_POST['id'] ?? 0);
        $beyond_cms['virtual_classroom'] = array_values(array_filter($beyond_cms['virtual_classroom'], function($v) use ($id) {
            return $v['id'] != $id;
        }));
        header("Location: /admin/beyond-cms.php?tab=classroom&msg=Video+item+deleted");
        exit;
    }
}

$current_page = 'admin-beyond-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div class="main-content">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;">CMS Module</span>
      <h1 style="color: var(--color-navy); font-size: 2rem; margin: 0.25rem 0 0 0;">Beyond CMS Manager</h1>
      <p style="color: var(--color-muted); margin: 0.25rem 0 0 0; font-size: 0.95rem;">
        Manage Co-curricular &amp; Global Clubs, Hybrid Campus, Student Achievers, Gallery, and Inside Virtual Classroom.
      </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
      <a href="/beyond" target="_blank" class="btn" style="background: #FFFFFF; border: 1.5px solid var(--color-navy); color: var(--color-navy); font-size: 0.85rem; padding: 0.6rem 1.25rem; font-weight: 600; text-decoration: none; border-radius: var(--radius-sm);">
        Preview Beyond &nearr;
      </a>
      <a href="/beyond/co-curricular" target="_blank" class="btn" style="background: var(--color-navy); color: #FFFFFF; font-size: 0.85rem; padding: 0.6rem 1.25rem; font-weight: 600; text-decoration: none; border-radius: var(--radius-sm);">
        Preview Clubs &nearr;
      </a>
    </div>
  </div>

  <?php if ($msg): ?>
    <div style="background-color: #DEF7EC; border: 1px solid #31C48D; color: #03543F; padding: 1rem 1.5rem; border-radius: 6px; margin-bottom: 2rem; font-size: 0.95rem;">
      <strong>Success!</strong> <?php echo h($msg); ?>
    </div>
  <?php endif; ?>

  <!-- Modular CMS Tabs -->
  <div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; overflow-x: auto; padding-bottom: 2px;">
    <a href="?tab=cocurricular" style="padding: 0.75rem 1.5rem; font-weight: 700; font-size: 0.9rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'cocurricular' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'cocurricular' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      1. Co-curricular &amp; Clubs
    </a>
    <a href="?tab=hybrid" style="padding: 0.75rem 1.5rem; font-weight: 700; font-size: 0.9rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'hybrid' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'hybrid' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      2. Hybrid Campus
    </a>
    <a href="?tab=achievers" style="padding: 0.75rem 1.5rem; font-weight: 700; font-size: 0.9rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'achievers' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'achievers' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      3. Student Achievers
    </a>
    <a href="?tab=gallery" style="padding: 0.75rem 1.5rem; font-weight: 700; font-size: 0.9rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'gallery' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'gallery' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      4. Photo Gallery
    </a>
    <a href="?tab=classroom" style="padding: 0.75rem 1.5rem; font-weight: 700; font-size: 0.9rem; text-decoration: none; border-bottom: 3px solid <?php echo $tab === 'classroom' ? 'var(--color-navy)' : 'transparent'; ?>; color: <?php echo $tab === 'classroom' ? 'var(--color-navy)' : 'var(--color-muted)'; ?>;">
      5. Virtual Classroom
    </a>
  </div>

  <!-- TAB 1: CO-CURRICULAR & CLUBS -->
  <?php if ($tab === 'cocurricular'): ?>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
      <div>
        <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
          <h3 style="color: var(--color-navy); font-size: 1.35rem; margin-top: 0; margin-bottom: 1.25rem;">
            Active Co-curricular &amp; Global Clubs (<?php echo count($beyond_cms['cocurricular_clubs']); ?>)
          </h3>
          <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($beyond_cms['cocurricular_clubs'] as $c): ?>
              <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                  <span style="font-size: 2rem;"><?php echo h($c['icon']); ?></span>
                  <div>
                    <h4 style="margin: 0; color: var(--color-navy); font-size: 1.1rem; font-weight: 700;">
                      <?php echo h($c['title']); ?>
                    </h4>
                    <span style="font-size: 0.8rem; color: var(--color-teal); font-weight: 600;">
                      <?php echo h($c['stage']); ?> &bull; <?php echo h($c['schedule']); ?>
                    </span>
                    <p style="color: var(--color-muted); font-size: 0.85rem; margin: 0.35rem 0 0 0; line-height: 1.4;">
                      <?php echo h($c['desc']); ?>
                    </p>
                  </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
                  <form method="POST" style="margin: 0;">
                    <input type="hidden" name="action" value="toggle_club_publish">
                    <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                    <button type="submit" style="background: <?php echo !empty($c['is_published']) ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?php echo !empty($c['is_published']) ? '#03543F' : '#9B1C1C'; ?>; border: 1px solid <?php echo !empty($c['is_published']) ? '#31C48D' : '#F98080'; ?>; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                      <?php echo !empty($c['is_published']) ? 'Published' : 'Hidden'; ?>
                    </button>
                  </form>
                  <form method="POST" onsubmit="return confirm('Delete this club?');" style="margin: 0;">
                    <input type="hidden" name="action" value="delete_club">
                    <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>">
                    <button type="submit" style="background: #FDE8E8; color: #9B1C1C; border: 1px solid #F98080; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Add Club Form -->
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-top: 0; margin-bottom: 1.25rem;">
          Add New Club
        </h3>
        <form method="POST">
          <input type="hidden" name="action" value="add_club">
          
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Club Title *</label>
            <input type="text" name="title" required placeholder="e.g. Creative Writers Club" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Stage Label *</label>
            <input type="text" name="stage" required placeholder="e.g. Preparatory & Middle School" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Stage Filter Key *</label>
            <select name="stage_key" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; background: #fff;">
              <option value="foundational">Foundational Stage (KG–Gr 2)</option>
              <option value="prep_mid" selected>Preparatory &amp; Middle (Gr 3–8)</option>
              <option value="middle">Middle School Only (Gr 6–8)</option>
              <option value="all">All Stages</option>
            </select>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem; margin-bottom: 1rem;">
            <div>
              <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Icon Emoji</label>
              <input type="text" name="icon" value="🌟" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; text-align: center;">
            </div>
            <div>
              <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Schedule</label>
              <input type="text" name="schedule" value="Weekly live cohort" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
            </div>
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Description *</label>
            <textarea name="desc" required rows="3" placeholder="Brief description of club objectives..." style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; font-family: inherit;"></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--color-navy); color: #fff; padding: 0.75rem; border: none; font-weight: 700; border-radius: var(--radius-sm); cursor: pointer;">
            Add Club &rarr;
          </button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <!-- TAB 2: HYBRID CAMPUS -->
  <?php if ($tab === 'hybrid'): ?>
    <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
      <h3 style="color: var(--color-navy); font-size: 1.35rem; margin-top: 0; margin-bottom: 1.5rem;">
        Hybrid Experiential Campus Facets (Page 60 Reference)
      </h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <?php foreach ($beyond_cms['hybrid_campus'] as $facet): ?>
          <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;"><?php echo h($facet['icon']); ?></div>
            <h4 style="color: var(--color-navy); font-size: 1.15rem; margin: 0 0 0.5rem 0;"><?php echo h($facet['title']); ?></h4>
            <p style="color: var(--color-muted); font-size: 0.88rem; line-height: 1.5; margin: 0;"><?php echo h($facet['desc']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- TAB 3: STUDENT ACHIEVERS -->
  <?php if ($tab === 'achievers'): ?>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.35rem; margin-top: 0; margin-bottom: 1.5rem;">
          Verified Student Achievers (<?php echo count($beyond_cms['student_achievers']); ?>)
        </h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <?php foreach ($beyond_cms['student_achievers'] as $a): ?>
            <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
              <div>
                <span style="background: var(--color-surface-warm); color: var(--color-navy); border: 1px solid var(--color-border); font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 12px;">
                  <?php echo h($a['badge']); ?>
                </span>
                <h4 style="margin: 0.35rem 0 0.2rem 0; color: var(--color-navy); font-size: 1.15rem; font-weight: 700;">
                  <?php echo h($a['name']); ?>
                </h4>
                <span style="font-size: 0.85rem; color: var(--color-teal); font-weight: 600;"><?php echo h($a['category']); ?></span>
                <p style="color: var(--color-muted); font-size: 0.88rem; margin: 0.35rem 0 0 0; line-height: 1.4;">
                  <?php echo h($a['description']); ?>
                </p>
              </div>
              <div style="display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0;">
                <form method="POST" style="margin: 0;">
                  <input type="hidden" name="action" value="toggle_achiever_publish">
                  <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
                  <button type="submit" style="background: <?php echo !empty($a['is_published']) ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?php echo !empty($a['is_published']) ? '#03543F' : '#9B1C1C'; ?>; border: 1px solid <?php echo !empty($a['is_published']) ? '#31C48D' : '#F98080'; ?>; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                    <?php echo !empty($a['is_published']) ? 'Published' : 'Hidden'; ?>
                  </button>
                </form>
                <form method="POST" onsubmit="return confirm('Delete achiever?');" style="margin: 0;">
                  <input type="hidden" name="action" value="delete_achiever">
                  <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
                  <button type="submit" style="background: #FDE8E8; color: #9B1C1C; border: 1px solid #F98080; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                    Delete
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Add Achiever Form -->
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-top: 0; margin-bottom: 1.25rem;">
          Add Student Achiever
        </h3>
        <form method="POST">
          <input type="hidden" name="action" value="add_achiever">
          
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Student Full Name *</label>
            <input type="text" name="name" required placeholder="e.g. Aarav Mehta" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Badge Label *</label>
            <input type="text" name="badge" value="🌟 Star Kid" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Category *</label>
            <input type="text" name="category" required placeholder="e.g. Martial Arts & Sports" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Filter Key *</label>
            <select name="category_key" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; background: #fff;">
              <option value="sports">Martial Arts &amp; Sports</option>
              <option value="chess">Chess &amp; Mind Sports</option>
              <option value="arts">Arts &amp; Creative</option>
              <option value="academics">Olympiads &amp; STEM</option>
              <option value="extracurricular" selected>Extracurricular</option>
            </select>
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Achievement Description *</label>
            <textarea name="description" required rows="3" placeholder="Factual description of achievement or competition..." style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; font-family: inherit;"></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--color-navy); color: #fff; padding: 0.75rem; border: none; font-weight: 700; border-radius: var(--radius-sm); cursor: pointer;">
            Save Achiever &rarr;
          </button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <!-- TAB 4: GALLERY -->
  <?php if ($tab === 'gallery'): ?>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.35rem; margin-top: 0; margin-bottom: 1.5rem;">
          Gallery Images (<?php echo count($beyond_cms['gallery']); ?>)
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
          <?php foreach ($beyond_cms['gallery'] as $g): ?>
            <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden; display: flex; flex-direction: column;">
              <img src="<?php echo h($g['image']); ?>" alt="" style="width: 100%; height: 130px; object-fit: cover;">
              <div style="padding: 0.75rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                  <h5 style="margin: 0 0 0.25rem 0; font-size: 0.95rem; color: var(--color-navy);"><?php echo h($g['title']); ?></h5>
                  <span style="font-size: 0.75rem; color: var(--color-teal); font-weight: 600;"><?php echo h($g['category']); ?></span>
                </div>
                <form method="POST" onsubmit="return confirm('Delete image?');" style="margin-top: 0.75rem;">
                  <input type="hidden" name="action" value="delete_gallery">
                  <input type="hidden" name="id" value="<?php echo (int)$g['id']; ?>">
                  <button type="submit" style="background: #FDE8E8; color: #9B1C1C; border: 1px solid #F98080; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; cursor: pointer; width: 100%;">
                    Remove
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Add Gallery Form -->
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-top: 0; margin-bottom: 1.25rem;">
          Add Photo to Gallery
        </h3>
        <form method="POST">
          <input type="hidden" name="action" value="add_gallery">
          
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Photo Title *</label>
            <input type="text" name="title" required placeholder="e.g. Robotics Kit Assembly" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Category *</label>
            <input type="text" name="category" required placeholder="e.g. Projects & STEM" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Category Key *</label>
            <select name="category_key" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; background: #fff;">
              <option value="live">Live Classes</option>
              <option value="stem">Projects &amp; STEM</option>
              <option value="arts">Creative Arts</option>
              <option value="mind">Mind Sports &amp; Chess</option>
            </select>
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Image URL *</label>
            <input type="text" name="image" required value="/assets/images/Students learning in classroom.png" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Caption *</label>
            <textarea name="caption" required rows="3" placeholder="Factual context for the image..." style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; font-family: inherit;"></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--color-navy); color: #fff; padding: 0.75rem; border: none; font-weight: 700; border-radius: var(--radius-sm); cursor: pointer;">
            Add to Gallery &rarr;
          </button>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <!-- TAB 5: VIRTUAL CLASSROOM VIDEOS -->
  <?php if ($tab === 'classroom'): ?>
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: flex-start;">
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.35rem; margin-top: 0; margin-bottom: 1.5rem;">
          Virtual Classroom Videos (<?php echo count($beyond_cms['virtual_classroom']); ?>)
        </h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <?php foreach ($beyond_cms['virtual_classroom'] as $v): ?>
            <div style="background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
              <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="<?php echo h($v['thumbnail']); ?>" alt="" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                <div>
                  <span style="font-size: 0.75rem; background: var(--color-surface-warm); color: var(--color-navy); padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">
                    <?php echo h($v['badge']); ?> &bull; <?php echo h($v['duration']); ?>
                  </span>
                  <h4 style="margin: 0.25rem 0 0.2rem 0; color: var(--color-navy); font-size: 1.05rem; font-weight: 700;">
                    <?php echo h($v['title']); ?>
                  </h4>
                  <span style="font-size: 0.8rem; color: var(--color-muted);"><?php echo h($v['video_url']); ?></span>
                </div>
              </div>
              <form method="POST" onsubmit="return confirm('Delete video?');" style="margin: 0;">
                <input type="hidden" name="action" value="delete_video">
                <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
                <button type="submit" style="background: #FDE8E8; color: #9B1C1C; border: 1px solid #F98080; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; cursor: pointer;">
                  Delete
                </button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Add Video Form -->
      <div style="background: #FFFFFF; border-radius: var(--radius-lg); border: 1.5px solid rgba(6, 43, 99, 0.16); padding: 2rem; box-shadow: var(--shadow-sm);">
        <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-top: 0; margin-bottom: 1.25rem;">
          Add Classroom Video
        </h3>
        <form method="POST">
          <input type="hidden" name="action" value="add_video">
          
          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Video Title *</label>
            <input type="text" name="title" required placeholder="e.g. Live Coding Cohort Session" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Category Badge Label *</label>
            <input type="text" name="badge" required value="📹 Live Class" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Filter Key *</label>
            <select name="category_key" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; background: #fff;">
              <option value="live">Live Class</option>
              <option value="activity">Student Activity</option>
              <option value="cocurricular">Co-Curricular</option>
              <option value="performance">Performances</option>
              <option value="experience">School Experience</option>
            </select>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
            <div>
              <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Category</label>
              <input type="text" name="category" value="Live Class" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
            </div>
            <div>
              <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Duration</label>
              <input type="text" name="duration" value="3:30 mins" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
            </div>
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Video URL (mp4 or stream) *</label>
            <input type="text" name="video_url" required value="/assets/images/01_Collaborative_Project_Learning.mp4" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Thumbnail Image URL *</label>
            <input type="text" name="thumbnail" required value="/assets/images/Students learning in classroom.png" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem;">
          </div>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); margin-bottom: 0.3rem;">Description</label>
            <textarea name="description" rows="3" placeholder="Brief description of the session..." style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 0.9rem; font-family: inherit;"></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--color-navy); color: #fff; padding: 0.75rem; border: none; font-weight: 700; border-radius: var(--radius-sm); cursor: pointer;">
            Add Video &rarr;
          </button>
        </form>
      </div>
    </div>
  <?php endif; ?>

</div>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
