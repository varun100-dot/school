<?php
// Zuvio Global School - Admin Beyond CMS Manager (Unified 2-Column Section Manager)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$selected_sec = $_GET['sec'] ?? 'hero';

// Persistent CMS Storage (MySQL database with session fallback)
$db_saved = get_json_setting('cms_beyond', []);
if (!isset($_SESSION['mock_beyond_cms']) || !empty($db_saved)) {
    $_SESSION['mock_beyond_cms'] = !empty($db_saved) ? $db_saved : [];
}
$beyond_cms = &$_SESSION['mock_beyond_cms'];

// 1. Defaults for Hero
if (!isset($beyond_cms['hero'])) {
    $beyond_cms['hero'] = [
        'is_active' => 1,
        'tag' => 'ZUVIO BEYOND',
        'subtitle' => 'LEARNING BEYOND CLASSROOMS',
        'title' => 'Discover. Create. Grow Beyond.',
        'desc' => 'A vibrant enrichment space for future-ready skills, creative expression and meaningful interests.',
        'grades' => 'NURSERY – GRADE 8'
    ];
}

// 2. Defaults for Co-curricular Clubs
if (!isset($beyond_cms['cocurricular'])) {
    $beyond_cms['cocurricular'] = [
        'is_active' => 1,
        'title' => 'Global Clubs & Co-Curricular Enrichment',
        'subtitle' => 'Nurturing holistic development, creative expression, and intellectual curiosity through diverse live clubs.'
    ];
}
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

// 3. Defaults for Hybrid Campus
if (!isset($beyond_cms['hybrid'])) {
    $beyond_cms['hybrid'] = [
        'is_active' => 1,
        'title' => 'Hybrid Campus & Practical Hands-on Learning',
        'subtitle' => 'Online intellectual depth seamlessly combined with physical kits, regional field meets, and sensory experiments.'
    ];
}
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

// 4. Defaults for Student Achievers
if (!isset($beyond_cms['achievers'])) {
    $beyond_cms['achievers'] = [
        'is_active' => 1,
        'title' => 'Student Achievers & Star Performers',
        'subtitle' => 'Celebrating excellence across competitive sports, international olympiads, visual arts, and leadership.'
    ];
}
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

// 5. Defaults for Gallery
if (!isset($beyond_cms['gallery_meta'])) {
    $beyond_cms['gallery_meta'] = [
        'is_active' => 1,
        'title' => 'Photo & Activity Gallery',
        'subtitle' => 'Glimpses into our live interactive classrooms, hands-on maker activities, and global student community.'
    ];
}
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

// 6. Defaults for Virtual Classroom
if (!isset($beyond_cms['classroom_meta'])) {
    $beyond_cms['classroom_meta'] = [
        'is_active' => 1,
        'title' => 'Inside the Virtual Classroom',
        'subtitle' => 'Watch authentic class clips and project demonstrations showing active participation, small cohorts, and real learning in action.'
    ];
}
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

// 6 Beyond Sections
$sections_nav = [
    'hero'         => ['num' => 1, 'name' => 'Beyond Hero & Overview', 'icon' => '🌟'],
    'cocurricular' => ['num' => 2, 'name' => 'Co-Curricular & Global Clubs', 'icon' => '🎭'],
    'hybrid'       => ['num' => 3, 'name' => 'Hybrid Campus & Science Kits', 'icon' => '🔬'],
    'achievers'    => ['num' => 4, 'name' => 'Student Achievers & Stars', 'icon' => '🏆'],
    'gallery'      => ['num' => 5, 'name' => 'Photo & Activity Gallery', 'icon' => '📸'],
    'classroom'    => ['num' => 6, 'name' => 'Inside Virtual Classroom', 'icon' => '💻']
];

if (!array_key_exists($selected_sec, $sections_nav)) {
    $selected_sec = 'hero';
}

function redirect_and_save_beyond($sec, $msg) {
    global $beyond_cms;
    set_json_setting('cms_beyond', $beyond_cms, 'Beyond CMS Content');
    header("Location: /admin/beyond-cms.php?sec=" . urlencode($sec) . "&msg=" . urlencode($msg));
    exit;
}

// Handle Form Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        $target_sec = $_POST['section_key'] ?? $selected_sec;

        // Apply Section Removal / Restoration / Visibility
        $storage_key = ($target_sec === 'gallery') ? 'gallery_meta' : (($target_sec === 'classroom') ? 'classroom_meta' : $target_sec);
        if (!isset($beyond_cms[$storage_key])) {
            $beyond_cms[$storage_key] = [];
        }

        $sec_action = $_POST['sec_action'] ?? '';
        if ($sec_action === 'remove') {
            $beyond_cms[$storage_key]['is_removed'] = 1;
            $beyond_cms[$storage_key]['is_active'] = 0;
        } elseif ($sec_action === 'restore') {
            $beyond_cms[$storage_key]['is_removed'] = 0;
            $beyond_cms[$storage_key]['is_active'] = 1;
        } else {
            $beyond_cms[$storage_key]['is_active'] = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;
            if ($beyond_cms[$storage_key]['is_active'] == 1) {
                $beyond_cms[$storage_key]['is_removed'] = 0;
            }
        }

        // Section 1: Hero
        if ($target_sec === 'hero') {
            $beyond_cms['hero']['tag'] = trim($_POST['tag'] ?? 'ZUVIO BEYOND');
            $beyond_cms['hero']['subtitle'] = trim($_POST['subtitle'] ?? 'LEARNING BEYOND CLASSROOMS');
            $beyond_cms['hero']['title'] = trim($_POST['title'] ?? 'Discover. Create. Grow Beyond.');
            $beyond_cms['hero']['desc'] = trim($_POST['desc'] ?? '');
            $beyond_cms['hero']['grades'] = trim($_POST['grades'] ?? 'NURSERY – GRADE 8');
            redirect_and_save_beyond('hero', 'saved');
        }

        // Section 2: Co-curricular
        if ($target_sec === 'cocurricular') {
            $beyond_cms['cocurricular']['title'] = trim($_POST['title'] ?? '');
            $beyond_cms['cocurricular']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if ($action === 'add_club') {
                $title = trim($_POST['new_title'] ?? '');
                if ($title) {
                    $beyond_cms['cocurricular_clubs'][] = [
                        'id' => time(),
                        'title' => $title,
                        'stage' => trim($_POST['new_stage'] ?? 'All Stages'),
                        'stage_key' => trim($_POST['new_stage_key'] ?? 'all'),
                        'desc' => trim($_POST['new_desc'] ?? ''),
                        'icon' => trim($_POST['new_icon'] ?? '🌟'),
                        'schedule' => trim($_POST['new_schedule'] ?? 'Weekly session'),
                        'sort_order' => count($beyond_cms['cocurricular_clubs']) + 1,
                        'is_published' => 1
                    ];
                }
            } elseif ($action === 'delete_club') {
                $id = (int)($_POST['item_id'] ?? 0);
                $beyond_cms['cocurricular_clubs'] = array_values(array_filter($beyond_cms['cocurricular_clubs'], fn($c) => ($c['id'] ?? 0) != $id));
            }
            redirect_and_save_beyond('cocurricular', 'saved');
        }

        // Section 3: Hybrid Campus
        if ($target_sec === 'hybrid') {
            $beyond_cms['hybrid']['title'] = trim($_POST['title'] ?? '');
            $beyond_cms['hybrid']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if ($action === 'add_hybrid') {
                $title = trim($_POST['new_title'] ?? '');
                if ($title) {
                    $beyond_cms['hybrid_campus'][] = [
                        'id' => time(),
                        'title' => $title,
                        'desc' => trim($_POST['new_desc'] ?? ''),
                        'icon' => trim($_POST['new_icon'] ?? '🔬')
                    ];
                }
            } elseif ($action === 'delete_hybrid') {
                $id = (int)($_POST['item_id'] ?? 0);
                $beyond_cms['hybrid_campus'] = array_values(array_filter($beyond_cms['hybrid_campus'], fn($h) => ($h['id'] ?? 0) != $id));
            }
            redirect_and_save_beyond('hybrid', 'saved');
        }

        // Section 4: Achievers
        if ($target_sec === 'achievers') {
            $beyond_cms['achievers']['title'] = trim($_POST['title'] ?? '');
            $beyond_cms['achievers']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if ($action === 'add_achiever') {
                $name = trim($_POST['new_name'] ?? '');
                if ($name) {
                    $beyond_cms['student_achievers'][] = [
                        'id' => time(),
                        'name' => $name,
                        'category' => trim($_POST['new_category'] ?? 'Extracurricular'),
                        'category_key' => trim($_POST['new_category_key'] ?? 'extracurricular'),
                        'badge' => trim($_POST['new_badge'] ?? '🌟 Star Kid'),
                        'description' => trim($_POST['new_desc'] ?? ''),
                        'image' => trim($_POST['new_image'] ?? '/assets/images/Profile_Images/Student_1.png'),
                        'sort_order' => count($beyond_cms['student_achievers']) + 1,
                        'is_published' => 1
                    ];
                }
            } elseif ($action === 'delete_achiever') {
                $id = (int)($_POST['item_id'] ?? 0);
                $beyond_cms['student_achievers'] = array_values(array_filter($beyond_cms['student_achievers'], fn($a) => ($a['id'] ?? 0) != $id));
            }
            redirect_and_save_beyond('achievers', 'saved');
        }

        // Section 5: Gallery
        if ($target_sec === 'gallery') {
            $beyond_cms['gallery_meta']['title'] = trim($_POST['title'] ?? '');
            $beyond_cms['gallery_meta']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if ($action === 'add_gallery') {
                $title = trim($_POST['new_title'] ?? '');
                if ($title) {
                    $beyond_cms['gallery'][] = [
                        'id' => time(),
                        'title' => $title,
                        'category' => trim($_POST['new_category'] ?? 'Live Classes'),
                        'category_key' => trim($_POST['new_category_key'] ?? 'live'),
                        'caption' => trim($_POST['new_caption'] ?? ''),
                        'image' => trim($_POST['new_image'] ?? '/assets/images/Students learning in classroom.png'),
                        'sort_order' => count($beyond_cms['gallery']) + 1,
                        'is_published' => 1
                    ];
                }
            } elseif ($action === 'delete_gallery') {
                $id = (int)($_POST['item_id'] ?? 0);
                $beyond_cms['gallery'] = array_values(array_filter($beyond_cms['gallery'], fn($g) => ($g['id'] ?? 0) != $id));
            }
            redirect_and_save_beyond('gallery', 'saved');
        }

        // Section 6: Virtual Classroom
        if ($target_sec === 'classroom') {
            $beyond_cms['classroom_meta']['title'] = trim($_POST['title'] ?? '');
            $beyond_cms['classroom_meta']['subtitle'] = trim($_POST['subtitle'] ?? '');

            if ($action === 'add_video') {
                $title = trim($_POST['new_title'] ?? '');
                if ($title) {
                    $beyond_cms['virtual_classroom'][] = [
                        'id' => time(),
                        'title' => $title,
                        'category' => trim($_POST['new_category'] ?? 'Live Class'),
                        'category_key' => trim($_POST['new_category_key'] ?? 'live'),
                        'badge' => trim($_POST['new_badge'] ?? '📹 Live Class'),
                        'duration' => trim($_POST['new_duration'] ?? '3:00 mins'),
                        'description' => trim($_POST['new_desc'] ?? ''),
                        'thumbnail' => trim($_POST['new_thumbnail'] ?? '/assets/images/Students learning in classroom.png'),
                        'video_url' => trim($_POST['new_video_url'] ?? '/assets/images/01_Collaborative_Project_Learning.mp4'),
                        'sort_order' => count($beyond_cms['virtual_classroom']) + 1,
                        'is_published' => 1
                    ];
                }
            } elseif ($action === 'delete_video') {
                $id = (int)($_POST['item_id'] ?? 0);
                $beyond_cms['virtual_classroom'] = array_values(array_filter($beyond_cms['virtual_classroom'], fn($v) => ($v['id'] ?? 0) != $id));
            }
            redirect_and_save_beyond('classroom', 'saved');
        }
    }
}

$page_slug = 'admin-beyond-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      Beyond Academics Section Manager
    </h1>
    <p style="color: var(--color-muted); font-size: 0.85rem;">
      Unified 2-column management for Beyond sections: Co-Curricular Clubs, Hybrid Campus, Achievers, Gallery, and Virtual Classroom.
    </p>
  </div>
  <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <a href="/beyond" target="_blank" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem;">
      <span>👁️</span> Preview Live Page &nearr;
    </a>
    <a href="/admin/beyond-cms.php?sec=<?php echo urlencode($selected_sec); ?>" class="btn btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; background: #FFFFFF;">
      ↻ Reload
    </a>
  </div>
</div>

<?php if ($msg === 'saved'): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.85rem 1.25rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.9rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <div><strong>✓ Saved & Synchronized!</strong> Section updates are live on the website.</div>
    <span style="font-size: 0.8rem; color: var(--color-muted);"><?php echo date('H:i:s'); ?></span>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.85rem 1.25rem; margin-bottom:1.5rem; color:#9b1c1c; font-size:0.9rem;">
    <strong>Error:</strong> <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- 2-Column Section Manager Layout -->
<div style="display: grid; grid-template-columns: 310px 1fr; gap: 1.75rem; align-items: start;">

  <!-- Left Sidebar: Sticky Section Sequence -->
  <div style="position: sticky; top: 1.5rem;">
    <div class="card" style="padding: 0; overflow: hidden; border: 1.5px solid rgba(6, 43, 99, 0.12); box-shadow: var(--shadow-sm);">
      <div style="background: var(--color-navy); color: #FFFFFF; padding: 1rem 1.25rem; font-weight: 700; font-size: 0.9rem; display: flex; justify-content: space-between; align-items: center;">
        <span>Beyond Sections</span>
        <span style="font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 0.2rem 0.55rem; border-radius: 12px; font-weight: 600;">
          <?php echo count($sections_nav); ?> Total
        </span>
      </div>

      <div style="divide-y: 1px solid var(--color-border); max-height: calc(100vh - 180px); overflow-y: auto;">
        <?php foreach ($sections_nav as $skey => $sdata): 
          $check_key = ($skey === 'gallery') ? 'gallery_meta' : (($skey === 'classroom') ? 'classroom_meta' : $skey);
          $s_active = !isset($beyond_cms[$check_key]['is_active']) || !empty($beyond_cms[$check_key]['is_active']);
          $s_removed = !empty($beyond_cms[$check_key]['is_removed']);
          $is_current = ($selected_sec === $skey);
        ?>
          <a href="/admin/beyond-cms.php?sec=<?php echo urlencode($skey); ?>" 
             style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1.1rem; text-decoration: none; border-bottom: 1px solid var(--color-border); background: <?php echo $is_current ? 'var(--pastel-blue)' : '#FFFFFF'; ?>; border-left: 4px solid <?php echo $is_current ? 'var(--color-navy)' : 'transparent'; ?>; transition: all 0.15s ease;">
            <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
              <span style="font-size: 0.75rem; font-weight: 800; color: var(--color-muted); width: 18px;">
                <?php echo str_pad($sdata['num'], 2, '0', STR_PAD_LEFT); ?>
              </span>
              <span style="font-size: 1.1rem;"><?php echo $sdata['icon']; ?></span>
              <span style="font-size: 0.85rem; font-weight: <?php echo $is_current ? '700' : '500'; ?>; color: var(--color-navy); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <?php echo h($sdata['name']); ?>
              </span>
            </div>
            <div>
              <?php if ($s_removed): ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #dc2626; background: #fee2e2; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  REMOVED
                </span>
              <?php elseif (!$s_active): ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #6b7280; background: #f3f4f6; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  OFF
                </span>
              <?php else: ?>
                <span style="font-size: 0.7rem; font-weight: 700; color: #047857; background: #d1fae5; padding: 0.15rem 0.45rem; border-radius: 4px;">
                  ON
                </span>
              <?php endif; ?>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Right Main Section Editor -->
  <div>
    <?php 
      $check_key = ($selected_sec === 'gallery') ? 'gallery_meta' : (($selected_sec === 'classroom') ? 'classroom_meta' : $selected_sec);
      $current_sec_data = $beyond_cms[$check_key] ?? [];
      $is_sec_removed = !empty($current_sec_data['is_removed']);
      $is_sec_active = !isset($current_sec_data['is_active']) || !empty($current_sec_data['is_active']);
      $sec_meta = $sections_nav[$selected_sec];
    ?>

    <form method="POST" id="sectionForm">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="section_key" value="<?php echo h($selected_sec); ?>">
      <input type="hidden" name="sec_action" id="sec_action_input" value="">
      <input type="hidden" name="action" id="form_action_input" value="save_section">
      <input type="hidden" name="item_id" id="item_id_input" value="">

      <div class="card" style="padding: 2rem; border: 1.5px solid rgba(6, 43, 99, 0.15); box-shadow: var(--shadow-sm); margin-bottom: 2rem;">

        <!-- Section Editor Header with Live Controls -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 1.25rem; margin-bottom: 1.5rem; border-bottom: 2px solid var(--color-border); flex-wrap: wrap; gap: 1rem;">
          <div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 0.25rem;">
              Section <?php echo $sec_meta['num']; ?> of <?php echo count($sections_nav); ?>
            </div>
            <h2 style="font-size: 1.4rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
              <span><?php echo $sec_meta['icon']; ?></span>
              <span><?php echo h($sec_meta['name']); ?></span>
            </h2>
          </div>

          <div style="display: flex; align-items: center; gap: 1rem;">
            <!-- Visible on Page Toggle -->
            <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: var(--color-navy); background: var(--color-surface-warm); padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <input type="checkbox" name="is_active" value="1" <?php echo ($is_sec_active && !$is_sec_removed) ? 'checked' : ''; ?> <?php echo $is_sec_removed ? 'disabled' : ''; ?> style="width: 16px; height: 16px; cursor: pointer;">
              <span>Visible on Page</span>
            </label>

            <!-- Remove / Restore Button -->
            <?php if ($is_sec_removed): ?>
              <button type="button" onclick="setSectionAction('restore')" class="btn btn-outline" style="border-color: #047857; color: #047857; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>↩️</span> Restore Section
              </button>
            <?php else: ?>
              <button type="button" onclick="setSectionAction('remove')" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.4rem 0.85rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>🗑️</span> Remove Section
              </button>
            <?php endif; ?>
          </div>
        </div>

        <!-- Pending Removal Alert Box -->
        <div id="pendingRemovalBox" style="display: none; background: #fee2e2; border-left: 4px solid #dc2626; padding: 1rem 1.25rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; color: #991b1b; font-size: 0.9rem;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              <strong>⚠️ PENDING REMOVAL:</strong> This section will be removed from the Beyond page when you click "Save Changes" below.
            </div>
            <button type="button" onclick="cancelSectionAction()" style="background: transparent; border: 1px solid #dc2626; color: #dc2626; padding: 0.25rem 0.65rem; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.8rem;">
              Cancel Removal
            </button>
          </div>
        </div>

        <!-- Section 1: Hero -->
        <?php if ($selected_sec === 'hero'): 
          $h = $beyond_cms['hero'] ?? [];
        ?>
          <div style="display: grid; gap: 1.25rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Tag / Kicker
                </label>
                <input type="text" name="tag" value="<?php echo h($h['tag'] ?? 'ZUVIO BEYOND'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
              <div>
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                  Subtitle Eyebrow
                </label>
                <input type="text" name="subtitle" value="<?php echo h($h['subtitle'] ?? 'LEARNING BEYOND CLASSROOMS'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
              </div>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Hero Main Title
              </label>
              <input type="text" name="title" value="<?php echo h($h['title'] ?? 'Discover. Create. Grow Beyond.'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Hero Description
              </label>
              <textarea name="desc" rows="3" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($h['desc'] ?? ''); ?></textarea>
            </div>

            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Grades Banner
              </label>
              <input type="text" name="grades" value="<?php echo h($h['grades'] ?? 'NURSERY – GRADE 8'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
          </div>

        <!-- Section 2: Co-Curricular & Global Clubs -->
        <?php elseif ($selected_sec === 'cocurricular'): 
          $cc = $beyond_cms['cocurricular'] ?? [];
          $clubs = $beyond_cms['cocurricular_clubs'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($cc['title'] ?? 'Global Clubs & Co-Curricular Enrichment'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($cc['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Existing Clubs -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Active Clubs (<?php echo count($clubs); ?> Clubs)
              </label>
              <div style="display: grid; gap: 0.75rem;">
                <?php foreach ($clubs as $c): ?>
                  <div style="display: flex; justify-content: space-between; align-items: center; background: var(--color-surface-warm); padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <span style="font-size: 1.4rem;"><?php echo $c['icon'] ?? '🌟'; ?></span>
                      <div>
                        <strong><?php echo h($c['title'] ?? ''); ?></strong>
                        <span style="font-size: 0.75rem; color: var(--color-muted); margin-left: 0.5rem;">(<?php echo h($c['stage'] ?? ''); ?>)</span>
                        <div style="font-size: 0.8rem; color: var(--color-text); margin-top: 0.2rem;"><?php echo h($c['schedule'] ?? ''); ?></div>
                      </div>
                    </div>
                    <div>
                      <button type="button" onclick="deleteItem('delete_club', <?php echo (int)($c['id'] ?? 0); ?>)" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                        Delete
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Add Club Block -->
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Add New Club</h4>
              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_title" placeholder="Club Name (e.g. Robotics Club)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_stage" placeholder="Stage (e.g. Grades 3-8)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_icon" placeholder="Emoji Icon (e.g. 🤖)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_desc" placeholder="Brief club description..." class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_schedule" placeholder="Schedule (e.g. Weekly)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <button type="button" onclick="submitItemAction('add_club')" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; padding: 0.4rem 1rem; font-size: 0.85rem;">
                + Add This Club
              </button>
            </div>
          </div>

        <!-- Section 3: Hybrid Campus -->
        <?php elseif ($selected_sec === 'hybrid'): 
          $hy = $beyond_cms['hybrid'] ?? [];
          $kits = $beyond_cms['hybrid_campus'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($hy['title'] ?? 'Hybrid Campus & Practical Hands-on Learning'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($hy['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Existing Hybrid Features -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Hybrid Features & Kits
              </label>
              <div style="display: grid; gap: 0.75rem;">
                <?php foreach ($kits as $k): ?>
                  <div style="display: flex; justify-content: space-between; align-items: center; background: var(--color-surface-warm); padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <span style="font-size: 1.4rem;"><?php echo $k['icon'] ?? '🔬'; ?></span>
                      <div>
                        <strong><?php echo h($k['title'] ?? ''); ?></strong>
                        <div style="font-size: 0.82rem; color: var(--color-muted);"><?php echo h($k['desc'] ?? ''); ?></div>
                      </div>
                    </div>
                    <div>
                      <button type="button" onclick="deleteItem('delete_hybrid', <?php echo (int)($k['id'] ?? 0); ?>)" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                        Delete
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Add Hybrid Feature -->
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Add Hybrid Feature</h4>
              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_title" placeholder="Feature Title" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_icon" placeholder="Emoji (e.g. ⚙️)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <textarea name="new_desc" rows="2" placeholder="Description of practical kit or activity..." class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px; margin-bottom: 0.75rem;"></textarea>
              <button type="button" onclick="submitItemAction('add_hybrid')" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; padding: 0.4rem 1rem; font-size: 0.85rem;">
                + Add Hybrid Feature
              </button>
            </div>
          </div>

        <!-- Section 4: Student Achievers -->
        <?php elseif ($selected_sec === 'achievers'): 
          $ach = $beyond_cms['achievers'] ?? [];
          $students = $beyond_cms['student_achievers'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($ach['title'] ?? 'Student Achievers & Star Performers'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($ach['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Existing Achievers -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Recognized Achievers (<?php echo count($students); ?> Students)
              </label>
              <div style="display: grid; gap: 0.75rem;">
                <?php foreach ($students as $st): ?>
                  <div style="display: flex; justify-content: space-between; align-items: center; background: var(--color-surface-warm); padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <img src="<?php echo h($st['image'] ?? '/assets/images/Profile_Images/Student_1.png'); ?>" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                      <div>
                        <strong><?php echo h($st['name'] ?? ''); ?></strong>
                        <span style="font-size: 0.75rem; background: var(--pastel-yellow); padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem; color: var(--color-navy);"><?php echo h($st['badge'] ?? ''); ?></span>
                        <div style="font-size: 0.8rem; color: var(--color-muted);"><?php echo h($st['category'] ?? ''); ?></div>
                      </div>
                    </div>
                    <div>
                      <button type="button" onclick="deleteItem('delete_achiever', <?php echo (int)($st['id'] ?? 0); ?>)" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                        Delete
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Add Achiever Block -->
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Add Student Achiever</h4>
              <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_name" placeholder="Student Name" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_badge" placeholder="Badge (e.g. 🌟 Star Kid)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_category" placeholder="Category (e.g. Creative Arts)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <textarea name="new_desc" rows="2" placeholder="Achievement summary..." class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px; margin-bottom: 0.75rem;"></textarea>
              <button type="button" onclick="submitItemAction('add_achiever')" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; padding: 0.4rem 1rem; font-size: 0.85rem;">
                + Add Achiever
              </button>
            </div>
          </div>

        <!-- Section 5: Photo Gallery -->
        <?php elseif ($selected_sec === 'gallery'): 
          $gm = $beyond_cms['gallery_meta'] ?? [];
          $photos = $beyond_cms['gallery'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($gm['title'] ?? 'Photo & Activity Gallery'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($gm['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Existing Gallery -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Gallery Items (<?php echo count($photos); ?> Photos)
              </label>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <?php foreach ($photos as $g): ?>
                  <div style="background: var(--color-surface-warm); padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <img src="<?php echo h($g['image'] ?? ''); ?>" alt="" style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 0.5rem;">
                    <strong><?php echo h($g['title'] ?? ''); ?></strong>
                    <div style="font-size: 0.8rem; color: var(--color-muted); margin-bottom: 0.5rem;"><?php echo h($g['category'] ?? ''); ?></div>
                    <button type="button" onclick="deleteItem('delete_gallery', <?php echo (int)($g['id'] ?? 0); ?>)" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.25rem 0.6rem; font-size: 0.75rem; width: 100%;">
                      Delete
                    </button>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Add Gallery Block -->
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Add Gallery Photo</h4>
              <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_title" placeholder="Photo Title" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_category" placeholder="Category (e.g. Live Classes)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <input type="text" name="new_image" placeholder="Image URL (e.g. /assets/images/Students learning in classroom.png)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px; margin-bottom: 0.75rem;">
              <input type="text" name="new_caption" placeholder="Caption / description..." class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px; margin-bottom: 0.75rem;">
              <button type="button" onclick="submitItemAction('add_gallery')" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; padding: 0.4rem 1rem; font-size: 0.85rem;">
                + Add Photo
              </button>
            </div>
          </div>

        <!-- Section 6: Virtual Classroom -->
        <?php elseif ($selected_sec === 'classroom'): 
          $cm = $beyond_cms['classroom_meta'] ?? [];
          $videos = $beyond_cms['virtual_classroom'] ?? [];
        ?>
          <div style="display: grid; gap: 1.5rem;">
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Title
              </label>
              <input type="text" name="title" value="<?php echo h($cm['title'] ?? 'Inside the Virtual Classroom'); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--color-navy); margin-bottom: 0.35rem; display: block;">
                Section Subtitle
              </label>
              <input type="text" name="subtitle" value="<?php echo h($cm['subtitle'] ?? ''); ?>" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
            </div>

            <!-- Existing Videos -->
            <div>
              <label class="form-label" style="font-weight: 700; font-size: 0.95rem; color: var(--color-navy); margin-bottom: 0.75rem; display: block;">
                Classroom Videos (<?php echo count($videos); ?> Clips)
              </label>
              <div style="display: grid; gap: 0.75rem;">
                <?php foreach ($videos as $v): ?>
                  <div style="display: flex; justify-content: space-between; align-items: center; background: var(--color-surface-warm); padding: 0.85rem 1.15rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                    <div>
                      <strong><?php echo h($v['title'] ?? ''); ?></strong>
                      <span style="font-size: 0.75rem; background: var(--pastel-yellow); padding: 0.15rem 0.45rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem; color: var(--color-navy);"><?php echo h($v['duration'] ?? ''); ?></span>
                      <div style="font-size: 0.8rem; color: var(--color-muted); margin-top: 0.2rem;"><?php echo h($v['description'] ?? ''); ?></div>
                    </div>
                    <div>
                      <button type="button" onclick="deleteItem('delete_video', <?php echo (int)($v['id'] ?? 0); ?>)" class="btn btn-outline" style="border-color: #dc2626; color: #dc2626; padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                        Delete
                      </button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Add Video Block -->
            <div style="background: var(--pastel-blue); padding: 1.25rem; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
              <h4 style="font-size: 1rem; color: var(--color-navy); margin-bottom: 0.75rem;">Add Classroom Video</h4>
              <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                <input type="text" name="new_title" placeholder="Video Title" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
                <input type="text" name="new_duration" placeholder="Duration (e.g. 3:45 mins)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
              </div>
              <input type="text" name="new_video_url" placeholder="Video File URL (e.g. /assets/images/01_Collaborative_Project_Learning.mp4)" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px; margin-bottom: 0.75rem;">
              <textarea name="new_desc" rows="2" placeholder="Video description..." class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px; margin-bottom: 0.75rem;"></textarea>
              <button type="button" onclick="submitItemAction('add_video')" class="btn btn-outline" style="border-color: var(--color-navy); color: var(--color-navy); font-weight: 700; padding: 0.4rem 1rem; font-size: 0.85rem;">
                + Add Video
              </button>
            </div>
          </div>
        <?php endif; ?>

        <!-- Bottom Actions Bar -->
        <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
          <a href="/admin/beyond-cms.php?sec=<?php echo urlencode($selected_sec); ?>" class="btn btn-outline" style="padding: 0.65rem 1.25rem; font-size: 0.9rem;">
            ↻ Reset / Reload
          </a>

          <button type="submit" id="saveSubmitBtn" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #FFFFFF; font-weight: 700; padding: 0.65rem 1.75rem; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
            <span>💾</span> Save <?php echo h($sec_meta['name']); ?>
          </button>
        </div>

      </div>
    </form>
  </div>

</div>

<script>
function setSectionAction(action) {
  const actionInput = document.getElementById('sec_action_input');
  const pendingBox = document.getElementById('pendingRemovalBox');
  const saveBtn = document.getElementById('saveSubmitBtn');

  if (action === 'remove') {
    actionInput.value = 'remove';
    if (pendingBox) pendingBox.style.display = 'block';
    if (saveBtn) {
      saveBtn.style.backgroundColor = '#dc2626';
      saveBtn.style.borderColor = '#dc2626';
      saveBtn.innerHTML = '<span>⚠️</span> Confirm Removal &amp; Save';
    }
  } else if (action === 'restore') {
    actionInput.value = 'restore';
    document.getElementById('sectionForm').submit();
  }
}

function cancelSectionAction() {
  const actionInput = document.getElementById('sec_action_input');
  const pendingBox = document.getElementById('pendingRemovalBox');
  const saveBtn = document.getElementById('saveSubmitBtn');

  actionInput.value = '';
  if (pendingBox) pendingBox.style.display = 'none';
  if (saveBtn) {
    saveBtn.style.backgroundColor = 'var(--color-navy)';
    saveBtn.style.borderColor = 'var(--color-navy)';
    saveBtn.innerHTML = '<span>💾</span> Save <?php echo addslashes(h($sec_meta['name'])); ?>';
  }
}

function submitItemAction(actionName) {
  document.getElementById('form_action_input').value = actionName;
  document.getElementById('sectionForm').submit();
}

function deleteItem(actionName, id) {
  if (confirm('Are you sure you want to delete this item?')) {
    document.getElementById('form_action_input').value = actionName;
    document.getElementById('item_id_input').value = id;
    document.getElementById('sectionForm').submit();
  }
}
</script>

<?php
include_once dirname(__FILE__) . '/footer.php';
