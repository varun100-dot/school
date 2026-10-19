<?php
// Zuvio Global School — Standalone Landing Page (/landing-page/)
// Exact implementation matching assets/images/landing-page/landing-page.png
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$form_status = 'idle';
$error_message = '';
$submitted_data = [];

// Handle Hero Enquiry Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_landing_lead'])) {
    $is_ajax = isset($_POST['is_ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);
    
    $csrf = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($csrf)) {
        $form_status = 'error';
        $error_message = 'Security validation failed. Please refresh and try again.';
    } else {
        $parent_name = trim($_POST['parent_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $grade = trim($_POST['grade'] ?? '');
        
        $submitted_data = compact('parent_name', 'phone', 'email', 'grade');

        if (empty($parent_name) || empty($phone) || empty($email) || empty($grade)) {
            $form_status = 'error';
            $error_message = 'Please fill in all required fields (Parent’s Name, Phone, Email, and Grade).';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_status = 'error';
            $error_message = 'Please enter a valid email address.';
        } elseif (strlen(preg_replace('/[^0-9]/', '', $phone)) < 8) {
            $form_status = 'error';
            $error_message = 'Please enter a valid phone number.';
        } else {
            $message = 'Landing Page Enquiry: Book a Free Demo for ' . $grade;
            $persisted = false;

            // 1. Insert into persistent database if connection active
            if (isset($db) && $db) {
                try {
                    $stmt = $db->prepare("
                        INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                        VALUES (?, ?, ?, ?, ?, ?, 'Landing Page', 1)
                    ");
                    $stmt->execute([
                        $parent_name,
                        $parent_name . ' (Student)',
                        $grade,
                        $phone,
                        $email,
                        $message
                    ]);
                    $persisted = true;
                } catch (Exception $e) {
                    error_log("[Landing Page DB Error] " . $e->getMessage());
                }
            }

            // 2. Always persist into mock enquiries session so lead is visible in CRM under all conditions
            if (!isset($_SESSION['mock_enquiries'])) {
                $_SESSION['mock_enquiries'] = [];
            }
            $_SESSION['mock_enquiries'][] = [
                'id' => count($_SESSION['mock_enquiries']) + 1001,
                'parent_name' => $parent_name,
                'student_name' => $parent_name . ' (Student)',
                'grade' => $grade,
                'phone' => $phone,
                'email' => $email,
                'message' => $message,
                'source' => 'Landing Page',
                'status_id' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $form_status = 'success';
        }
    }

    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        if ($form_status === 'success') {
            echo json_encode([
                'status' => 'success',
                'message' => 'Thank you! Your demo request has been received. Our academic counsellor will connect with you shortly.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $error_message ?: 'Failed to submit form.'
            ]);
        }
        exit;
    }
}

// SEO Metadata
$seo_title = "Zuvio Global School — 100% Online School for Nursery to Grade 8";
$meta_desc = "Flexible. Personalised. Future-Ready 100% online schooling for Nursery to Grade 8. Live interactive classes, CBSE & Oxford curriculum, and holistic global learning.";
$canonical_url = (defined('BASE_URL') ? BASE_URL : '') . "/landing-page/";
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($seo_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
  <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($seo_title); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($meta_desc); ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
  
  <link rel="icon" type="image/png" href="/assets/images/logo.png">
  <link rel="stylesheet" href="/css/landing-page.css?v=<?php echo time(); ?>">
</head>
<body class="zuvio-landing-page">

  <!-- ==========================================================================
       A. HEADER
       ========================================================================== -->
  <header class="lp-header" id="top">
    <div class="lp-header-container">
      <div class="lp-header-inner">
        <!-- Logo -->
        <a href="/landing-page/" class="lp-logo-link" aria-label="Zuvio Global School">
          <img src="/assets/images/logo.png" alt="Zuvio Global School — Learning Beyond Boundaries" class="lp-header-logo" width="220" height="60">
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="lp-nav-menu" aria-label="Primary Navigation">
          <a href="/about" class="lp-nav-item">About</a>
          <a href="#who-is-zuvio" class="lp-nav-item">Why Zuvio</a>
          <a href="#how-it-works" class="lp-nav-item">Academics</a>
          <a href="#curriculum" class="lp-nav-item">Admissions</a>
          <a href="/faqs" class="lp-nav-item">FAQs</a>
        </nav>

        <!-- Right CTA Button & Mobile Toggle -->
        <div class="lp-header-actions">
          <a href="#hero-form" class="lp-btn-cta-pill lp-scroll-btn">Book a Free Demo</a>
          <button class="lp-hamburger-btn" id="lp-menu-btn" aria-label="Toggle Navigation">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div class="lp-mobile-nav" id="lp-mobile-nav">
      <a href="/about" class="lp-mobile-nav-link">About</a>
      <a href="#who-is-zuvio" class="lp-mobile-nav-link">Why Zuvio</a>
      <a href="#how-it-works" class="lp-mobile-nav-link">Academics</a>
      <a href="#curriculum" class="lp-mobile-nav-link">Admissions</a>
      <a href="/faqs" class="lp-mobile-nav-link">FAQs</a>
      <a href="#hero-form" class="lp-btn-cta-pill lp-scroll-btn" style="text-align: center; margin-top: 10px;">Book a Free Demo</a>
    </div>
  </header>

  <!-- ==========================================================================
       B. HERO SECTION
       ========================================================================== -->
  <section class="lp-hero">
    <div class="lp-section-container lp-hero-container">
      
      <!-- Left Hero Content -->
      <div class="lp-hero-left">
        <h1 class="lp-hero-heading">
          A School That Sees<br><span class="lp-color-cyan">Beyond Marks.</span>
        </h1>
        
        <p class="lp-hero-tagline">Flexible. Personalised. Future-Ready.</p>
        <p class="lp-hero-grades">100% Online School for Nursery to Grade 8</p>

        <!-- 3 Benefit Bullets (Horizontal) -->
        <div class="lp-hero-benefits-row">
          <div class="lp-hero-benefit">
            <span class="lp-benefit-circle lp-circle-navy">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </span>
            <span class="lp-benefit-text">Live Interactive Classes</span>
          </div>

          <div class="lp-hero-benefit">
            <span class="lp-benefit-circle lp-circle-yellow">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"></path></svg>
            </span>
            <span class="lp-benefit-text">Holistic Development</span>
          </div>

          <div class="lp-hero-benefit">
            <span class="lp-benefit-circle lp-circle-navy">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
            </span>
            <span class="lp-benefit-text">Global Learning Community</span>
          </div>
        </div>

        <!-- Yellow Counselling CTA Button -->
        <div class="lp-hero-cta-wrap">
          <a href="#hero-form" class="lp-btn-counselling lp-scroll-btn">
            <span>Book a Free Counselling</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </a>
        </div>
      </div>

      <!-- Center Child Learning Visual -->
      <div class="lp-hero-center">
        <img src="/assets/images/landing-page/LP-IMAGE-01.png" alt="Student learning online with headphones and laptop at Zuvio Global School" class="lp-hero-child-img" width="460" height="420" loading="eager">
      </div>

      <!-- Right Navy Enquiry Form Card -->
      <div class="lp-hero-right">
        <div class="lp-enquiry-card" id="hero-form">
          <h2 class="lp-enquiry-title">Start Your Child’s<br>Learning Journey</h2>

          <div id="lp-form-feedback">
            <?php if ($form_status === 'success'): ?>
              <div class="lp-alert lp-alert-success">
                ✓ Thank you! Your demo request has been received. Our academic counsellor will connect with you shortly.
              </div>
            <?php elseif ($form_status === 'error'): ?>
              <div class="lp-alert lp-alert-error">
                ✕ <?php echo htmlspecialchars($error_message); ?>
              </div>
            <?php endif; ?>
          </div>

          <form action="/landing-page/#hero-form" method="POST" id="lp-enquiry-form" class="lp-form-element">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <input type="hidden" name="submit_landing_lead" value="1">

            <!-- Field 1: Parent's Name -->
            <div class="lp-input-group">
              <span class="lp-input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              </span>
              <input type="text" name="parent_name" id="parent_name" class="lp-input-control" placeholder="Parent’s Name" required value="<?php echo htmlspecialchars($submitted_data['parent_name'] ?? ''); ?>">
            </div>

            <!-- Field 2: Phone Number -->
            <div class="lp-input-group">
              <span class="lp-input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-2.2 2.2a15.05 15.05 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1.01A11.36 11.36 0 0 1 8.5 3.99c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.49c0-.55-.45-1-.99-1.12z"/></svg>
              </span>
              <input type="tel" name="phone" id="phone" class="lp-input-control" placeholder="Phone Number" required value="<?php echo htmlspecialchars($submitted_data['phone'] ?? ''); ?>">
            </div>

            <!-- Field 3: Email Address -->
            <div class="lp-input-group">
              <span class="lp-input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
              </span>
              <input type="email" name="email" id="email" class="lp-input-control" placeholder="Email Address" required value="<?php echo htmlspecialchars($submitted_data['email'] ?? ''); ?>">
            </div>

            <!-- Field 4: Child's Grade -->
            <div class="lp-input-group">
              <span class="lp-input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
              </span>
              <select name="grade" id="grade" class="lp-input-control lp-select-control" required>
                <option value="">Child’s Grade</option>
                <?php
                $grade_options = ['Nursery', 'LKG', 'UKG', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8'];
                $sel = $submitted_data['grade'] ?? '';
                foreach ($grade_options as $opt) {
                    $isSelected = ($sel === $opt) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($opt) . "\" {$isSelected}>" . htmlspecialchars($opt) . "</option>";
                }
                ?>
              </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="lp-btn-form-submit" id="lp-form-submit">
              <span>Book a Free Demo</span>
            </button>

            <!-- Footnote -->
            <p class="lp-form-footnote">No commitment. Just a conversation.</p>
          </form>
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================================================
       C. LEARNING PHILOSOPHY (Horizontal Layout)
       ========================================================================== -->
  <section class="lp-philosophy" id="learning-philosophy">
    <div class="lp-section-container lp-philosophy-container">
      
      <!-- Left: Heading & Description -->
      <div class="lp-philosophy-left">
        <h2 class="lp-philosophy-title">Every Child Learns Differently.</h2>
        <p class="lp-philosophy-subtitle">
          We nurture curiosity, build confidence and inspire children<br>
          <span class="lp-stars-wrapper">• to explore their true potential. •</span>
        </p>
      </div>

      <!-- Right: KNOW -> THINK -> CREATE (Horizontal Sequence) -->
      <div class="lp-philosophy-right">
        <!-- Item 1: KNOW -->
        <div class="lp-journey-step">
          <div class="lp-journey-circle lp-circle-cyan">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-2.04z"/></svg>
          </div>
          <span class="lp-journey-name">KNOW</span>
          <span class="lp-journey-desc">Build strong foundations</span>
        </div>

        <!-- Arrow -->
        <div class="lp-journey-arrow">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </div>

        <!-- Item 2: THINK -->
        <div class="lp-journey-step">
          <div class="lp-journey-circle lp-circle-gold">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>
          </div>
          <span class="lp-journey-name">THINK</span>
          <span class="lp-journey-desc">Develop understanding</span>
        </div>

        <!-- Arrow -->
        <div class="lp-journey-arrow">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </div>

        <!-- Item 3: CREATE -->
        <div class="lp-journey-step">
          <div class="lp-journey-circle lp-circle-green">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>
          </div>
          <span class="lp-journey-name">CREATE</span>
          <span class="lp-journey-desc">Turn ideas into possibilities</span>
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================================================
       D. WHO IS ZUVIO FOR? (Full-width light blue, 6 cards in one row)
       ========================================================================== -->
  <section class="lp-audience" id="who-is-zuvio">
    <div class="lp-section-container lp-audience-container">
      
      <!-- Left Column: Title & Subtitle -->
      <div class="lp-audience-left">
        <h2 class="lp-audience-title">Who is<br>Zuvio For?</h2>
        <p class="lp-audience-subtitle">A flexible learning option for every kind of learner.</p>
      </div>

      <!-- Right Column: 6 Horizontal Cards in One Row -->
      <div class="lp-audience-row">
        <!-- Card 1 -->
        <div class="lp-audience-card">
          <div class="lp-card-thumb">
            <img src="/assets/images/landing-page/LP-IMAGE-02.png" alt="Homeschooling Families" loading="lazy">
          </div>
          <p class="lp-card-label">Homeschooling Families</p>
        </div>

        <!-- Card 2 -->
        <div class="lp-audience-card">
          <div class="lp-card-thumb">
            <img src="/assets/images/landing-page/LP-IMAGE-03.png" alt="Young Athletes" loading="lazy">
          </div>
          <p class="lp-card-label">Young Athletes</p>
        </div>

        <!-- Card 3 -->
        <div class="lp-audience-card">
          <div class="lp-card-thumb">
            <img src="/assets/images/landing-page/LP-IMAGE-04.png" alt="Frequent Travellers" loading="lazy">
          </div>
          <p class="lp-card-label">Frequent Travellers</p>
        </div>

        <!-- Card 4 -->
        <div class="lp-audience-card">
          <div class="lp-card-thumb">
            <img src="/assets/images/landing-page/LP-IMAGE-05.png" alt="Children with Diverse Learning Needs" loading="lazy">
          </div>
          <p class="lp-card-label">Children with Diverse Learning Needs</p>
        </div>

        <!-- Card 5 -->
        <div class="lp-audience-card">
          <div class="lp-card-thumb">
            <img src="/assets/images/landing-page/LP-IMAGE-06.png" alt="Remote Location Learners" loading="lazy">
          </div>
          <p class="lp-card-label">Remote Location Learners</p>
        </div>

        <!-- Card 6 -->
        <div class="lp-audience-card">
          <div class="lp-card-thumb">
            <img src="/assets/images/landing-page/LP-IMAGE-07.png" alt="Every Child Who Deserves a Flexible, Future-Ready Education" loading="lazy">
          </div>
          <p class="lp-card-label">Every Child Who Deserves a Flexible, Future-Ready Education</p>
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================================================
       E. HOW ONLINE LEARNING WORKS (Full-width dark blue, horizontal process)
       ========================================================================== -->
  <section class="lp-how-works" id="how-it-works">
    <div class="lp-section-container lp-how-container">
      
      <!-- Left: Child Image with Globe & Laptop -->
      <div class="lp-how-left">
        <img src="/assets/images/landing-page/LP-IMAGE-08.png" alt="How Online Learning Works" class="lp-how-child-img" width="380" height="340" loading="lazy">
      </div>

      <!-- Right: Heading + 4 Horizontal Process Steps -->
      <div class="lp-how-right">
        <h2 class="lp-how-title">How Online Learning Works</h2>
        <p class="lp-how-subtitle">A structured and supportive learning experience.</p>

        <!-- 4 Steps Connected by Horizontal Dotted Lines -->
        <div class="lp-how-steps-row">
          <!-- Step 1 -->
          <div class="lp-how-step-item">
            <div class="lp-step-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            </div>
            <p class="lp-step-text">Live<br>Teacher-Led<br>Classes</p>
          </div>

          <!-- Dotted Connector -->
          <div class="lp-step-connector"></div>

          <!-- Step 2 -->
          <div class="lp-how-step-item">
            <div class="lp-step-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <p class="lp-step-text">Small & Engaging<br>Group Learning</p>
          </div>

          <!-- Dotted Connector -->
          <div class="lp-step-connector"></div>

          <!-- Step 3 -->
          <div class="lp-how-step-item">
            <div class="lp-step-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <p class="lp-step-text">Interactive<br>Activities & Projects</p>
          </div>

          <!-- Dotted Connector -->
          <div class="lp-step-connector"></div>

          <!-- Step 4 -->
          <div class="lp-how-step-item">
            <div class="lp-step-circle">
              <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            </div>
            <p class="lp-step-text">Continuous<br>Feedback & Support</p>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- ==========================================================================
       F. EDUCATION AND CURRICULUM (Horizontal strip)
       ========================================================================== -->
  <section class="lp-curriculum" id="curriculum">
    <div class="lp-section-container lp-curriculum-container">
      
      <!-- Left: Title & Subtitle -->
      <div class="lp-curriculum-left">
        <h2 class="lp-curriculum-title">A Well-Rounded Education</h2>
        <p class="lp-curriculum-subtitle">
          CBSE-Mapped Curriculum | Oxford Advantage | NIOS Pathways with Global Learning Opportunities
        </p>
      </div>

      <!-- Right: Logos Strip with Vertical Dividers -->
      <div class="lp-curriculum-right">
        <!-- Partner 1 -->
        <div class="lp-partner-item">
          <img src="/assets/images/landing-page/logo-cbse.png" alt="CBSE" class="lp-partner-logo">
          <span class="lp-partner-caption">Mapped Curriculum</span>
        </div>

        <div class="lp-partner-divider"></div>

        <!-- Partner 2 -->
        <div class="lp-partner-item">
          <img src="/assets/images/landing-page/logo-oxford.png" alt="Oxford" class="lp-partner-logo">
          <span class="lp-partner-caption">Advantage Curriculum</span>
        </div>

        <div class="lp-partner-divider"></div>

        <!-- Partner 3 -->
        <div class="lp-partner-item">
          <img src="/assets/images/landing-page/logo-nios.png" alt="NIOS" class="lp-partner-logo">
          <span class="lp-partner-caption">Curriculum Support</span>
        </div>

        <div class="lp-partner-divider"></div>

        <!-- Partner 4 -->
        <div class="lp-partner-item">
          <img src="/assets/images/landing-page/logo-ibm.png" alt="IBM" class="lp-partner-logo">
          <span class="lp-partner-caption">Certified Young Innovators</span>
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================================================
       G. PARENTS SPEAK (Left header, right 3 cards)
       ========================================================================== -->
  <section class="lp-testimonials" id="testimonials">
    <div class="lp-section-container lp-testimonials-container">
      
      <!-- Left: Heading & Subtitle -->
      <div class="lp-testimonials-left">
        <h2 class="lp-testimonials-title">Parents Speak</h2>
        <p class="lp-testimonials-subtitle">Real experiences. Real stories.</p>
      </div>

      <!-- Right: 3 Testimonial Cards in Horizontal Row -->
      <div class="lp-testimonials-row">
        <!-- Testimonial 1 -->
        <div class="lp-testimonial-card">
          <div class="lp-card-quote-mark">“</div>
          <p class="lp-card-quote-text">
            Zuvio has given my child the confidence to explore his interests while maintaining strong academics.
          </p>
          <p class="lp-card-author">– Parent of Grade 4 Student</p>
        </div>

        <!-- Testimonial 2 -->
        <div class="lp-testimonial-card">
          <div class="lp-card-quote-mark">“</div>
          <p class="lp-card-quote-text">
            The teachers are supportive and the learning experience is truly engaging. My child looks forward to every class!
          </p>
          <p class="lp-card-author">– Parent of Grade 2 Student</p>
        </div>

        <!-- Testimonial 3 -->
        <div class="lp-testimonial-card">
          <div class="lp-card-quote-mark">“</div>
          <p class="lp-card-quote-text">
            A flexible and global learning environment that understands my child’s unique needs.
          </p>
          <p class="lp-card-author">– Parent of Grade 6 Student</p>
        </div>
      </div>

    </div>
  </section>

  <!-- ==========================================================================
       H. FINAL CTA SECTION (Full-width dark blue, waves, left copy, child right)
       ========================================================================== -->
  <section class="lp-final-cta" id="final-cta">
    <!-- Decorative Wave Top -->
    <div class="lp-wave-top">
      <svg viewBox="0 0 1200 60" preserveAspectRatio="none">
        <path d="M0,0 C300,50 600,10 900,45 C1050,60 1150,30 1200,40 L1200,60 L0,60 Z" fill="#013150" opacity="0.3"></path>
        <path d="M0,15 C250,55 550,20 850,50 C1000,65 1100,35 1200,48 L1200,60 L0,60 Z" fill="#013150"></path>
      </svg>
    </div>

    <div class="lp-section-container lp-final-cta-container">
      
      <!-- Left Content -->
      <div class="lp-final-cta-left">
        <h2 class="lp-final-cta-heading">
          Your Child’s Future Begins<br>with a Conversation.
        </h2>
        <p class="lp-final-cta-subtext">
          Discover a learning environment designed around<br>curiosity, confidence and growth.
        </p>
      </div>

      <!-- Middle: Button & Phone Call -->
      <div class="lp-final-cta-center">
        <a href="#hero-form" class="lp-btn-cta-pill lp-scroll-btn">
          <span>Book a Free Demo</span>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
        <div class="lp-cta-or-call">or Call <a href="tel:+917827262956">+91 7827262956</a></div>
      </div>

      <!-- Right Smiling Boy Visual with Stars -->
      <div class="lp-final-cta-right">
        <!-- Floating Stars -->
        <span class="lp-star lp-star-1">★</span>
        <span class="lp-star lp-star-2">✦</span>
        <span class="lp-star lp-star-3">★</span>
        <span class="lp-star lp-star-4">★</span>
        <img src="/assets/images/landing-page/LP-IMAGE-09.png" alt="Student with backpack and globe" class="lp-final-child-img" width="340" height="340" loading="lazy">
      </div>

    </div>

    <!-- Decorative Wave Bottom -->
    <div class="lp-wave-bottom">
      <svg viewBox="0 0 1200 40" preserveAspectRatio="none">
        <path d="M0,0 C300,35 700,5 1200,30 L1200,40 L0,40 Z" fill="#00223b"></path>
      </svg>
    </div>
  </section>

  <!-- ==========================================================================
       I. FOOTER (Horizontal single contact strip)
       ========================================================================== -->
  <footer class="lp-footer" id="footer">
    <div class="lp-section-container lp-footer-container">
      
      <div class="lp-footer-strip">
        <!-- Phone -->
        <a href="tel:+917827262956" class="lp-footer-contact-item">
          <span class="lp-footer-circle">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-2.2 2.2a15.05 15.05 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1.01A11.36 11.36 0 0 1 8.5 3.99c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.49c0-.55-.45-1-.99-1.12z"/></svg>
          </span>
          <span class="lp-footer-label">+91 7827262956</span>
        </a>

        <div class="lp-footer-divider"></div>

        <!-- Email -->
        <a href="mailto:info@zuvioglobalschool.com" class="lp-footer-contact-item">
          <span class="lp-footer-circle">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
          </span>
          <span class="lp-footer-label">info@zuvioglobalschool.com</span>
        </a>

        <div class="lp-footer-divider"></div>

        <!-- Website -->
        <a href="https://zuvioglobalschool.com" class="lp-footer-contact-item" target="_blank" rel="noopener">
          <span class="lp-footer-circle">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
          </span>
          <span class="lp-footer-label">zuvioglobalschool.com</span>
        </a>
      </div>

    </div>
  </footer>

  <!-- ==========================================================================
       JAVASCRIPT
       ========================================================================== -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // 1. Mobile Menu Toggle
      const menuBtn = document.getElementById('lp-menu-btn');
      const navDrawer = document.getElementById('lp-mobile-nav');
      if (menuBtn && navDrawer) {
        menuBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          navDrawer.classList.toggle('is-open');
        });
        document.addEventListener('click', function(e) {
          if (!navDrawer.contains(e.target) && !menuBtn.contains(e.target)) {
            navDrawer.classList.remove('is-open');
          }
        });
      }

      // 2. Smooth Scroll to Hero Form
      const scrollButtons = document.querySelectorAll('.lp-scroll-btn');
      scrollButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (navDrawer) navDrawer.classList.remove('is-open');
          const target = document.getElementById('hero-form');
          if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
              const input = document.getElementById('parent_name');
              if (input) input.focus();
            }, 600);
          }
        });
      });

      // 3. AJAX Form Submission
      const form = document.getElementById('lp-enquiry-form');
      const feedback = document.getElementById('lp-form-feedback');
      const submitBtn = document.getElementById('lp-form-submit');

      if (form && feedback && submitBtn) {
        form.addEventListener('submit', function(e) {
          const name = document.getElementById('parent_name').value.trim();
          const phone = document.getElementById('phone').value.trim();
          const email = document.getElementById('email').value.trim();
          const grade = document.getElementById('grade').value.trim();

          if (!name || !phone || !email || !grade) {
            feedback.innerHTML = '<div class="lp-alert lp-alert-error">✕ Please fill in all fields.</div>';
            return;
          }

          e.preventDefault();
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span>Submitting...</span>';

          const fd = new FormData(form);
          fd.append('is_ajax', '1');

          fetch('/landing-page/', {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              feedback.innerHTML = '<div class="lp-alert lp-alert-success">✓ ' + data.message + '</div>';
              form.reset();
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<span>Booked Successfully!</span>';
              setTimeout(() => {
                submitBtn.innerHTML = '<span>Book a Free Demo</span>';
              }, 4000);
            } else {
              feedback.innerHTML = '<div class="lp-alert lp-alert-error">✕ ' + (data.message || 'Submission error.') + '</div>';
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<span>Book a Free Demo</span>';
            }
          })
          .catch(err => {
            console.error(err);
            form.submit(); // fallback
          });
        });
      }
    });
  </script>

  <!-- BotPenguin WhatsApp Chatbot integration -->
  <script id="bp-wa-script" src="https://cdn.botpenguin.com/whatsapp-widget.js">19071980-ca0b-4e77-b150-82fb4f79e964</script>

</body>
</html>
