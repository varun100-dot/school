<?php
// Zuvio Global School — Nursery to Grade 12 Standalone Landing Page
// Route: /nursery-to-grade-12/

require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$form_status = 'idle';
$form_message = '';
$submitted_data = [];

// Form submission handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_n12_lead'])) {
    $is_ajax = isset($_POST['is_ajax']) || 
               (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
               (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);

    // Spam honeypot check
    if (!empty($_POST['website_hp'])) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Your request has been received.']);
            exit;
        }
        $form_status = 'success';
        $form_message = 'Your request has been received.';
    } else {
        $csrf = $_POST['csrf_token'] ?? '';
        if (!validate_csrf_token($csrf)) {
            $form_status = 'error';
            $form_message = 'Security token invalid or expired. Please refresh the page and try again.';
        } else {
            $parent_name  = trim($_POST['parent_name'] ?? '');
            $phone        = trim($_POST['phone'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $student_name = trim($_POST['student_name'] ?? '');
            $grade        = trim($_POST['grade'] ?? '');

            $submitted_data = compact('parent_name', 'phone', 'email', 'student_name', 'grade');

            if (empty($parent_name) || empty($phone) || empty($email) || empty($student_name) || empty($grade)) {
                $form_status = 'error';
                $form_message = 'Please fill in all required fields to book your free demo.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $form_status = 'error';
                $form_message = 'Please provide a valid email address.';
            } elseif (strlen(preg_replace('/[^0-9]/', '', $phone)) < 8) {
                $form_status = 'error';
                $form_message = 'Please provide a valid phone number.';
            } else {
                $lead_source = 'Nursery to Grade 12 LP';
                $message_text = "Nursery to Grade 12 Demo Booking — Child: {$student_name}, Grade: {$grade}";

                // 1. Insert into persistent database if connection active
                if (isset($db) && $db) {
                    try {
                        $stmt = $db->prepare("
                            INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                            VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                        ");
                        $stmt->execute([
                            $parent_name,
                            $student_name,
                            $grade,
                            $phone,
                            $email,
                            $message_text,
                            $lead_source
                        ]);
                    } catch (Exception $e) {
                        error_log("[N12 LP DB Error] " . $e->getMessage());
                    }
                }

                // 2. Fallback mock session store for preview resiliency
                if (!isset($_SESSION['mock_enquiries'])) {
                    $_SESSION['mock_enquiries'] = [];
                }
                $_SESSION['mock_enquiries'][] = [
                    'id'           => time(),
                    'parent_name'  => $parent_name,
                    'student_name' => $student_name,
                    'grade'        => $grade,
                    'phone'        => $phone,
                    'email'        => $email,
                    'message'      => $message_text,
                    'source'       => $lead_source,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'status'       => 'new'
                ];

                $form_status = 'success';
                $form_message = 'Thank you! Your free demo class has been booked. Our academic counsellor will connect with you within 24 hours.';
                $submitted_data = []; // Clear inputs on success
            }
        }

        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => $form_status,
                'message' => $form_message
            ]);
            exit;
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nursery to Grade 12 Online School | Zuvio Global School</title>
  <meta name="description" content="Bharat ka No. 1 Online School — Live interactive classes, 15:1 student-teacher ratio, CBSE-mapped curriculum from Nursery to Grade 12. Book a free demo class today.">
  <link rel="canonical" href="https://zuvioglobalschool.com/nursery-to-grade-12/">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/images/logo.png">

  <!-- Google Fonts: Playfair Display (Serif), Inter (Body), Caveat (Handwritten) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

  <!-- Dedicated Isolated Stylesheet -->
  <link rel="stylesheet" href="/css/nursery-to-grade-12.css">
</head>
<body class="n12-page">

  <!-- ========================================================================
       00. CUSTOM HEADER
       ======================================================================== -->
  <header class="n12-header" id="n12-top-header">
    <div class="n12-container">
      <div class="n12-header-inner">
        <!-- Logo -->
        <a href="/" class="n12-logo-link" title="Zuvio Global School">
          <img src="/assets/images/logo.png" alt="Zuvio Global School Logo" class="n12-logo-img">
        </a>

        <!-- Center Navigation -->
        <nav class="n12-nav">
          <ul class="n12-nav-menu">
            <li><a href="/" class="n12-nav-link">Home</a></li>
            <li><a href="#why-zuvio" class="n12-nav-link">Why Zuvio</a></li>
            <li><a href="#academics" class="n12-nav-link">Academics</a></li>
            <li><a href="#every-learner" class="n12-nav-link">Beyond</a></li>
            <li><a href="/admissions/" class="n12-nav-link">Admissions</a></li>
            <li><a href="/faqs/" class="n12-nav-link">FAQs</a></li>
          </ul>
        </nav>

        <!-- Right CTA Button -->
        <a href="#hero-form" class="n12-header-cta">
          <span>Book a Free Demo</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12"></line>
            <polyline points="12 5 19 12 12 19"></polyline>
          </svg>
        </a>

        <!-- Mobile Hamburger Toggle -->
        <button class="n12-hamburger" id="n12HamburgerBtn" aria-label="Toggle navigation menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div class="n12-mobile-drawer" id="n12MobileDrawer">
      <ul class="n12-mobile-menu">
        <li><a href="/" class="n12-mobile-link">Home</a></li>
        <li><a href="#why-zuvio" class="n12-mobile-link">Why Zuvio</a></li>
        <li><a href="#academics" class="n12-mobile-link">Academics</a></li>
        <li><a href="#every-learner" class="n12-mobile-link">Beyond</a></li>
        <li><a href="/admissions/" class="n12-mobile-link">Admissions</a></li>
        <li><a href="/faqs/" class="n12-mobile-link">FAQs</a></li>
      </ul>
      <a href="#hero-form" class="n12-mobile-cta">Book a Free Demo Class</a>
    </div>
  </header>

  <!-- ========================================================================
       01. HERO BANNER
       ======================================================================== -->
  <section class="n12-hero-section" id="hero">
    <!-- Background Tiranga & Student Artwork -->
    <div class="n12-hero-backdrop" aria-hidden="true">
      <img src="/assets/images/nursery-to-grade-12/hero-tiranga-student.webp" alt="Indian Student Learning Online with Tiranga" class="n12-hero-tiranga-img">
    </div>

    <div class="n12-container">
      <div class="n12-hero-layout">
        
        <!-- Left Hero Content -->
        <div class="n12-hero-content">
          <div class="n12-hero-badge">
            NURSERY TO GRADE 12
          </div>
          
          <h1 class="n12-hero-title">
            Bharat ka<br><span>No. 1</span> Online School
          </h1>

          <p class="n12-hero-sub">
            Live classes. Personal attention. Future-ready learning.
          </p>

          <p class="n12-hero-desc">
            A CBSE-mapped online school with 15:1 student–teacher ratio, expert educators and a safe, flexible learning environment — from the comfort of home.
          </p>

          <!-- Hero Action CTAs -->
          <div class="n12-hero-actions">
            <a href="#hero-form" class="n12-btn-primary">
              <span>Book a Free Demo Class</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
            <a href="tel:+918073867623" class="n12-btn-secondary">
              <span>Talk to an Expert</span>
            </a>
          </div>

          <!-- Bottom 4 Stats Strip with Vertical Dividers (nich icon with text) -->
          <div class="n12-hero-stats-strip">
            <!-- Stat 1: 15:1 Ratio -->
            <div class="n12-hero-stat-item">
              <div class="n12-stat-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                </svg>
              </div>
              <div class="n12-stat-title">15:1</div>
              <div class="n12-stat-sub">Student-Teacher<br>Ratio</div>
            </div>

            <div class="n12-stat-divider"></div>

            <!-- Stat 2: Affordable Fee -->
            <div class="n12-hero-stat-item">
              <div class="n12-stat-icon">
                <span class="n12-rupee-symbol">₹</span>
              </div>
              <div class="n12-stat-title">Affordable</div>
              <div class="n12-stat-sub">Fee Structure</div>
            </div>

            <div class="n12-stat-divider"></div>

            <!-- Stat 3: Quality Education -->
            <div class="n12-hero-stat-item">
              <div class="n12-stat-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
              </div>
              <div class="n12-stat-title">Quality</div>
              <div class="n12-stat-sub">Education<br>at Home</div>
            </div>

            <div class="n12-stat-divider"></div>

            <!-- Stat 4: Travel-Free -->
            <div class="n12-hero-stat-item">
              <div class="n12-stat-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.04 3H5.81l1.04-3zM19 17H5v-4.66l.12-.34h13.77l.11.34V17z"/>
                  <circle cx="7.5" cy="14.5" r="1.5"/>
                  <circle cx="16.5" cy="14.5" r="1.5"/>
                  <line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="n12-stat-title">Travel-Free</div>
              <div class="n12-stat-sub">Stress-Free<br>Learning</div>
            </div>
          </div>
        </div>

        <!-- Right Hero Demo Form Card -->
        <div class="n12-hero-form-wrapper">
          <div class="n12-hero-form-card" id="hero-form">
            <div class="n12-hero-form-header">
              <h2 class="n12-form-title">Book a Free Demo Class</h2>
              <p class="n12-form-subtitle">See how your child can learn, grow and thrive with Zuvio.</p>
            </div>

            <div id="n12FormNotice" class="n12-form-message <?= $form_status === 'success' ? 'success' : ($form_status === 'error' ? 'error' : '') ?>">
              <?= htmlspecialchars($form_message) ?>
            </div>

            <form action="/nursery-to-grade-12/#hero-form" method="POST" id="n12LeadForm" novalidate>
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
              <!-- Honeypot -->
              <input type="text" name="website_hp" style="display:none !important;" tabindex="-1" autocomplete="off">

              <div class="n12-form-group">
                <input type="text" class="n12-form-input" id="parent_name" name="parent_name" placeholder="Parent Name" required value="<?= htmlspecialchars($submitted_data['parent_name'] ?? '') ?>">
              </div>

              <div class="n12-form-group">
                <input type="tel" class="n12-form-input" id="phone" name="phone" placeholder="Phone Number" required value="<?= htmlspecialchars($submitted_data['phone'] ?? '') ?>">
              </div>

              <div class="n12-form-group">
                <input type="email" class="n12-form-input" id="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($submitted_data['email'] ?? '') ?>">
              </div>

              <div class="n12-form-group">
                <input type="text" class="n12-form-input" id="student_name" name="student_name" placeholder="Child's Name" required value="<?= htmlspecialchars($submitted_data['student_name'] ?? '') ?>">
              </div>

              <div class="n12-form-group">
                <div class="n12-select-wrap">
                  <select class="n12-form-select" id="grade" name="grade" required>
                    <option value="">Select Grade</option>
                    <option value="Nursery" <?= ($submitted_data['grade'] ?? '') === 'Nursery' ? 'selected' : '' ?>>Nursery</option>
                    <option value="LKG" <?= ($submitted_data['grade'] ?? '') === 'LKG' ? 'selected' : '' ?>>LKG</option>
                    <option value="UKG" <?= ($submitted_data['grade'] ?? '') === 'UKG' ? 'selected' : '' ?>>UKG</option>
                    <option value="Grade 1" <?= ($submitted_data['grade'] ?? '') === 'Grade 1' ? 'selected' : '' ?>>Grade 1</option>
                    <option value="Grade 2" <?= ($submitted_data['grade'] ?? '') === 'Grade 2' ? 'selected' : '' ?>>Grade 2</option>
                    <option value="Grade 3" <?= ($submitted_data['grade'] ?? '') === 'Grade 3' ? 'selected' : '' ?>>Grade 3</option>
                    <option value="Grade 4" <?= ($submitted_data['grade'] ?? '') === 'Grade 4' ? 'selected' : '' ?>>Grade 4</option>
                    <option value="Grade 5" <?= ($submitted_data['grade'] ?? '') === 'Grade 5' ? 'selected' : '' ?>>Grade 5</option>
                    <option value="Grade 6" <?= ($submitted_data['grade'] ?? '') === 'Grade 6' ? 'selected' : '' ?>>Grade 6</option>
                    <option value="Grade 7" <?= ($submitted_data['grade'] ?? '') === 'Grade 7' ? 'selected' : '' ?>>Grade 7</option>
                    <option value="Grade 8" <?= ($submitted_data['grade'] ?? '') === 'Grade 8' ? 'selected' : '' ?>>Grade 8</option>
                    <option value="Grade 9" <?= ($submitted_data['grade'] ?? '') === 'Grade 9' ? 'selected' : '' ?>>Grade 9</option>
                    <option value="Grade 10" <?= ($submitted_data['grade'] ?? '') === 'Grade 10' ? 'selected' : '' ?>>Grade 10</option>
                    <option value="Grade 11" <?= ($submitted_data['grade'] ?? '') === 'Grade 11' ? 'selected' : '' ?>>Grade 11</option>
                    <option value="Grade 12" <?= ($submitted_data['grade'] ?? '') === 'Grade 12' ? 'selected' : '' ?>>Grade 12</option>
                  </select>
                  <svg class="n12-select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                  </svg>
                </div>
              </div>

              <button type="submit" name="submit_n12_lead" class="n12-form-btn" id="n12SubmitBtn">
                <span>Get Free Demo</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                  <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
              </button>
            </form>

            <p class="n12-form-reassurance">
              Our academic counsellor will get in touch within 24 hours.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ========================================================================
       02. WHY ZUVIO
       ======================================================================== -->
  <section class="n12-why-section" id="why-zuvio">
    <div class="n12-container">
      <div class="n12-why-grid">
        
        <!-- Left Editorial Column -->
        <div class="n12-why-editorial">
          <span class="n12-section-badge">WHY CHOOSE ZUVIO</span>
          <h2 class="n12-section-title">A Better School Day Starts at Home</h2>
          <p class="n12-section-desc">
            Zuvio combines the best of structured schooling and the freedom of home learning — giving children the right balance of academics, attention and personal growth.
          </p>
          
          <div class="n12-why-quote-box">
            <p class="n12-why-quote-text">
              “More of what your child needs.<br>Less of what they don't.”
            </p>
          </div>
        </div>

        <!-- Right 8 Benefit Cards Grid -->
        <div class="n12-why-cards">
          <!-- 1 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
            </div>
            <h3 class="n12-why-card-title">15:1 Student–Teacher Ratio</h3>
            <p class="n12-why-card-text">Every child gets the personal attention they deserve in intimate live cohorts, ensuring no concept is left behind.</p>
          </div>

          <!-- 2 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
            </div>
            <h3 class="n12-why-card-title">Affordable Fee Structure</h3>
            <p class="n12-why-card-text">High-caliber education at a fraction of traditional school fees, with zero hidden campus or infrastructure expenses.</p>
          </div>

          <!-- 3 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
              </svg>
            </div>
            <h3 class="n12-why-card-title">Quality Education at Home</h3>
            <p class="n12-why-card-text">Certified, passionate educators delivering rigorous CBSE curriculum enriched with modern multimedia tools.</p>
          </div>

          <!-- 4 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M16 12H8"></path>
                <path d="M12 16V8"></path>
              </svg>
            </div>
            <h3 class="n12-why-card-title">Travel-Free, Stress-Free Learning</h3>
            <p class="n12-why-card-text">Eliminate hours lost in traffic, dangerous pollution, and heavy school bags. Keep energy focused on learning.</p>
          </div>

          <!-- 5 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
            </div>
            <h3 class="n12-why-card-title">Flexible Schedule</h3>
            <p class="n12-why-card-text">Balanced live class hours paired with on-demand recorded archives allow children to learn at an optimal pace.</p>
          </div>

          <!-- 6 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
            </div>
            <h3 class="n12-why-card-title">Safe & Secure Environment</h3>
            <p class="n12-why-card-text">A zero-tolerance policy for bullying, monitored classrooms, and complete parental visibility into their daily day.</p>
          </div>

          <!-- 7 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
              </svg>
            </div>
            <h3 class="n12-why-card-title">Future-Ready Skills</h3>
            <p class="n12-why-card-text">Integrated coursework in coding, AI literacy, public speaking, financial wellness, and design thinking.</p>
          </div>

          <!-- 8 -->
          <div class="n12-why-card">
            <div class="n12-why-icon-wrap">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </div>
            <h3 class="n12-why-card-title">SEN Support</h3>
            <p class="n12-why-card-text">Certified special educators offering tailored accommodations, IEP guidance, and empathetic one-on-one sessions.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ========================================================================
       03. DESIGNED FOR EVERY LEARNER
       ======================================================================== -->
  <section class="n12-learner-section" id="every-learner">
    <div class="n12-container">
      <div class="n12-section-header">
        <span class="n12-section-badge">INCLUSIVE & ADAPTIVE</span>
        <h2 class="n12-section-title">Different Children. Different Dreams.</h2>
        <p class="n12-section-desc">
          Whether your child is an athlete, artist, traveller, homeschooler or simply learns at their own pace — Zuvio is designed to support their unique journey.
        </p>
        <p class="n12-cursive" style="font-size: 1.55rem; margin-top: 14px;">
          “Because every child deserves a school that sees them.”
        </p>
      </div>

      <div class="n12-learner-grid">
        <!-- Card 1: Young Athlete -->
        <div class="n12-learner-card">
          <div class="n12-learner-img-wrap">
            <img src="/assets/images/nursery-to-grade-12/athlete.webp" alt="The Young Athlete" class="n12-learner-img" loading="lazy">
          </div>
          <div class="n12-learner-body">
            <span class="n12-learner-category">SPORTS & ATHLETICS</span>
            <h3 class="n12-learner-name">The Young Athlete</h3>
            <p class="n12-learner-desc">Flexible training hours, asynchronous modules, and academic mentors so training and schooling thrive together.</p>
          </div>
        </div>

        <!-- Card 2: Creative Artist -->
        <div class="n12-learner-card">
          <div class="n12-learner-img-wrap">
            <img src="/assets/images/nursery-to-grade-12/artist.webp" alt="The Creative Artist" class="n12-learner-img" loading="lazy">
          </div>
          <div class="n12-learner-body">
            <span class="n12-learner-category">CREATIVE PURSUITS</span>
            <h3 class="n12-learner-name">The Creative Artist</h3>
            <p class="n12-learner-desc">Freedom to rehearse, paint, perform, and create without missing core foundational academic milestones.</p>
          </div>
        </div>

        <!-- Card 3: World Traveller -->
        <div class="n12-learner-card">
          <div class="n12-learner-img-wrap">
            <img src="/assets/images/nursery-to-grade-12/traveller.webp" alt="The World Traveller" class="n12-learner-img" loading="lazy">
          </div>
          <div class="n12-learner-body">
            <span class="n12-learner-category">GLOBAL FAMILIES</span>
            <h3 class="n12-learner-name">The World Traveller</h3>
            <p class="n12-learner-desc">Consistent, recognized Indian curriculum accessible from any timezone, city, or international stay.</p>
          </div>
        </div>

        <!-- Card 4: Homeschooler -->
        <div class="n12-learner-card">
          <div class="n12-learner-img-wrap">
            <img src="/assets/images/nursery-to-grade-12/homeschooler.webp" alt="The Homeschooling Family" class="n12-learner-img" loading="lazy">
          </div>
          <div class="n12-learner-body">
            <span class="n12-learner-category">HOME-BASED EDUCATION</span>
            <h3 class="n12-learner-name">The Homeschooling Family</h3>
            <p class="n12-learner-desc">Structured syllabus, certified educators, and peer socialization without sacrificing family autonomy.</p>
          </div>
        </div>

        <!-- Card 5: Thoughtful Learner -->
        <div class="n12-learner-card">
          <div class="n12-learner-img-wrap">
            <img src="/assets/images/nursery-to-grade-12/every-learner.webp" alt="The Thoughtful Learner" class="n12-learner-img" loading="lazy">
          </div>
          <div class="n12-learner-body">
            <span class="n12-learner-category">INDIVIDUAL PACE</span>
            <h3 class="n12-learner-name">The Thoughtful Learner</h3>
            <p class="n12-learner-desc">A calm, unhurried space free from peer pressure, bullying, and large crowded classroom distractions.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================================================
       04. ACADEMICS & BEYOND
       ======================================================================== -->
  <section class="n12-academics-section" id="academics">
    <div class="n12-container">
      <div class="n12-academics-grid">
        
        <!-- Left Side -->
        <div class="n12-academics-left">
          <span class="n12-section-badge">ACADEMICS & CURRICULUM</span>
          <h2 class="n12-section-title">CBSE Curriculum with a Modern Edge</h2>
          <p class="n12-section-desc">
            From Nursery foundational phonics to Grade 12 board readiness, our curriculum delivers rigorous academic standards enhanced with essential real-world capabilities.
          </p>

          <ul class="n12-academics-features">
            <li class="n12-feature-item">
              <svg class="n12-feature-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span><strong>NEP 2020 Pedagogical Framework</strong> (5+3+3+4 stage progression)</span>
            </li>
            <li class="n12-feature-item">
              <svg class="n12-feature-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span><strong>STEM & AI Integration</strong> with hands-on coding and analytical problem-solving</span>
            </li>
            <li class="n12-feature-item">
              <svg class="n12-feature-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span><strong>Formative Continuous Assessment</strong> with real-time parent progress trackers</span>
            </li>
            <li class="n12-feature-item">
              <svg class="n12-feature-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              <span><strong>Virtual Science Labs & Digital Library</strong> powered by global academic leaders</span>
            </li>
          </ul>

          <div style="margin-top: 10px;">
            <a href="/curriculum/" class="n12-btn-primary">
              <span>Explore Detailed Curriculum</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </a>
          </div>

          <div class="n12-academics-media">
            <img src="/assets/images/nursery-to-grade-12/curriculum-student.webp" alt="Student engaged in interactive learning" class="n12-academics-img" loading="lazy">
          </div>
        </div>

        <!-- Right Side: 6 Stage Cards -->
        <div class="n12-stages-grid">
          <!-- Stage 1 -->
          <div class="n12-stage-card">
            <span class="n12-stage-badge">NURSERY – GRADE 2</span>
            <h3 class="n12-stage-title">Foundational Stage</h3>
            <p class="n12-stage-text">Play-based learning, phonics immersion, early numeracy, and nurturing innate curiosity in small, cheerful batches.</p>
          </div>

          <!-- Stage 2 -->
          <div class="n12-stage-card">
            <span class="n12-stage-badge">GRADES 3 – 5</span>
            <h3 class="n12-stage-title">Preparatory Stage</h3>
            <p class="n12-stage-text">Building conceptual strength in mathematics, general science, language fluency, and healthy reading habits.</p>
          </div>

          <!-- Stage 3 -->
          <div class="n12-stage-card">
            <span class="n12-stage-badge">GRADES 6 – 8</span>
            <h3 class="n12-stage-title">Middle Stage</h3>
            <p class="n12-stage-text">Experiential learning, logical reasoning, introductory computer programming, and social sciences discovery.</p>
          </div>

          <!-- Stage 4 -->
          <div class="n12-stage-card">
            <span class="n12-stage-badge">GRADES 9 – 10</span>
            <h3 class="n12-stage-title">Secondary Stage</h3>
            <p class="n12-stage-text">Rigorous CBSE board exam preparation, in-depth subject mastery, structured mock evaluations, and career guidance.</p>
          </div>

          <!-- Stage 5 -->
          <div class="n12-stage-card">
            <span class="n12-stage-badge">GRADES 11 – 12</span>
            <h3 class="n12-stage-title">Senior Secondary</h3>
            <p class="n12-stage-text">Specialized streams (Science, Commerce, Humanities) with comprehensive competitive exam support and board success.</p>
          </div>

          <!-- Stage 6 -->
          <div class="n12-stage-card">
            <span class="n12-stage-badge">BEYOND ACADEMICS</span>
            <h3 class="n12-stage-title">Future Skills</h3>
            <p class="n12-stage-text">Artificial intelligence basics, communication masterclasses, debate clubs, entrepreneurship, and creative arts.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ========================================================================
       05. TRUST & RECOGNITION
       ======================================================================== -->
  <section class="n12-trust-section" id="trust">
    <div class="n12-container">
      <div class="n12-section-header" style="margin-bottom: 40px;">
        <span class="n12-section-badge">ACCREDITED & RECOGNISED</span>
        <h2 class="n12-section-title">Recognised. Accredited. Trusted.</h2>
        <p class="n12-section-desc">
          Zuvio Global School partners with respected international and national education bodies to ensure your child’s credentials open doors anywhere in the world.
        </p>
      </div>

      <div class="n12-trust-grid">
        <!-- Partner 1 -->
        <div class="n12-trust-card">
          <div class="n12-trust-logo-box">
            <img src="/assets/images/iao-logo.png" alt="IAO International Accreditation" class="n12-trust-logo" loading="lazy">
          </div>
          <h3 class="n12-trust-card-title">IAO Accredited</h3>
          <p class="n12-trust-card-desc">Internationally certified for academic quality and modern teaching methodologies.</p>
        </div>

        <!-- Partner 2 -->
        <div class="n12-trust-card">
          <div class="n12-trust-logo-box">
            <img src="/assets/images/landing-page/logo-cbse.png" alt="CBSE Aligned Framework" class="n12-trust-logo" loading="lazy">
          </div>
          <h3 class="n12-trust-card-title">CBSE Mapped</h3>
          <p class="n12-trust-card-desc">Curriculum mapped strictly to national NCERT and CBSE academic benchmarks.</p>
        </div>

        <!-- Partner 3 -->
        <div class="n12-trust-card">
          <div class="n12-trust-logo-box">
            <img src="/assets/images/nursery-to-grade-12/nep-2020-logo.png" alt="NEP 2020 Compliant" class="n12-trust-logo" loading="lazy">
          </div>
          <h3 class="n12-trust-card-title">NEP 2020 Aligned</h3>
          <p class="n12-trust-card-desc">Fully compliant with India’s 5+3+3+4 National Education Policy vision.</p>
        </div>

        <!-- Partner 4 -->
        <div class="n12-trust-card">
          <div class="n12-trust-logo-box">
            <img src="/assets/images/isso-logo.png" alt="ISSO Sports and Culture" class="n12-trust-logo" loading="lazy">
          </div>
          <h3 class="n12-trust-card-title">ISSO Affiliated</h3>
          <p class="n12-trust-card-desc">Member of the International Schools Sports & Cultural Organization.</p>
        </div>

        <!-- Partner 5 -->
        <div class="n12-trust-card">
          <div class="n12-trust-logo-box">
            <img src="/assets/images/oxford-logo.png" alt="Oxford Advantage Partner" class="n12-trust-logo" loading="lazy">
          </div>
          <h3 class="n12-trust-card-title">Oxford Advantage</h3>
          <p class="n12-trust-card-desc">Enriched learning materials powered by Oxford University Press curriculum resources.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================================================
       06. PARENT TESTIMONIALS
       ======================================================================== -->
  <section class="n12-testimonials-section" id="testimonials">
    <div class="n12-container">
      <div class="n12-section-header">
        <span class="n12-section-badge">FAMILY STORIES</span>
        <h2 class="n12-section-title">Loved by Parents. Cherished by Students.</h2>
        <p class="n12-section-desc">
          Read real experiences from families who made the transition to Zuvio and unlocked their child’s true potential.
        </p>
      </div>

      <div class="n12-testimonials-grid">
        <!-- Testimonial 1 -->
        <div class="n12-testimonial-card">
          <div>
            <div class="n12-testimonial-stars">★★★★★</div>
            <p class="n12-testimonial-quote">
              “Zuvio has completely transformed our mornings. Aarav gets individual attention from teachers, and his confidence has skyrocketed. He genuinely loves attending his live classes every day!”
            </p>
          </div>
          <div class="n12-testimonial-author">
            <img src="/assets/images/nursery-to-grade-12/parent-priya.png" alt="Priya Sharma" class="n12-author-avatar" loading="lazy">
            <div>
              <h4 class="n12-author-name">Priya Sharma</h4>
              <p class="n12-author-info">Mother of Aarav, Grade 4 · Bengaluru</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="n12-testimonial-card">
          <div>
            <div class="n12-testimonial-stars">★★★★★</div>
            <p class="n12-testimonial-quote">
              “As a competitive tennis player, Ananya needed school flexibility. With Zuvio, she never falls behind in academics while pursuing her athletic training. It has been truly life-changing for our family.”
            </p>
          </div>
          <div class="n12-testimonial-author">
            <img src="/assets/images/nursery-to-grade-12/parent-rahul.png" alt="Rahul Verma" class="n12-author-avatar" loading="lazy">
            <div>
              <h4 class="n12-author-name">Rahul Verma</h4>
              <p class="n12-author-info">Father of Ananya, Grade 7 (National Tennis Player) · Delhi</p>
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="n12-testimonial-card">
          <div>
            <div class="n12-testimonial-stars">★★★★★</div>
            <p class="n12-testimonial-quote">
              “The quality of educators is outstanding. The 15:1 ratio means teachers actually know Rohan's strengths and areas for improvement. Highly recommended to every caring parent who wants real attention.”
            </p>
          </div>
          <div class="n12-testimonial-author">
            <img src="/assets/images/nursery-to-grade-12/parent-neha.png" alt="Neha & Amit Kulkarni" class="n12-author-avatar" loading="lazy">
            <div>
              <h4 class="n12-author-name">Neha & Amit Kulkarni</h4>
              <p class="n12-author-info">Parents of Rohan, Grade 9 · Mumbai</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================================================
       07. FINAL CTA
       ======================================================================== -->
  <section class="n12-cta-section" id="final-cta">
    <img src="/assets/images/nursery-to-grade-12/final-cta-student.webp" alt="Inspiring Horizon" class="n12-cta-bg-image" loading="lazy">
    <div class="n12-container">
      <div class="n12-cta-container">
        <h2 class="n12-cta-title">Give Your Child the School They Deserve</h2>
        <p class="n12-cta-desc">
          Admissions open for Nursery to Grade 12. Book your free live demo class today and experience the future of personalized schooling from the comfort of home.
        </p>

        <p class="n12-cta-cursive">
          “The future of schooling is here, and it begins at home.”
        </p>

        <div class="n12-cta-actions">
          <a href="#hero-form" class="n12-btn-cta-gold">
            <span>Book a Free Demo Class</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-left:6px;">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
          <a href="tel:+918073867623" class="n12-btn-cta-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:6px;">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span>Talk to an Expert</span>
          </a>
        </div>

        <div class="n12-cta-badges">
          <span class="n12-cta-badge-item">✓ 100% Free Demo</span>
          <span class="n12-cta-badge-item">✓ 15:1 Ratio</span>
          <span class="n12-cta-badge-item">✓ CBSE Mapped</span>
          <span class="n12-cta-badge-item">✓ No Obligation</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================================================
       08. DEDICATED CUSTOM FOOTER
       ======================================================================== -->
  <footer class="n12-footer" id="footer">
    <div class="n12-container">
      <div class="n12-footer-grid">
        
        <!-- Column 1: About & Logo -->
        <div class="n12-footer-col-about">
          <a href="/" title="Zuvio Global School">
            <img src="/assets/images/logo.png" alt="Zuvio Global School" class="n12-footer-logo-img">
          </a>
          <p class="n12-footer-about-text">
            Zuvio Global School is India’s premier online school providing CBSE-aligned education from Nursery to Grade 12 with personalized attention, certified educators, and future-ready skills.
          </p>
        </div>

        <!-- Column 2: Quick Links -->
        <div>
          <h4 class="n12-footer-heading">Quick Links</h4>
          <ul class="n12-footer-links">
            <li><a href="/" class="n12-footer-link">Home</a></li>
            <li><a href="#why-zuvio" class="n12-footer-link">Why Zuvio</a></li>
            <li><a href="#academics" class="n12-footer-link">Academics</a></li>
            <li><a href="#every-learner" class="n12-footer-link">Designed for Every Learner</a></li>
            <li><a href="/admissions/" class="n12-footer-link">Admissions</a></li>
            <li><a href="/faqs/" class="n12-footer-link">Frequently Asked Questions</a></li>
          </ul>
        </div>

        <!-- Column 3: Academic Stages -->
        <div>
          <h4 class="n12-footer-heading">Curriculum Stages</h4>
          <ul class="n12-footer-links">
            <li><a href="/curriculum/" class="n12-footer-link">Foundational (Nursery – Gr 2)</a></li>
            <li><a href="/curriculum/" class="n12-footer-link">Preparatory (Grades 3 – 5)</a></li>
            <li><a href="/curriculum/" class="n12-footer-link">Middle Stage (Grades 6 – 8)</a></li>
            <li><a href="/curriculum/" class="n12-footer-link">Secondary (Grades 9 – 10)</a></li>
            <li><a href="/curriculum/" class="n12-footer-link">Senior Secondary (Gr 11 – 12)</a></li>
            <li><a href="/nep-2020/" class="n12-footer-link">NEP 2020 Alignment</a></li>
          </ul>
        </div>

        <!-- Column 4: Contact & Admissions -->
        <div>
          <h4 class="n12-footer-heading">Admissions & Contact</h4>
          <div class="n12-footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>info@zuvioglobalschool.com</span>
          </div>

          <div class="n12-footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
            </svg>
            <span>+91 80738 67623</span>
          </div>

          <div class="n12-footer-contact-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Mon – Sat: 9:00 AM – 6:00 PM IST</span>
          </div>
        </div>

      </div>

      <!-- Footer Bottom -->
      <div class="n12-footer-bottom">
        <div>
          © <?= date('Y') ?> Zuvio Global School. All rights reserved.
        </div>
        <div class="n12-footer-bottom-links">
          <a href="/privacy-policy/">Privacy Policy</a>
          <a href="/terms/">Terms of Service</a>
          <a href="/nep-2020/">NEP 2020 Framework</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ========================================================================
       CLIENT-SIDE JAVASCRIPT
       ======================================================================== -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // 1. Mobile Menu Toggle
      const hamburger = document.getElementById('n12HamburgerBtn');
      const drawer = document.getElementById('n12MobileDrawer');
      if (hamburger && drawer) {
        hamburger.addEventListener('click', function() {
          drawer.classList.toggle('is-open');
        });

        // Close on link click
        drawer.querySelectorAll('a').forEach(link => {
          link.addEventListener('click', function() {
            drawer.classList.remove('is-open');
          });
        });
      }

      // 2. Smooth Scroll for Anchor Links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          const targetId = this.getAttribute('href');
          if (targetId === '#' || targetId === '') return;
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            e.preventDefault();
            const headerOffset = 90;
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
          }
        });
      });

      // 3. AJAX Form Submission
      const form = document.getElementById('n12LeadForm');
      const notice = document.getElementById('n12FormNotice');
      const submitBtn = document.getElementById('n12SubmitBtn');

      if (form && notice && submitBtn) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();

          // Client validation
          const parentName = form.querySelector('#parent_name').value.trim();
          const phone = form.querySelector('#phone').value.trim();
          const email = form.querySelector('#email').value.trim();
          const studentName = form.querySelector('#student_name').value.trim();
          const grade = form.querySelector('#grade').value;

          if (!parentName || !phone || !email || !studentName || !grade) {
            notice.className = 'n12-form-message error';
            notice.textContent = 'Please fill in all required fields to book your free demo.';
            notice.style.display = 'block';
            return;
          }

          // Disable button
          const originalBtnHtml = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span>Booking Demo...</span>';

          const formData = new FormData(form);
          formData.append('submit_n12_lead', '1');
          formData.append('is_ajax', '1');

          fetch('/nursery-to-grade-12/#hero-form', {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.json())
          .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;

            if (data.status === 'success') {
              notice.className = 'n12-form-message success';
              notice.textContent = data.message;
              notice.style.display = 'block';
              form.reset();
            } else {
              notice.className = 'n12-form-message error';
              notice.textContent = data.message || 'An error occurred. Please try again.';
              notice.style.display = 'block';
            }
          })
          .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            // Fallback to standard form submission
            form.submit();
          });
        });
      }
    });
  </script>

</body>
</html>
