<?php
// Zuvio Global School - Homepage Template
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$form_status = 'idle';
$error_message = '';

// Handle Enquiry Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enquiry'])) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $form_status = 'error';
        $error_message = 'Security validation failed. Please refresh and try again.';
    } else {
        $parent_name = trim($_POST['parent_name'] ?? '');
        $student_name = trim($_POST['student_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $grade = trim($_POST['grade'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($parent_name) || empty($email) || empty($phone) || empty($grade)) {
            $form_status = 'error';
            $error_message = 'All fields except message are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_status = 'error';
            $error_message = 'Please enter a valid email address.';
        } else {
            try {
                if (!$db) throw new Exception("Database offline");
                
                $stmt = $db->prepare("
                    INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                    VALUES (?, ?, ?, ?, ?, ?, 'Home Banner', 1)
                ");
                $stmt->execute([
                    $parent_name,
                    $student_name ?: ($parent_name . ' (Student)'),
                    $grade,
                    $phone,
                    $email,
                    $message ?: 'Submitted via Hero banner form'
                ]);
                $form_status = 'success';
            } catch (Exception $e) {
                $form_status = 'error';
                $error_message = 'Database connection required. Form could not be persisted.';
            }
        }
    }
}

// Fetch Hero Slides
$slides = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `hero_slides` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $slides = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Home Slides Error] " . $e->getMessage());
    }
}

if (empty($slides)) {
    $slides = [
        [
            'title' => 'A Future-Ready Online School',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Academic excellence meets personalised online learning. We prepare children for a changing world.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/homepage_hero_1.jpg',
            'video' => '/assets/images/01_Collaborative_Project_Learning.mp4',
            'media_type' => 'video'
        ],
        [
            'title' => 'Personalised Learning Paths',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Every child learns differently. Our interactive virtual classrooms adapt to your child\'s pace.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Hero image 2.png',
            'video' => '/assets/images/02_Online_Robotics_Learning.mp4',
            'media_type' => 'video'
        ],
        [
            'title' => 'Interactive Science & Lab Work',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Virtual experiments, hands-on activities, and coding built into the core curriculum.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Students learning in classroom.png',
            'video' => '/assets/images/03_Science_Experiment_Learning.mp4',
            'media_type' => 'video'
        ],
        [
            'title' => 'Building Leaders of Tomorrow',
            'subtitle' => 'ZUVIO GLOBAL SCHOOL',
            'description' => 'Public speaking, collaboration, global citizenship, and creative expression.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Teacher interacting with students.png',
            'video' => '/assets/images/04_Student_Presentation_Learning.mp4',
            'media_type' => 'video'
        ]
    ];
} else {
    foreach ($slides as &$slide) {
        $slide['subtitle'] = 'ZUVIO GLOBAL SCHOOL';
    }
}

// Fetch USP Features
$features = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `homepage_features` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $features = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Home Features Error] " . $e->getMessage());
    }
}

if (empty($features)) {
    $features = [
        [
            'title' => 'Global Presence',
            'description' => 'A globally connected learning community with an international outlook.',
            'bg_color' => 'var(--pastel-blue)',
            'border_color' => 'rgba(10, 137, 152, 0.2)'
        ],
        [
            'title' => 'International Credibility',
            'description' => 'Global standards, perspectives, and learning practices designed for a changing world.',
            'bg_color' => 'var(--pastel-yellow)',
            'border_color' => 'rgba(217, 164, 65, 0.2)'
        ],
        [
            'title' => 'World-Class Teachers',
            'description' => 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.',
            'bg_color' => 'var(--pastel-orange)',
            'border_color' => 'rgba(217, 164, 65, 0.2)'
        ],
        [
            'title' => 'US-Based Learning Platform',
            'description' => 'A powerful, thoughtfully designed US-based LMS that brings learning, collaboration, resources, and progress tracking together.',
            'bg_color' => 'var(--pastel-green)',
            'border_color' => 'rgba(10, 137, 152, 0.2)'
        ],
        [
            'title' => 'CBSE, NEP & NCF Aligned',
            'description' => 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.',
            'bg_color' => 'var(--pastel-purple)',
            'border_color' => 'rgba(6, 43, 99, 0.1)'
        ],
        [
            'title' => 'Personalised Learning',
            'description' => 'Learning experiences that recognise every child\'s unique pace, strengths, interests, and potential.',
            'bg_color' => 'var(--pastel-red)',
            'border_color' => 'rgba(217, 70, 239, 0.2)'
        ],
        [
            'title' => 'Futuristic Learning',
            'description' => 'Empowering learners with future-ready skills, technology, creativity, and critical thinking to thrive in an ever-changing world.',
            'bg_color' => 'var(--pastel-blue)',
            'border_color' => 'rgba(10, 137, 152, 0.2)'
        ],
        [
            'title' => 'World-Class Experiences',
            'description' => 'Beyond academics—with technology, creativity, collaboration, projects, and real-world experiences.',
            'bg_color' => 'var(--pastel-yellow)',
            'border_color' => 'rgba(217, 164, 65, 0.2)'
        ]
    ];
} else {
    $colors = [
        'var(--pastel-blue)', 'var(--pastel-yellow)', 'var(--pastel-orange)', 'var(--pastel-green)',
        'var(--pastel-purple)', 'var(--pastel-red)', 'var(--pastel-blue)', 'var(--pastel-yellow)'
    ];
    $borders = [
        'rgba(10, 137, 152, 0.2)', 'rgba(217, 164, 65, 0.2)', 'rgba(217, 164, 65, 0.2)', 'rgba(10, 137, 152, 0.2)',
        'rgba(6, 43, 99, 0.1)', 'rgba(217, 70, 239, 0.2)', 'rgba(10, 137, 152, 0.2)', 'rgba(217, 164, 65, 0.2)'
    ];
    foreach ($features as $idx => &$feat) {
        $feat['bg_color'] = $colors[$idx % count($colors)];
        $feat['border_color'] = $borders[$idx % count($borders)];
        if ($feat['title'] === 'Inclusive Learning' || $feat['title'] === 'Futuristic Learning') {
            $feat['title'] = 'Futuristic Learning';
            $feat['description'] = 'Empowering learners with future-ready skills, technology, creativity, and critical thinking to thrive in an ever-changing world.';
        }
    }
}

// Fetch stats
$stats = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `homepage_stats` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $stats = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Home Stats Error] " . $e->getMessage());
    }
}

if (empty($stats)) {
    $stats = [
        ['label' => 'Established', 'value' => '2026', 'id_key' => 'stat_established'],
        ['label' => 'Student-Teacher Ratio', 'value' => '15:1', 'id_key' => 'stat_ratio'],
        ['label' => 'Students Enrolled', 'value' => '120+', 'id_key' => 'stat_students'],
        ['label' => 'World-Class Educators', 'value' => '25+', 'id_key' => 'stat_educators'],
        ['label' => 'Experienced Special Educators', 'value' => '1', 'id_key' => 'stat_special']
    ];
} else {
    // Ensure stats are updated and formatted
    $has_special = false;
    foreach ($stats as &$st) {
        if ($st['label'] === 'Students Enrolled') {
            $st['value'] = '120+';
            $st['id_key'] = 'stat_students';
        } elseif ($st['label'] === 'World-Class Educators') {
            $st['value'] = '25+';
            $st['id_key'] = 'stat_educators';
        } elseif ($st['label'] === 'Established') {
            $st['id_key'] = 'stat_established';
        } elseif ($st['label'] === 'Student-Teacher Ratio') {
            $st['id_key'] = 'stat_ratio';
        }
        if (strpos($st['label'], 'Special Educators') !== false) {
            $has_special = true;
        }
    }
    if (!$has_special) {
        $stats[] = ['label' => 'Experienced Special Educators', 'value' => '1', 'id_key' => 'stat_special'];
    }
}

// Fetch Brand Promise Section
$brand_promise = ['title' => '', 'subtitle' => '', 'content' => ''];
if ($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM `homepage_sections` WHERE `section_key` = 'brand_promise' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) $brand_promise = $row;
    } catch (Exception $e) {
        error_log("[Home Section Error] " . $e->getMessage());
    }
}

if (empty($brand_promise['title'])) {
    $brand_promise = [
        'title' => 'Every child deserves an education that prepares them for life, not just examinations.',
        'subtitle' => 'Zuvio’s Promise and Philosophy',
        'content' => 'We are not building another future. We are building a future where every child has the opportunity to learn beyond boundaries.'
    ];
} else {
    $brand_promise['subtitle'] = 'Zuvio’s Promise and Philosophy';
    $brand_promise['content'] = 'We are not building another future. We are building a future where every child has the opportunity to learn beyond boundaries.';
}

// Fetch Published Blogs
$posts = [];
if ($db) {
    try {
        $stmt = $db->query("
            SELECT b.*, c.name as category_name 
            FROM `blogs` b 
            LEFT JOIN `blog_categories` c ON c.id = b.category_id 
            WHERE b.status = 'published' 
            ORDER BY b.publish_date DESC LIMIT 3
        ");
        $posts = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Home Blogs Error] " . $e->getMessage());
    }
}

include_once dirname(__FILE__) . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-container">
  <!-- Bubbles Layer -->
  <div class="bubbles-background">
    <div class="bubble-particle" style="width: 150px; height: 150px; background-color: var(--pastel-yellow); top: 15%; left: 10%;"></div>
    <div class="bubble-particle" style="width: 250px; height: 250px; background-color: var(--pastel-blue); bottom: 10%; right: 15%; animation-delay: -3s;"></div>
  </div>

  <?php if (!empty($slides)): ?>
    <?php foreach ($slides as $idx => $slide): ?>
      <div class="hero-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
        <?php if (!empty($slide['video']) && ($slide['media_type'] ?? 'image') === 'video'): ?>
          <!-- Video Background -->
          <div class="hero-bg-img" style="background-color: var(--color-navy);">
            <video
              autoplay
              muted
              loop
              playsinline
              preload="metadata"
              style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; filter: brightness(0.95);"
            >
              <source src="<?php echo h($slide['video']); ?>" type="video/<?php echo pathinfo($slide['video'], PATHINFO_EXTENSION); ?>">
            </video>
          </div>
        <?php else: ?>
          <!-- Image Background -->
          <div class="hero-bg-img" style="background-image: url('<?php echo h($slide['image']); ?>'); background-size: cover; background-position: center; filter: brightness(1.02);"></div>
        <?php endif; ?>
        <div class="hero-overlay"></div>
        
        <div class="hero-split-grid">
          <div class="hero-content">
            <div class="hero-school-badge">
              <span class="badge-dot"></span>
              Zuvio Global School
            </div>
            <h1 class="hero-title"><?php echo h($slide['title']); ?></h1>
            <p class="hero-description"><?php echo h($slide['description']); ?></p>
            
            <div class="hero-btn-row">
              <?php if (!empty($slide['primary_cta_text'])): ?>
                <a href="<?php echo h($slide['primary_cta_url']); ?>" class="btn btn-primary" style="background-color: var(--color-navy); border-color: var(--color-navy); color: #fff;"><?php echo h($slide['primary_cta_text']); ?></a>
              <?php endif; ?>
              <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="background-color: var(--color-teal); border-color: var(--color-teal); color: #fff;">Book a Demo</a>
            </div>
          </div>
          <div class="hero-form-spacer"></div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- Absolute Form Overlay -->
  <div class="hero-absolute-form">
    <div class="hero-form-card">
      <?php if ($form_status === 'success'): ?>
        <div class="form-success-state">
          <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-success); margin-bottom: 1rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          <h3>Enquiry Received</h3>
          <p>Thank you. We will get in touch with you shortly to plan your child's roadmap.</p>
        </div>
      <?php else: ?>
        <form method="POST" action="">
          <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
          <input type="hidden" name="submit_enquiry" value="1">
          <h3>Enquire Now</h3>
          <p class="form-tagline">Take the first step towards your child's learning journey.</p>
          
          <?php if ($form_status === 'error'): ?>
            <div class="form-error-alert">
              <span><?php echo h($error_message); ?></span>
            </div>
          <?php endif; ?>

          <div class="form-grid-row">
            <input type="text" name="parent_name" placeholder="Parent Name" required class="hero-input">
            <input type="text" name="student_name" placeholder="Student Name (Opt)" class="hero-input">
          </div>
          
          <div class="form-grid-row">
            <input type="email" name="email" placeholder="Email Address" required class="hero-input">
            <input type="tel" name="phone" placeholder="Phone Number" required class="hero-input">
          </div>

          <select name="grade" required class="hero-input" style="width: 100%; margin-bottom: 0.75rem;">
            <option value="">Select Grade of Interest</option>
            <option value="Early Years">Early Years (K)</option>
            <option value="Grades 1-5">Primary (Grades 1-5)</option>
            <option value="Grades 6-8">Middle School (Grades 6-8)</option>
          </select>

          <textarea name="message" placeholder="Message / Question (Optional)" rows="2" class="hero-input" style="resize: none; margin-bottom: 0.75rem;"></textarea>

          <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: 600; padding: 0.75rem; background-color: var(--color-navy); border-color: var(--color-navy); color: #fff;">Submit Enquiry</button>
        </form>
      <?php endif; ?>
    </div>
  </div>

  <!-- Carousel Indicators -->
  <div class="hero-indicators">
    <?php foreach ($slides as $idx => $slide): ?>
      <button class="hero-indicator-dot <?php echo $idx === 0 ? 'active' : ''; ?>" aria-label="Go to slide <?php echo $idx + 1; ?>"></button>
    <?php endforeach; ?>
  </div>
</section>

<!-- Section 2: Brand Promise -->
<?php if (!empty($brand_promise['title'])): ?>
<section class="section text-center" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container" style="max-width: 800px;">
    <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 1.5px;"><?php echo h($brand_promise['subtitle']); ?></span>
    <h2 style="font-size: 2.25rem; color: var(--color-navy); margin: 1rem 0 1.5rem 0; font-family: var(--font-primary);"><?php echo h($brand_promise['title']); ?></h2>
    <p style="color: var(--color-muted); font-size: 1.1rem; line-height: 1.8;"><?php echo h($brand_promise['content']); ?></p>
  </div>
</section>
<?php endif; ?>

<!-- Section 3: Why Zuvio (Asymmetric Layout) -->
<section class="section" style="background-color: var(--color-surface-blue); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="grid-2 why-zuvio-grid" style="align-items: flex-start;">
      
      <!-- Left Column: Sticky Editorial Info -->
      <div class="why-zuvio-intro-col" style="position: sticky; top: 120px;">
        <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; marginBottom: 0.5rem;">Why Zuvio</span>
        <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-bottom: 1.25rem; font-family: var(--font-primary);">A Future-Ready Online School</h2>
        <div style="width: 60px; height: 3px; background-color: var(--color-gold); margin-bottom: 1.5rem;"></div>
        <p style="color: var(--color-muted); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem;">
          At Zuvio Global School, academic excellence meets personalised learning. We prepare children for a changing world by helping them become capable, compassionate global learners.
        </p>
        <a href="/about" class="btn btn-outline">Read Our Story</a>
      </div>

      <!-- Right Column: Grid of Feature Cards -->
      <div class="why-zuvio-blocks" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <?php foreach ($features as $feat): 
          $bg = $feat['bg_color'] ?? 'var(--color-white)';
          $border = $feat['border_color'] ?? 'var(--color-border)';
        ?>
          <div class="card" style="padding: 1.75rem; background-color: <?php echo $bg; ?>; border-color: <?php echo $border; ?>; box-shadow: var(--shadow-sm); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
            <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem; font-family: var(--font-primary); font-weight: 700;"><?php echo h($feat['title']); ?></h3>
            <p style="color: var(--color-text); font-size: 0.88rem; line-height: 1.6;"><?php echo h($feat['description']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

<!-- Section: Global Partnerships (Oxford Quality, ISSO, IAO) -->
<section class="section text-center" style="background-color: var(--pastel-blue); border-bottom: 1px solid var(--color-border); padding: 5.5rem 0;">
  <div class="container">
    <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">Global Partnerships & Quality Associations</span>
    <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-bottom: 3.5rem; font-family: var(--font-primary); font-weight: 700;">Recognised & Benchmarked Globally</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
      <!-- Card 1: ISSO -->
      <div class="card quality-association-card" style="padding: 2.5rem 2rem; background-color: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold); text-align: left; display: flex; flex-direction: column; gap: 1rem; border-left: none;">
        <div class="quality-association-logo-container">
          <img src="/assets/images/isso-logo.png" alt="ISSO Logo" class="quality-association-logo">
        </div>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin: 0;">ISSO — International Schools Sports Organisation</h3>
        <h4 style="font-size: 0.9rem; color: var(--color-teal); font-family: var(--font-secondary); font-weight: 600; margin: 0;">Building Champions Beyond the Classroom</h4>
        <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin: 0;">
          Through its association with ISSO, Zuvio aims to provide learners access to a structured school-sports ecosystem that promotes competition, teamwork, discipline, resilience and sporting excellence. ISSO connects international-curriculum schools and student-athletes through organised multi-sport opportunities and competitive pathways.
        </p>
      </div>
      <!-- Card 2: IAO -->
      <div class="card quality-association-card iao-accreditation-card" style="padding: 2.5rem 2rem; background-color: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-teal); text-align: left; display: flex; flex-direction: column; gap: 1rem; border-left: none;">
        <div class="quality-association-logo-container">
          <a href="https://www.iao.org/India-Delhi/Zuvio-Global-School" target="_blank" rel="noopener noreferrer" class="iao-seal-link" style="display: flex; height: 100%; align-items: center;">
            <img src="/assets/images/iao-logo.png" alt="International Accreditation Organization - IAO Fully Accredited Member" class="quality-association-logo iao-main-logo">
          </a>
        </div>
        <div class="iao-accreditation-seal-container">
          <span class="iao-accreditation-label">Fully Accredited Member</span>
        </div>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin: 0;">IAO — International Accreditation Organization</h3>
        <h4 style="font-size: 0.9rem; color: var(--color-teal); font-family: var(--font-secondary); font-weight: 600; margin: 0;">Committed to Global Quality Standards</h4>
        <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin: 0;">
          Zuvio’s association with IAO reflects our focus on quality, continuous improvement and internationally benchmarked educational practices. IAO provides quality-assurance and accreditation services to educational institutions, including online and distance-learning providers.
        </p>
      </div>
      <!-- Card 3: Oxford Quality -->
      <div class="card quality-association-card" style="padding: 2.5rem 2rem; background-color: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border-top: 4px solid var(--color-gold); text-align: left; display: flex; flex-direction: column; gap: 1rem; border-left: none;">
        <div class="quality-association-logo-container">
          <img src="/assets/images/oxford-logo.png" alt="Oxford Quality Logo" class="quality-association-logo">
        </div>
        <h3 style="font-size: 1.25rem; color: var(--color-navy); font-family: var(--font-primary); font-weight: 700; margin: 0;">Oxford Quality</h3>
        <h4 style="font-size: 0.9rem; color: var(--color-teal); font-family: var(--font-secondary); font-weight: 600; margin: 0;">Powered by the Excellence of Oxford University Press</h4>
        <p style="color: var(--color-muted); font-size: 0.9rem; line-height: 1.6; margin: 0;">
          As part of the Oxford Quality community, Zuvio strengthens learning through high-quality educational resources, teacher professional development and globally connected learning opportunities. Oxford Quality is an Oxford University Press programme designed to support institutions committed to continuously developing their teaching, learning methods and resources.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Section 4: Academic Statistics -->
<section class="section text-center" style="background: linear-gradient(135deg, var(--color-navy-dark) 0%, var(--color-navy) 100%); color: #FFFFFF; padding: 5rem 0;">
  <div class="container">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 2.5rem;">
      <?php foreach ($stats as $stat): 
        $id_attr = isset($stat['id_key']) ? 'id="' . h($stat['id_key']) . '"' : '';
        $val = preg_replace('/[^0-9]/', '', $stat['value']);
        $suffix = preg_replace('/[0-9]/', '', $stat['value']);
        $data_attrs = ($stat['id_key'] === 'stat_students' || $stat['id_key'] === 'stat_educators') ? 'data-target="' . $val . '" data-suffix="' . $suffix . '"' : '';
      ?>
        <div class="stat-box" style="padding: 1.5rem 1rem; background: rgba(255,255,255,0.04); border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; gap: 0.5rem; justify-content: center; align-items: center;">
          <p <?php echo $id_attr; ?> <?php echo $data_attrs; ?> style="font-size: 3rem; font-weight: 700; color: var(--color-gold); margin: 0; font-family: var(--font-primary); line-height: 1;"><?php echo h($stat['value']); ?></p>
          <p style="color: #E2E8F0; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; margin: 0; text-align: center;"><?php echo h($stat['label']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Section 5: Latest Insights / Blogs -->
<?php if (!empty($posts)): ?>
<section class="section" style="background-color: var(--color-white); border-bottom: 1px solid var(--color-border);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-gold); text-transform: uppercase; letter-spacing: 2px;">School Insights</span>
      <h2 style="font-size: 2.25rem; color: var(--color-navy); margin-top: 0.5rem; font-family: var(--font-primary);">Latest from Our Blog</h2>
    </div>

    <div class="grid-3">
      <?php foreach ($posts as $post): ?>
        <div class="card" style="padding: 0; overflow: hidden; border-left: none; border-top: 4px solid var(--color-gold);">
          <div style="height: 200px; background-color: var(--color-navy); background-image: url('<?php echo h($post['featured_image']); ?>'); background-size: cover; background-position: center;"></div>
          <div style="padding: 1.75rem; display: flex; flex-direction: column; flex-grow: 1;">
            <span style="font-size: 0.75rem; color: var(--color-gold); font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;"><?php echo h($post['category_name'] ?: 'School News'); ?></span>
            <h3 style="font-size: 1.25rem; color: var(--color-navy); margin-bottom: 1rem; line-height: 1.4;"><?php echo h($post['title']); ?></h3>
            <p style="color: var(--color-muted); font-size: 0.85rem; line-height: 1.6; margin-bottom: 1.5rem;"><?php echo h($post['excerpt']); ?></p>
            <a href="/blogs/<?php echo h($post['slug']); ?>" class="btn btn-outline" style="margin-top: auto; padding: 0.5rem 1rem; font-size: 0.8rem; align-self: flex-start;">Read Article</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- Section 7: Final CTA Banner -->
<section class="section text-center" style="background-color: var(--color-surface-warm); padding: 6.5rem 0;">
  <div class="container" style="max-width: 700px;">
    <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-primary); font-weight: 700;">Ready to Experience Zuvio?</h2>
    <p style="color: var(--color-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2.5rem;">
      Register your enquiry today to discuss a customized learning timeline for your child with our academic counselors.
    </p>
    <a href="/contact" class="btn btn-primary" style="padding: 1rem 3.5rem; font-size: 1rem;">Begin Your Journey</a>
  </div>
</section>

<!-- Additional Custom Hero Styles -->
<style>
  .hero-container {
    position: relative;
    height: 80vh;
    min-height: 620px;
    overflow: hidden;
    background-color: var(--pastel-blue);
    font-family: var(--font-secondary);
  }
  .hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    z-index: 1;
    display: flex;
    align-items: center;
  }
  .hero-slide.active {
    opacity: 1;
    z-index: 2;
  }
  .hero-bg-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
  }
  .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(235, 245, 251, 0.96) 0%, rgba(235, 245, 251, 0.8) 50%, rgba(255, 255, 255, 0.15) 100%);
    z-index: 3;
  }
  .hero-split-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 4rem;
    align-items: center;
    height: 100%;
    position: relative;
    z-index: 10;
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 3rem;
    width: 100%;
  }
  .hero-form-spacer {
    display: block;
  }
  
  /* Absolute Form Overlay layout */
  .hero-absolute-form {
    position: absolute;
    right: calc((100vw - var(--max-width)) / 2 + 3rem);
    top: 50%;
    transform: translateY(-50%);
    z-index: 20;
  }
  .hero-form-card {
    background-color: rgba(255, 255, 255, 0.98);
    border-top: 4px solid var(--color-gold);
    border-radius: var(--radius-lg);
    box-shadow: 0 20px 45px rgba(6, 43, 99, 0.18);
    padding: 1.75rem;
    width: 400px;
    color: var(--color-navy);
  }
  .hero-form-card h3 {
    font-size: 1.35rem;
    margin: 0 0 0.25rem 0;
    font-family: var(--font-secondary);
    font-weight: 700;
  }
  .form-tagline {
    font-size: 0.75rem;
    color: var(--color-muted);
    margin: 0 0 1rem 0;
  }
  .hero-input {
    width: 100%;
    padding: 0.55rem 0.75rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    outline: none;
    background-color: #FFFFFF;
    margin-bottom: 0.75rem;
    transition: border-color var(--transition-fast);
  }
  .hero-input:focus {
    border-color: var(--color-navy);
  }
  .form-grid-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
  }
  .form-success-state {
    text-align: center;
    padding: 1.5rem 0;
  }
  .success-icon {
    color: var(--color-success);
    margin-bottom: 1rem;
  }
  .form-error-alert {
    background-color: #FDF2F8;
    border: 1px solid #FBCFE8;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-sm);
    color: #D946EF;
    font-size: 0.75rem;
    margin-bottom: 0.75rem;
  }
  .hero-content {
    max-width: 700px;
    text-align: left;
  }
  .hero-school-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: var(--color-gold);
    color: var(--color-navy-dark);
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
  }
  .badge-dot {
    width: 8px;
    height: 8px;
    background-color: var(--color-teal);
    border-radius: 50%;
    display: inline-block;
    animation: badgePulse 1.5s infinite;
  }
  @keyframes badgePulse {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.25); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
  }
  .hero-title {
    font-size: 3.5rem;
    color: var(--color-navy-dark);
    margin-bottom: 1.25rem;
    line-height: 1.2;
    font-family: var(--font-primary);
    font-weight: 700;
  }
  .hero-description {
    font-size: 1.15rem;
    color: var(--color-text);
    margin-bottom: 2.5rem;
    line-height: 1.6;
  }
  .hero-btn-row {
    display: flex;
    gap: 1.25rem;
  }

  .hero-indicators {
    position: absolute;
    right: 3rem;
    bottom: 2.5rem;
    display: flex;
    gap: 0.75rem;
    z-index: 10;
  }
  .hero-indicator-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: none;
    background-color: rgba(6, 43, 99, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .hero-indicator-dot.active {
    width: 24px;
    background-color: var(--color-gold);
    border-radius: 4px;
  }

  @media (max-width: 1280px) {
    .hero-absolute-form {
      right: 3rem;
    }
  }

  /* Responsive Rules */
  @media (max-width: 1024px) {
    .hero-container {
      height: auto !important;
      min-height: auto !important;
      display: flex !important;
      flex-direction: column !important;
      background-color: var(--pastel-blue) !important;
    }
    .hero-slide {
      position: relative !important;
      top: auto !important;
      left: auto !important;
      width: 100% !important;
      height: auto !important;
      opacity: 0 !important;
      display: none !important;
      transition: none !important;
      background-color: var(--pastel-blue) !important;
    }
    .hero-slide.active {
      opacity: 1 !important;
      display: flex !important;
      flex-direction: column !important;
    }
    .hero-bg-img {
      position: relative !important;
      width: 100% !important;
      height: 380px !important;
    }
    .hero-overlay {
      display: block !important;
      background: linear-gradient(to bottom, rgba(235, 245, 251, 0.4) 0%, rgba(235, 245, 251, 0.95) 90%) !important;
    }
    .hero-split-grid {
      grid-template-columns: 1fr !important;
      gap: 2rem !important;
      padding: 3rem 1.25rem 2rem 1.25rem !important;
      height: auto !important;
    }
    .hero-content {
      max-width: 100% !important;
      text-align: center !important;
    }
    .hero-title {
      font-size: 2.5rem !important;
    }
    .hero-description {
      font-size: 1.05rem !important;
    }
    .hero-btn-row {
      justify-content: center !important;
    }
    .hero-absolute-form {
      position: relative !important;
      right: auto !important;
      top: auto !important;
      transform: none !important;
      margin: 0 auto 3rem auto !important;
      width: calc(100% - 2.5rem) !important;
      display: flex !important;
      justify-content: center !important;
    }
    .hero-form-card {
      width: 100% !important;
      max-width: 420px !important;
    }
    .hero-form-spacer, .hero-indicators {
      display: none !important;
    }
  }

  @media (max-width: 767px) {
    .hero-bg-img {
      display: block !important;
      height: 250px !important;
    }
    .hero-container {
      background: var(--pastel-blue) !important;
    }
    .hero-split-grid {
      padding: 2.5rem 1.25rem 3.5rem 1.25rem !important;
    }
    .hero-title {
      font-size: 2rem !important;
    }
    .hero-description {
      font-size: 0.95rem !important;
    }
    .why-zuvio-grid > div:first-child {
      position: static !important; /* Disable sticky sidebar on mobile */
    }
    .why-zuvio-blocks {
      grid-template-columns: 1fr !important; /* Single column stacking */
    }
  }
  /* Quality Associations Logos */
  .quality-association-logo-container {
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-bottom: 0.5rem;
  }
  .quality-association-logo {
    max-height: 100%;
    max-width: 220px;
    width: auto;
    height: auto;
    object-fit: contain;
  }
  .iao-main-logo {
    transform: scale(1.35);
    transform-origin: left center;
  }
  
  /* IAO Accreditation Seal */
  .iao-accreditation-seal-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
  }
  .iao-accreditation-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--color-teal);
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .iao-seal-link {
    display: inline-block;
  }
  .iao-accreditation-seal {
    width: 80px;
    height: 80px;
    object-fit: contain;
  }

  @media (max-width: 1024px) {
    .quality-association-logo-container {
      height: 100px;
    }
    .quality-association-logo {
      max-width: 180px;
    }
    .iao-accreditation-seal {
      width: 70px;
      height: 70px;
    }
  }

  @media (max-width: 767px) {
    .quality-association-logo-container {
      height: 90px;
    }
    .quality-association-logo {
      max-width: 160px;
    }
    .iao-accreditation-seal {
      width: 60px;
      height: 60px;
    }
  }
</style>

<script src="/js/main.js"></script>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
