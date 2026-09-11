<?php
// Zuvio Global School - Admin About Us CMS Manager
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$msg = $_GET['msg'] ?? '';
$error = '';
$tab = $_GET['tab'] ?? 'story';

// Ensure $_SESSION['mock_about_cms'] is fully structured
if (!isset($_SESSION['mock_about_cms'])) {
    $_SESSION['mock_about_cms'] = [];
}
$cms = &$_SESSION['mock_about_cms'];

// 1. Defaults if empty
if (!isset($cms['story'])) {
    $cms['story'] = [
        'title' => 'Learning Without Boundaries, Growing With Purpose',
        'subtitle' => 'About Zuvio',
        'content' => "Zuvio began with a simple observation: too many children are asked to fit into a system, rather than the system being designed to fit the child.\n\nTraditional schooling often requires conformity over curiosity, rigid schedules over natural rhythms, and a one-size-fits-all approach that leaves many students underserved — whether they need more time to master a concept, more room to run ahead, or simply an environment where they feel safe and understood.\n\nZuvio Global School was founded to offer an alternative — not an alternative that compromises on quality, but one that raises the bar for what education can be.\n\nWe bring together a structured, curriculum-aligned programme, caring teachers, and thoughtful technology to create a flexible, personalised learning experience your child can access from anywhere in the world.",
        'image' => '/assets/images/about_us_hero.jpg'
    ];
}

if (!isset($cms['vision_mission'])) {
    $cms['vision_mission'] = [
        'vision' => 'To redefine the future of education by creating a dynamic, borderless learning environment where students from every corner of the world can thrive academically, think critically, and evolve into compassionate, future-ready global leaders.',
        'mission' => 'Our mission is to revolutionize education through a cutting-edge online learning platform that integrates futuristic teaching methods, personalized pathways, and holistic development to unlock the unique potential of every child.'
    ];
}

if (!isset($cms['values'])) {
    $cms['values'] = [
        ['id' => 1, 'title' => 'Child at the Centre', 'desc' => 'Every child is an individual, not a cohort. Their strengths, pace, and interests shape the journey.', 'icon' => '🎯', 'sort_order' => 1, 'is_published' => 1],
        ['id' => 2, 'title' => 'Inclusion by Design', 'desc' => 'An environment built from day one to welcome every kind of mind — neurotypical, neurodivergent, gifted, or simply different.', 'icon' => '🤝', 'sort_order' => 2, 'is_published' => 1],
        ['id' => 3, 'title' => 'Personalised Learning', 'desc' => 'Learning pathways that flex to fit the student, not rigid timetables that force students into a mould.', 'icon' => '🌱', 'sort_order' => 3, 'is_published' => 1],
        ['id' => 4, 'title' => 'Growth Not Just Marks', 'desc' => 'Academic achievement matters deeply, but so does confidence, critical thinking, emotional resilience, and character.', 'icon' => '📈', 'sort_order' => 4, 'is_published' => 1],
        ['id' => 5, 'title' => 'Beyond Academics', 'desc' => 'A complete school experience — clubs, sports, competitions, exhibitions, and real-world life skills.', 'icon' => '🎨', 'sort_order' => 5, 'is_published' => 1],
        ['id' => 6, 'title' => 'Learning Without Boundaries', 'desc' => 'Quality education that travels with the child. Accessible from anywhere in the world.', 'icon' => '🌍', 'sort_order' => 6, 'is_published' => 1]
    ];
}

if (!isset($cms['apart'])) {
    $cms['apart'] = [
        ['id' => 1, 'title' => 'Online but Deeply Human', 'desc' => 'Small interactive live classes, dedicated mentors, and real relationships — never pre-recorded video lectures.', 'tag' => 'Human Touch', 'sort_order' => 1, 'is_published' => 1],
        ['id' => 2, 'title' => 'Personalised by Default', 'desc' => 'Customised pace, targeted support, and pathways tailored to each child’s unique learning style and needs.', 'tag' => 'Tailored Pace', 'sort_order' => 2, 'is_published' => 1],
        ['id' => 3, 'title' => 'Inclusive by Design', 'desc' => 'Specialised support, SEN certified educators, and a culture where every learner belongs and flourishes.', 'tag' => 'Neuroinclusive', 'sort_order' => 3, 'is_published' => 1],
        ['id' => 4, 'title' => 'Flexible for Real Life', 'desc' => 'Timetables and structures that support families traveling, student athletes, artists, and homeschooling paths.', 'tag' => 'Anytime Anywhere', 'sort_order' => 4, 'is_published' => 1],
        ['id' => 5, 'title' => 'Beyond Academics', 'desc' => 'Holistic co-curricular programmes, leadership clubs, debate, coding, and sports integration through ISSO.', 'tag' => '360° Growth', 'sort_order' => 5, 'is_published' => 1],
        ['id' => 6, 'title' => 'Parents as Partners', 'desc' => 'Transparent progress tracking, regular open dialogues, and collaborative goal setting for student success.', 'tag' => 'Collaborative', 'sort_order' => 6, 'is_published' => 1]
    ];
}

if (!isset($cms['approach'])) {
    $cms['approach'] = [
        ['letter' => 'Z', 'title' => 'Zoomed-In Attention', 'desc' => 'Small cohorts, frequent individual check-ins, and dedicated teacher focus ensuring no child is overlooked.', 'sort_order' => 1, 'is_published' => 1],
        ['letter' => 'U', 'title' => 'Understand Every Learner', 'desc' => 'Diagnostic assessments that recognise cognitive strengths, emotional needs, and individual learning preferences.', 'sort_order' => 2, 'is_published' => 1],
        ['letter' => 'V', 'title' => 'Versatile Pathways', 'desc' => 'Flexible curriculum choices, customizable pacing, and elective enrichment tailored to future aspirations.', 'sort_order' => 3, 'is_published' => 1],
        ['letter' => 'I', 'title' => 'Inclusive by Design', 'desc' => 'Neurodivergent support, SEN-trained educators, and differentiated instruction welcoming all minds.', 'sort_order' => 4, 'is_published' => 1],
        ['letter' => 'O', 'title' => 'Opportunities Without Boundaries', 'desc' => 'Global student peers, international olympiads, and borderless learning accessible anywhere on Earth.', 'sort_order' => 5, 'is_published' => 1]
    ];
}

if (!isset($cms['audiences'])) {
    $cms['audiences'] = [
        ['id' => 1, 'title' => 'Flexible Learning Families', 'desc' => 'Families seeking flexible learning schedules that adapt seamlessly to family lifestyle, commitments, and relocation.', 'sort_order' => 1, 'is_published' => 1],
        ['id' => 2, 'title' => 'Homeschooling Families', 'desc' => 'Alternative-learning and homeschooling families looking for structured, recognized international curriculum accreditation.', 'sort_order' => 2, 'is_published' => 1],
        ['id' => 3, 'title' => 'Globally Mobile & Expats', 'desc' => 'Expat, diplomatic, and traveling families requiring continuous, uninterrupted schooling with recognized global credentials.', 'sort_order' => 3, 'is_published' => 1],
        ['id' => 4, 'title' => 'Young Athletes & Performers', 'desc' => 'Students pursuing competitive sports, fine arts, music, or performance careers needing rigorous yet adaptable academics.', 'sort_order' => 4, 'is_published' => 1],
        ['id' => 5, 'title' => 'Calm-Environment Learners', 'desc' => 'Children who flourish better in calm, distraction-free, supportive online settings free from traditional classroom anxiety.', 'sort_order' => 5, 'is_published' => 1],
        ['id' => 6, 'title' => 'Personalised Pace Seekers', 'desc' => 'Students who want to accelerate in areas of strength or take measured, dedicated time to master challenging concepts.', 'sort_order' => 6, 'is_published' => 1],
        ['id' => 7, 'title' => 'Future-Ready Seekers', 'desc' => 'Parents prioritising 21st-century critical thinking, ethical digital literacy, communication, and emotional resilience.', 'sort_order' => 7, 'is_published' => 1],
        ['id' => 8, 'title' => 'Neuroinclusive Needs', 'desc' => 'Children with ADHD, autism, or delayed learning who thrive with individualized attention, patience, and expert guidance.', 'sort_order' => 8, 'is_published' => 1]
    ];
}

if (!isset($cms['founder_message_data'])) {
    $cms['founder_message_data'] = [
        'title' => 'Learning Without Boundaries. Growing With Purpose.',
        'salutation' => 'Dear Parents, Students and Members of the Zuvio Community,',
        'paragraphs' => [
            "Education today must prepare children not only for examinations, but for a world that is constantly evolving.",
            "At Zuvio Global School, we believe that meaningful learning is not defined by the walls of a classroom. It is defined by curiosity, connection, opportunity and the confidence to explore beyond what is already known.",
            "Our vision is to create a 100% online, future-ready learning environment where every child has the opportunity to learn beyond geographical boundaries while receiving the guidance, structure and personal attention needed to thrive.",
            "At Zuvio, strong academics form the foundation, but learning goes much further. We encourage our students to question, think critically, communicate confidently, collaborate, create and apply their knowledge to real-world situations. Technology enables our classrooms, but teachers, relationships and meaningful human interaction remain at the heart of the learning experience.",
            "We recognise that every child is different. Their interests, abilities, pace and aspirations are unique. Our approach therefore aims to create a learning journey that gives students the flexibility to discover their strengths while developing the knowledge, skills and values required for the future.",
            "We also believe education is a partnership. Parents, educators and students must work together to create an environment in which children feel supported, inspired and empowered to take ownership of their learning.",
            "Zuvio Global School is not simply about bringing a traditional classroom online. We are reimagining how learning can happen when boundaries are removed and possibilities are expanded.",
            "Our aspiration is simple yet powerful: to nurture confident learners, independent thinkers, compassionate individuals and responsible global citizens who are prepared not just for the next grade, but for the world ahead.",
            "Welcome to Zuvio Global School — a global learning community where every child is encouraged to learn, explore, create and grow without boundaries."
        ],
        'signoff_name' => 'Founder',
        'signoff_org' => 'Zuvio Global School',
        'image' => '/assets/images/Profile_Images/Pragya_Professional_Profile.webp'
    ];
}

if (!isset($cms['awards'])) {
    $cms['awards'] = [
        ['id' => 1, 'title' => 'Best E-School of 2023', 'org' => 'Global Education Summit', 'desc' => 'Recognized for pioneering digital school infrastructure and interactive cohort pedagogy.', 'sort_order' => 1, 'is_published' => 1],
        ['id' => 2, 'title' => 'National School Award', 'org' => 'Education Excellence Forum', 'desc' => 'Awarded for exceptional commitment to student-centric online learning and curriculum rigor.', 'sort_order' => 2, 'is_published' => 1],
        ['id' => 3, 'title' => 'International Icon Awards 2025', 'org' => 'Global EdTech Leadership', 'desc' => 'Honored for transformative leadership in inclusive and neurodivergent-friendly education.', 'sort_order' => 3, 'is_published' => 1],
        ['id' => 4, 'title' => 'Featured in Global Media', 'org' => 'Education World & Top Portals', 'desc' => 'Celebrated across leading publications for breaking geographical barriers in K–12 education.', 'sort_order' => 4, 'is_published' => 1]
    ];
}

// POST Action Handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security check failed. Please submit again.';
    } else {
        $action = $_POST['action'] ?? 'save_story';

        // 1. Save Story & Vision
        if ($action === 'save_story') {
            $cms['story']['title'] = trim($_POST['story_title'] ?? '');
            $cms['story']['subtitle'] = trim($_POST['story_subtitle'] ?? '');
            $cms['story']['content'] = trim($_POST['story_content'] ?? '');
            $cms['story']['image'] = trim($_POST['story_image'] ?? '');
            $cms['vision_mission']['vision'] = trim($_POST['vision'] ?? '');
            $cms['vision_mission']['mission'] = trim($_POST['mission'] ?? '');
            header('Location: /admin/about-cms.php?tab=story&msg=saved');
            exit;
        }

        // 2. Values: Add / Edit / Delete / Toggle Publish / Reorder
        if ($action === 'add_value') {
            $new_id = time();
            $cms['values'][] = [
                'id' => $new_id,
                'title' => trim($_POST['title'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'icon' => trim($_POST['icon'] ?? '✨'),
                'sort_order' => count($cms['values']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/about-cms.php?tab=values&msg=added');
            exit;
        }

        if ($action === 'edit_value') {
            $id = (int)$_POST['id'];
            foreach ($cms['values'] as &$v) {
                if ($v['id'] === $id) {
                    $v['title'] = trim($_POST['title'] ?? '');
                    $v['desc'] = trim($_POST['desc'] ?? '');
                    $v['icon'] = trim($_POST['icon'] ?? '✨');
                    $v['sort_order'] = (int)($_POST['sort_order'] ?? $v['sort_order']);
                    break;
                }
            }
            usort($cms['values'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/about-cms.php?tab=values&msg=updated');
            exit;
        }

        if ($action === 'delete_value') {
            $id = (int)$_POST['id'];
            $cms['values'] = array_values(array_filter($cms['values'], fn($v) => $v['id'] !== $id));
            header('Location: /admin/about-cms.php?tab=values&msg=deleted');
            exit;
        }

        if ($action === 'toggle_publish_value') {
            $id = (int)$_POST['id'];
            foreach ($cms['values'] as &$v) {
                if ($v['id'] === $id) {
                    $v['is_published'] = empty($v['is_published']) ? 1 : 0;
                    break;
                }
            }
            header('Location: /admin/about-cms.php?tab=values&msg=status_updated');
            exit;
        }

        // 3. What Sets Us Apart: Add / Edit / Delete / Toggle / Reorder
        if ($action === 'add_apart') {
            $new_id = time();
            $cms['apart'][] = [
                'id' => $new_id,
                'tag' => trim($_POST['tag'] ?? 'Differentiator'),
                'title' => trim($_POST['title'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'sort_order' => count($cms['apart']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/about-cms.php?tab=apart&msg=added');
            exit;
        }

        if ($action === 'edit_apart') {
            $id = (int)$_POST['id'];
            foreach ($cms['apart'] as &$a) {
                if ($a['id'] === $id) {
                    $a['tag'] = trim($_POST['tag'] ?? '');
                    $a['title'] = trim($_POST['title'] ?? '');
                    $a['desc'] = trim($_POST['desc'] ?? '');
                    $a['sort_order'] = (int)($_POST['sort_order'] ?? $a['sort_order']);
                    break;
                }
            }
            usort($cms['apart'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/about-cms.php?tab=apart&msg=updated');
            exit;
        }

        if ($action === 'delete_apart') {
            $id = (int)$_POST['id'];
            $cms['apart'] = array_values(array_filter($cms['apart'], fn($a) => $a['id'] !== $id));
            header('Location: /admin/about-cms.php?tab=apart&msg=deleted');
            exit;
        }

        if ($action === 'toggle_publish_apart') {
            $id = (int)$_POST['id'];
            foreach ($cms['apart'] as &$a) {
                if ($a['id'] === $id) {
                    $a['is_published'] = empty($a['is_published']) ? 1 : 0;
                    break;
                }
            }
            header('Location: /admin/about-cms.php?tab=apart&msg=status_updated');
            exit;
        }

        // 4. Approach (ZUVIO)
        if ($action === 'save_approach') {
            foreach ($cms['approach'] as $idx => &$app) {
                $app['title'] = trim($_POST['title_' . $idx] ?? $app['title']);
                $app['desc'] = trim($_POST['desc_' . $idx] ?? $app['desc']);
                $app['is_published'] = isset($_POST['published_' . $idx]) ? 1 : 0;
            }
            header('Location: /admin/about-cms.php?tab=approach&msg=saved');
            exit;
        }

        // 5. Who Should Choose Zuvio (Audiences)
        if ($action === 'add_audience') {
            $new_id = time();
            $cms['audiences'][] = [
                'id' => $new_id,
                'title' => trim($_POST['title'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'sort_order' => count($cms['audiences']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/about-cms.php?tab=audiences&msg=added');
            exit;
        }

        if ($action === 'edit_audience') {
            $id = (int)$_POST['id'];
            foreach ($cms['audiences'] as &$aud) {
                if ($aud['id'] === $id) {
                    $aud['title'] = trim($_POST['title'] ?? '');
                    $aud['desc'] = trim($_POST['desc'] ?? '');
                    $aud['sort_order'] = (int)($_POST['sort_order'] ?? $aud['sort_order']);
                    break;
                }
            }
            usort($cms['audiences'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/about-cms.php?tab=audiences&msg=updated');
            exit;
        }

        if ($action === 'delete_audience') {
            $id = (int)$_POST['id'];
            $cms['audiences'] = array_values(array_filter($cms['audiences'], fn($aud) => $aud['id'] !== $id));
            header('Location: /admin/about-cms.php?tab=audiences&msg=deleted');
            exit;
        }

        if ($action === 'toggle_publish_audience') {
            $id = (int)$_POST['id'];
            foreach ($cms['audiences'] as &$aud) {
                if ($aud['id'] === $id) {
                    $aud['is_published'] = empty($aud['is_published']) ? 1 : 0;
                    break;
                }
            }
            header('Location: /admin/about-cms.php?tab=audiences&msg=status_updated');
            exit;
        }

        // 6. Founder's Message
        if ($action === 'save_founder_message') {
            $cms['founder_message_data']['title'] = trim($_POST['founder_title'] ?? '');
            $cms['founder_message_data']['salutation'] = trim($_POST['founder_salutation'] ?? '');
            $cms['founder_message_data']['image'] = trim($_POST['founder_image'] ?? '');
            $cms['founder_message_data']['signoff_name'] = trim($_POST['signoff_name'] ?? 'Founder');
            $cms['founder_message_data']['signoff_org'] = trim($_POST['signoff_org'] ?? 'Zuvio Global School');

            $raw_paras = trim($_POST['founder_paras'] ?? '');
            $paras = array_filter(array_map('trim', explode("\n\n", $raw_paras)));
            if (!empty($paras)) {
                $cms['founder_message_data']['paragraphs'] = array_values($paras);
            }

            header('Location: /admin/about-cms.php?tab=founder_msg&msg=saved');
            exit;
        }

        // 7. Awards: Add / Edit / Delete / Toggle
        if ($action === 'add_award') {
            $new_id = time();
            $cms['awards'][] = [
                'id' => $new_id,
                'title' => trim($_POST['title'] ?? ''),
                'org' => trim($_POST['org'] ?? ''),
                'desc' => trim($_POST['desc'] ?? ''),
                'sort_order' => count($cms['awards']) + 1,
                'is_published' => 1
            ];
            header('Location: /admin/about-cms.php?tab=awards&msg=added');
            exit;
        }

        if ($action === 'edit_award') {
            $id = (int)$_POST['id'];
            foreach ($cms['awards'] as &$aw) {
                if ($aw['id'] === $id) {
                    $aw['title'] = trim($_POST['title'] ?? '');
                    $aw['org'] = trim($_POST['org'] ?? '');
                    $aw['desc'] = trim($_POST['desc'] ?? '');
                    $aw['sort_order'] = (int)($_POST['sort_order'] ?? $aw['sort_order']);
                    break;
                }
            }
            usort($cms['awards'], fn($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));
            header('Location: /admin/about-cms.php?tab=awards&msg=updated');
            exit;
        }

        if ($action === 'delete_award') {
            $id = (int)$_POST['id'];
            $cms['awards'] = array_values(array_filter($cms['awards'], fn($aw) => $aw['id'] !== $id));
            header('Location: /admin/about-cms.php?tab=awards&msg=deleted');
            exit;
        }

        if ($action === 'toggle_publish_award') {
            $id = (int)$_POST['id'];
            foreach ($cms['awards'] as &$aw) {
                if ($aw['id'] === $id) {
                    $aw['is_published'] = empty($aw['is_published']) ? 1 : 0;
                    break;
                }
            }
            header('Location: /admin/about-cms.php?tab=awards&msg=status_updated');
            exit;
        }
    }
}

$page_slug = 'admin-about-cms';
include_once dirname(__FILE__) . '/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-family: var(--font-secondary); font-size: 1.6rem; color: var(--color-navy); margin-bottom: 0.25rem;">
      About Us Content Management System
    </h1>
    <p style="color: var(--color-muted); font-size: 0.88rem;">
      Manage all approved sections of the About Us page synchronized with live frontend (/about).
    </p>
  </div>
  <div style="display: flex; gap: 0.6rem;">
    <a href="/about" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
      Preview Live /about &nearr;
    </a>
  </div>
</div>

<?php if ($msg): ?>
  <div style="background-color: var(--color-surface-blue); border-left: 4px solid var(--color-success); padding: 0.75rem 1rem; border-radius: var(--radius-sm); color: var(--color-navy); font-size: 0.88rem; margin-bottom: 1.5rem;">
    <?php 
      if ($msg === 'saved') echo 'Changes successfully saved and synchronized.';
      elseif ($msg === 'added') echo 'New record successfully added.';
      elseif ($msg === 'updated') echo 'Record successfully updated.';
      elseif ($msg === 'deleted') echo 'Record successfully deleted.';
      elseif ($msg === 'status_updated') echo 'Publish/Unpublish visibility status toggled.';
    ?>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div style="background:#fde8e8; border-left:4px solid #c81e1e; padding:0.75rem 1rem; margin-bottom:1.5rem; color:#9b1c1c; font-size: 0.88rem;">
    <?php echo h($error); ?>
  </div>
<?php endif; ?>

<!-- Tabs Navigation -->
<div style="display: flex; gap: 0.5rem; border-bottom: 2px solid var(--color-border); margin-bottom: 2rem; overflow-x: auto; padding-bottom: 0.25rem;">
  <a href="?tab=story" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'story' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    1. About Zuvio & Vision
  </a>
  <a href="?tab=values" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'values' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    2. Values (6)
  </a>
  <a href="?tab=apart" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'apart' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    3. What Sets Us Apart (6)
  </a>
  <a href="?tab=approach" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'approach' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    4. ZUVIO Approach
  </a>
  <a href="?tab=audiences" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'audiences' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    5. Who Should Choose (8)
  </a>
  <a href="?tab=founder_msg" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'founder_msg' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    6. Founder's Message
  </a>
  <a href="?tab=awards" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; <?php echo $tab === 'awards' ? 'background: var(--color-navy); color: #fff;' : 'color: var(--color-text); background: #f8fafc;'; ?>">
    7. Awards & Media
  </a>
  <a href="/admin/profiles" style="padding: 0.6rem 1rem; font-weight: 600; font-size: 0.88rem; text-decoration: none; border-radius: var(--radius-sm) var(--radius-sm) 0 0; color: var(--color-teal); background: var(--pastel-blue);">
    Team Profiles &rarr;
  </a>
</div>

<?php if ($tab === 'story'): ?>
  <!-- TAB 1: Story, Vision, Mission -->
  <form method="POST" action="/admin/about-cms.php">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    <input type="hidden" name="action" value="save_story">

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        About Zuvio (Origin & Story)
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Subtitle / Kicker</label>
        <input type="text" name="story_subtitle" value="<?php echo h($cms['story']['subtitle'] ?? 'About Zuvio'); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Main Story Headline</label>
        <input type="text" name="story_title" value="<?php echo h($cms['story']['title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Approved Story Content</label>
        <textarea name="story_content" rows="6" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.6;"><?php echo h($cms['story']['content']); ?></textarea>
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Feature Image URL</label>
        <input type="text" name="story_image" value="<?php echo h($cms['story']['image']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>
    </div>

    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
        Institutional Vision & Mission
      </h3>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Our Vision</label>
        <textarea name="vision" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($cms['vision_mission']['vision']); ?></textarea>
      </div>
      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Our Mission</label>
        <textarea name="mission" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);"><?php echo h($cms['vision_mission']['mission']); ?></textarea>
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Save Story & Vision</button>
  </form>

<?php elseif ($tab === 'values'): ?>
  <!-- TAB 2: Values (What Matters at Zuvio) -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">Values — What Matters at Zuvio</h3>
    <button onclick="document.getElementById('add-value-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add New Value
    </button>
  </div>

  <!-- Add Value Modal / Form -->
  <div id="add-value-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add New Value</h4>
    <form method="POST" action="/admin/about-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_value">
      <div style="display: grid; grid-template-columns: 80px 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Icon</label>
          <input type="text" name="icon" value="🎯" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
          <input type="text" name="title" required placeholder="e.g. Child at the Centre" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Value</button>
        <button type="button" onclick="document.getElementById('add-value-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <!-- Values List -->
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($cms['values'] as $v): ?>
      <div class="card" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 260px;">
          <div style="font-size: 1.5rem; width: 40px; height: 40px; border-radius: 8px; background: var(--pastel-blue); display: flex; align-items: center; justify-content: center;">
            <?php echo $v['icon'] ?? '✨'; ?>
          </div>
          <div>
            <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
              <?php echo h($v['title']); ?>
              <?php if (empty($v['is_published'])): ?>
                <span style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">UNPUBLISHED</span>
              <?php else: ?>
                <span style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">PUBLISHED</span>
              <?php endif; ?>
            </h4>
            <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
              <?php echo h($v['desc']); ?>
            </p>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <!-- Toggle Publish -->
          <form method="POST" action="/admin/about-cms.php" style="margin: 0;">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="toggle_publish_value">
            <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
              <?php echo empty($v['is_published']) ? 'Publish' : 'Unpublish'; ?>
            </button>
          </form>

          <!-- Edit Modal Trigger -->
          <button onclick="document.getElementById('edit-val-<?php echo $v['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>

          <!-- Delete -->
          <form method="POST" action="/admin/about-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this value?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_value">
            <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal Form -->
      <div id="edit-val-<?php echo $v['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit Value #<?php echo $v['id']; ?></h4>
        <form method="POST" action="/admin/about-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_value">
          <input type="hidden" name="id" value="<?php echo (int)$v['id']; ?>">
          <div style="display: grid; grid-template-columns: 80px 1fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Icon</label>
              <input type="text" name="icon" value="<?php echo h($v['icon'] ?? '🎯'); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
              <input type="text" name="title" value="<?php echo h($v['title']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($v['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($v['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Value</button>
            <button type="button" onclick="document.getElementById('edit-val-<?php echo $v['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($tab === 'apart'): ?>
  <!-- TAB 3: What Sets Us Apart (6 Cards) -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">What Sets Us Apart</h3>
    <button onclick="document.getElementById('add-apart-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add New Differentiator
    </button>
  </div>

  <!-- Add Apart Form -->
  <div id="add-apart-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add Differentiator</h4>
    <form method="POST" action="/admin/about-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_apart">
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1rem;">
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Badge / Tag</label>
          <input type="text" name="tag" placeholder="e.g. Human Touch" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
          <input type="text" name="title" required placeholder="e.g. Online but Deeply Human" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Differentiator</button>
        <button type="button" onclick="document.getElementById('add-apart-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($cms['apart'] as $a): ?>
      <div class="card" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
          <span style="font-size: 0.75rem; font-weight: 700; color: var(--color-teal); text-transform: uppercase;">
            <?php echo h($a['tag'] ?? 'Differentiator'); ?>
          </span>
          <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0.2rem 0 0.25rem 0;">
            <?php echo h($a['title']); ?>
            <?php if (empty($a['is_published'])): ?>
              <span style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">UNPUBLISHED</span>
            <?php else: ?>
              <span style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">PUBLISHED</span>
            <?php endif; ?>
          </h4>
          <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
            <?php echo h($a['desc']); ?>
          </p>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <form method="POST" action="/admin/about-cms.php" style="margin: 0;">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="toggle_publish_apart">
            <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
              <?php echo empty($a['is_published']) ? 'Publish' : 'Unpublish'; ?>
            </button>
          </form>

          <button onclick="document.getElementById('edit-apart-<?php echo $a['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>

          <form method="POST" action="/admin/about-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this card?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_apart">
            <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal Form -->
      <div id="edit-apart-<?php echo $a['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit Differentiator #<?php echo $a['id']; ?></h4>
        <form method="POST" action="/admin/about-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_apart">
          <input type="hidden" name="id" value="<?php echo (int)$a['id']; ?>">
          <div style="display: grid; grid-template-columns: 1fr 2fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Badge / Tag</label>
              <input type="text" name="tag" value="<?php echo h($a['tag'] ?? 'Differentiator'); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
              <input type="text" name="title" value="<?php echo h($a['title']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($a['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($a['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Differentiator</button>
            <button type="button" onclick="document.getElementById('edit-apart-<?php echo $a['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($tab === 'approach'): ?>
  <!-- TAB 4: ZUVIO Approach Acronym Flow -->
  <div class="card" style="padding: 2rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">
      The ZUVIO Approach (Z-U-V-I-O)
    </h3>
    <form method="POST" action="/admin/about-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_approach">

      <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <?php foreach ($cms['approach'] as $idx => $item): ?>
          <div style="border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: 1.25rem; background: #fafbfc;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
              <span style="font-weight: 700; font-size: 1.1rem; color: var(--color-navy); display: inline-flex; align-items: center; gap: 0.5rem;">
                <span style="background: var(--color-gold); color: var(--color-navy-dark); width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                  <?php echo h($item['letter']); ?>
                </span>
                Letter <?php echo h($item['letter']); ?> Pillar
              </span>
              <label style="font-size: 0.85rem; display: flex; align-items: center; gap: 0.35rem; cursor: pointer;">
                <input type="checkbox" name="published_<?php echo $idx; ?>" value="1" <?php echo !empty($item['is_published']) ? 'checked' : ''; ?>>
                Publish on Live Site
              </label>
            </div>
            <div style="margin-bottom: 0.75rem;">
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Pillar Title</label>
              <input type="text" name="title_<?php echo $idx; ?>" value="<?php echo h($item['title']); ?>" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
              <textarea name="desc_<?php echo $idx; ?>" rows="2" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($item['desc']); ?></textarea>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem; padding: 0.75rem 2rem;">Save ZUVIO Approach</button>
    </form>
  </div>

<?php elseif ($tab === 'audiences'): ?>
  <!-- TAB 5: Who Should Choose Zuvio (8 Audience Cards) -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">Who Should Choose Zuvio (8 Audiences)</h3>
    <button onclick="document.getElementById('add-audience-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add Target Audience
    </button>
  </div>

  <!-- Add Audience Form -->
  <div id="add-audience-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add Target Audience</h4>
    <form method="POST" action="/admin/about-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_audience">
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Audience Headline</label>
        <input type="text" name="title" required placeholder="e.g. Young Athletes & Performers" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Audience</button>
        <button type="button" onclick="document.getElementById('add-audience-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($cms['audiences'] as $aud): ?>
      <div class="card" style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
            <?php echo h($aud['title']); ?>
            <?php if (empty($aud['is_published'])): ?>
              <span style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">UNPUBLISHED</span>
            <?php else: ?>
              <span style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">PUBLISHED</span>
            <?php endif; ?>
          </h4>
          <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
            <?php echo h($aud['desc']); ?>
          </p>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <form method="POST" action="/admin/about-cms.php" style="margin: 0;">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="toggle_publish_audience">
            <input type="hidden" name="id" value="<?php echo (int)$aud['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
              <?php echo empty($aud['is_published']) ? 'Publish' : 'Unpublish'; ?>
            </button>
          </form>

          <button onclick="document.getElementById('edit-aud-<?php echo $aud['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>

          <form method="POST" action="/admin/about-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this audience?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_audience">
            <input type="hidden" name="id" value="<?php echo (int)$aud['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal Form -->
      <div id="edit-aud-<?php echo $aud['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit Audience #<?php echo $aud['id']; ?></h4>
        <form method="POST" action="/admin/about-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_audience">
          <input type="hidden" name="id" value="<?php echo (int)$aud['id']; ?>">
          <div style="display: grid; grid-template-columns: 1fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
              <input type="text" name="title" value="<?php echo h($aud['title']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($aud['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($aud['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Audience</button>
            <button type="button" onclick="document.getElementById('edit-aud-<?php echo $aud['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php elseif ($tab === 'founder_msg'): ?>
  <!-- TAB 6: Founder's Message -->
  <div class="card" style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
      <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">
        Founder’s Message (Strictly Approved Source)
      </h3>
      <span style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.78rem; font-weight: 700;">
        Rule: Must use "Founder's Message"
      </span>
    </div>

    <form method="POST" action="/admin/about-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="save_founder_message">

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Letter Headline</label>
        <input type="text" name="founder_title" value="<?php echo h($cms['founder_message_data']['title']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Salutation</label>
        <input type="text" name="founder_salutation" value="<?php echo h($cms['founder_message_data']['salutation']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <div class="form-group" style="margin-bottom: 1.25rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Letter Paragraphs (Separate each paragraph with two newlines)</label>
        <textarea name="founder_paras" rows="10" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-family: inherit; line-height: 1.6;"><?php echo h(implode("\n\n", $cms['founder_message_data']['paragraphs'])); ?></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Sign-off Name / Role</label>
          <input type="text" name="signoff_name" value="<?php echo h($cms['founder_message_data']['signoff_name']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
        <div>
          <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Organization</label>
          <input type="text" name="signoff_org" value="<?php echo h($cms['founder_message_data']['signoff_org']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
        </div>
      </div>

      <div class="form-group" style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; color: var(--color-navy); margin-bottom: 0.5rem; font-size: 0.85rem;">Founder Portrait Image URL</label>
        <input type="text" name="founder_image" value="<?php echo h($cms['founder_message_data']['image']); ?>" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: var(--radius-sm);">
      </div>

      <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Save Founder’s Message</button>
    </form>
  </div>

<?php elseif ($tab === 'awards'): ?>
  <!-- TAB 7: Awards & Media -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h3 style="color: var(--color-navy); font-size: 1.25rem; margin: 0;">Awards & Media Recognition</h3>
    <button onclick="document.getElementById('add-award-form').style.display='block'" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">
      + Add New Award
    </button>
  </div>

  <!-- Add Award Form -->
  <div id="add-award-form" class="card" style="display: none; padding: 1.5rem; margin-bottom: 2rem; background: var(--pastel-blue);">
    <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Add Award / Media Item</h4>
    <form method="POST" action="/admin/about-cms.php">
      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <input type="hidden" name="action" value="add_award">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Award Title</label>
          <input type="text" name="title" required placeholder="e.g. Best E-School of 2023" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
        <div>
          <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Conferring Organization</label>
          <input type="text" name="org" required placeholder="e.g. Global Education Summit" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
        </div>
      </div>
      <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
        <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
      </div>
      <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Save Award</button>
        <button type="button" onclick="document.getElementById('add-award-form').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
      </div>
    </form>
  </div>

  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <?php foreach ($cms['awards'] as $aw): ?>
      <div class="card" style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 260px;">
          <h4 style="font-size: 1.05rem; color: var(--color-navy); margin: 0 0 0.25rem 0;">
            🏆 <?php echo h($aw['title']); ?>
            <span style="font-size: 0.8rem; color: var(--color-teal); font-weight: 600; margin-left: 0.5rem;">(<?php echo h($aw['org']); ?>)</span>
            <?php if (empty($aw['is_published'])): ?>
              <span style="background: #fee2e2; color: #991b1b; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">UNPUBLISHED</span>
            <?php else: ?>
              <span style="background: #dcfce7; color: #166534; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700; margin-left: 0.5rem;">PUBLISHED</span>
            <?php endif; ?>
          </h4>
          <p style="color: var(--color-text); font-size: 0.85rem; margin: 0; line-height: 1.4;">
            <?php echo h($aw['desc']); ?>
          </p>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <form method="POST" action="/admin/about-cms.php" style="margin: 0;">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="toggle_publish_award">
            <input type="hidden" name="id" value="<?php echo (int)$aw['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
              <?php echo empty($aw['is_published']) ? 'Publish' : 'Unpublish'; ?>
            </button>
          </form>

          <button onclick="document.getElementById('edit-aw-<?php echo $aw['id']; ?>').style.display='block'" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem;">
            Edit
          </button>

          <form method="POST" action="/admin/about-cms.php" style="margin: 0;" onsubmit="return confirm('Delete this award?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="action" value="delete_award">
            <input type="hidden" name="id" value="<?php echo (int)$aw['id']; ?>">
            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; color: #dc2626; border-color: #dc2626;">
              Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Edit Modal Form -->
      <div id="edit-aw-<?php echo $aw['id']; ?>" class="card" style="display: none; padding: 1.5rem; margin-top: -0.5rem; margin-bottom: 1rem; border-top: 3px solid var(--color-gold);">
        <h4 style="color: var(--color-navy); margin-bottom: 1rem;">Edit Award #<?php echo $aw['id']; ?></h4>
        <form method="POST" action="/admin/about-cms.php">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          <input type="hidden" name="action" value="edit_award">
          <input type="hidden" name="id" value="<?php echo (int)$aw['id']; ?>">
          <div style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 1rem; margin-bottom: 1rem;">
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Title</label>
              <input type="text" name="title" value="<?php echo h($aw['title']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Organization</label>
              <input type="text" name="org" value="<?php echo h($aw['org']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
            <div>
              <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sort Order</label>
              <input type="number" name="sort_order" value="<?php echo (int)($aw['sort_order'] ?? 1); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;">
            </div>
          </div>
          <div style="margin-bottom: 1rem;">
            <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.25rem;">Description</label>
            <textarea name="desc" required rows="2" style="width: 100%; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 4px;"><?php echo h($aw['desc']); ?></textarea>
          </div>
          <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Update Award</button>
            <button type="button" onclick="document.getElementById('edit-aw-<?php echo $aw['id']; ?>').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Cancel</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

<?php endif; ?>

<?php include_once dirname(__FILE__) . '/footer.php'; ?>
