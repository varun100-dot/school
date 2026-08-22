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

// Map the 4 video assets in assets/images/ to the slides
$slide_videos = [
    '/assets/images/01_Collaborative_Project_Learning.mp4',
    '/assets/images/02_Online_Robotics_Learning.mp4',
    '/assets/images/03_Science_Experiment_Learning.mp4',
    '/assets/images/04_Student_Presentation_Learning.mp4'
];

if (empty($slides)) {
    $slides = [
        [
            'title' => 'A Future-Ready Online School',
            'subtitle' => 'WELCOME TO ZUVIO GLOBAL SCHOOL',
            'description' => 'Academic excellence meets personalised online learning. We prepare children for a changing world.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Hero image 1.png'
        ],
        [
            'title' => 'Personalised Learning Paths',
            'subtitle' => 'INDIVIDUAL ATTENTION',
            'description' => 'Every child learns differently. Our interactive virtual classrooms adapt to your child\'s pace.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Hero image 2.png'
        ],
        [
            'title' => 'Interactive Science & Lab Work',
            'subtitle' => 'LEARNING BY DOING',
            'description' => 'Virtual experiments, hands-on activities, and coding built into the core curriculum.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Students learning in classroom.png'
        ],
        [
            'title' => 'Building Leaders of Tomorrow',
            'subtitle' => 'BEYOND ACADEMICS',
            'description' => 'Public speaking, collaboration, global citizenship, and creative expression.',
            'primary_cta_text' => 'Our Curriculum',
            'primary_cta_url' => '/our-curriculum',
            'secondary_cta_text' => 'Zuvio Beyond',
            'secondary_cta_url' => '/zuvio-beyond',
            'image' => '/assets/images/Teacher interacting with students.png'
        ]
    ];
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
    <div class="bubble-particle" style="width: 150px; height: 150px; background-color: var(--color-gold); top: 15%; left: 10%;"></div>
    <div class="bubble-particle" style="width: 250px; height: 250px; background-color: var(--color-teal); bottom: 10%; right: 15%; animation-delay: -3s;"></div>
  </div>

  <?php if (!empty($slides)): ?>
    <?php foreach ($slides as $idx => $slide): 
      $video_url = $slide_videos[$idx % count($slide_videos)];
    ?>
      <div class="hero-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
        <!-- Video Background -->
        <div class="hero-bg-img" style="background-color: var(--color-navy);">
          <video
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;"
          >
            <source src="<?php echo h($video_url); ?>" type="video/mp4">
          </video>
        </div>
        <div class="hero-overlay"></div>
        
        <div class="hero-split-grid">
          <div class="hero-content">
            <?php if (!empty($slide['subtitle'])): ?>
              <span class="hero-subtitle"><?php echo h($slide['subtitle']); ?></span>
            <?php endif; ?>
            <h1 class="hero-title"><?php echo h($slide['title']); ?></h1>
            <p class="hero-description"><?php echo h($slide['description']); ?></p>
            
            <div class="hero-btn-row">
              <?php if (!empty($slide['primary_cta_text'])): ?>
                <a href="<?php echo h($slide['primary_cta_url']); ?>" class="btn btn-primary"><?php echo h($slide['primary_cta_text']); ?></a>
              <?php endif; ?>
              <?php if (!empty($slide['secondary_cta_text'])): ?>
                <a href="<?php echo h($slide['secondary_cta_url']); ?>" class="btn btn-outline" style="color: #FFF; border-color: #FFF;"><?php echo h($slide['secondary_cta_text']); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

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
        <?php foreach ($features as $feat): ?>
          <div class="card" style="padding: 1.75rem;">
            <h3 style="font-size: 1.2rem; color: var(--color-navy); margin-bottom: 0.75rem;"><?php echo h($feat['title']); ?></h3>
            <p style="color: var(--color-muted); font-size: 0.85rem; line-height: 1.6;"><?php echo h($feat['description']); ?></p>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

<!-- Section 4: Academic Statistics -->
<section class="section text-center" style="background-color: var(--color-navy-dark); color: #FFFFFF;">
  <div class="container">
    <div class="grid-4">
      <?php foreach ($stats as $stat): ?>
        <div style="padding: 1rem;">
          <p style="font-size: 3rem; font-weight: 700; color: var(--color-gold); margin-bottom: 0.5rem; font-family: var(--font-primary);"><?php echo h($stat['value']); ?></p>
          <p style="color: #E2E8F0; font-size: 0.95rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;"><?php echo h($stat['label']); ?></p>
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

<!-- Section 6: Final CTA Banner -->
<section class="section text-center" style="background-color: var(--color-surface-warm); padding: 6rem 0;">
  <div class="container" style="max-width: 700px;">
    <h2 style="font-size: 2.5rem; color: var(--color-navy); margin-bottom: 1.5rem; font-family: var(--font-primary);">Ready to Experience Zuvio?</h2>
    <p style="color: var(--color-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2.5rem;">
      Register your enquiry today to discuss a customized learning timeline for your child with our academic counselors.
    </p>
    <a href="/contact" class="btn btn-primary" style="padding: 1rem 3rem;">Begin Your Journey</a>
  </div>
</section>

<!-- Additional Custom Hero Styles -->
<style>
  .hero-container {
    position: relative;
    height: 80vh;
    min-height: 620px;
    overflow: hidden;
    background-color: #000A42;
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
    background: linear-gradient(to right, rgba(0, 10, 66, 0.9) 0%, rgba(0, 10, 66, 0.7) 50%, rgba(0, 10, 66, 0.2) 100%);
    z-index: 1;
  }
  .hero-split-grid {
    display: flex;
    align-items: center;
    height: 100%;
    position: relative;
    z-index: 10;
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 3rem;
    width: 100%;
  }
  .hero-content {
    max-width: 700px;
    text-align: left;
  }
  .hero-subtitle {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2.5px;
    color: var(--color-gold);
    margin-bottom: 1rem;
    display: inline-block;
  }
  .hero-title {
    font-size: 3.5rem;
    color: #FFFFFF;
    margin-bottom: 1.25rem;
    line-height: 1.2;
  }
  .hero-description {
    font-size: 1.1rem;
    color: #E2E8F0;
    margin-bottom: 2.5rem;
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
    background-color: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .hero-indicator-dot.active {
    width: 24px;
    background-color: var(--color-gold);
    border-radius: 4px;
  }

  /* Responsive Rules */
  @media (max-width: 1024px) {
    .hero-container {
      height: auto !important;
      min-height: auto !important;
      display: flex !important;
      flex-direction: column !important;
      background-color: var(--color-navy-dark) !important;
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
      background-color: var(--color-navy-dark) !important;
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
      display: none !important;
    }
    .hero-split-grid {
      padding: 3rem 1.25rem 2.5rem 1.25rem !important;
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
    .hero-indicators {
      display: none !important;
    }
  }

  @media (max-width: 767px) {
    .hero-bg-img {
      display: none !important; /* Hide video element/box on small mobile */
    }
    .hero-container {
      background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%) !important;
    }
    .hero-split-grid {
      padding: 3.5rem 1.25rem 3.5rem 1.25rem !important;
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
</style>

<script src="/js/main.js"></script>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
