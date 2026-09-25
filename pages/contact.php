<?php
// Zuvio Global School - Dedicated Contact Page
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

// Initialize Contact CMS Mock State / Session Store
if (!isset($_SESSION['mock_contact_cms'])) {
    $_SESSION['mock_contact_cms'] = [];
}
$contact_cms = &$_SESSION['mock_contact_cms'];

// 1. Contact Details Defaults
if (!isset($contact_cms['details'])) {
    $contact_cms['details'] = [
        'heading' => 'Contact Us / Enquire Now',
        'badge' => 'Admissions & Academic Office',
        'subheading' => 'We are here to support your child’s onboarding. Connect directly with our academic coordinators or submit an enquiry below.',
        'address' => "B-09, Lower Ground Floor,\nITL Twin Tower,\nNetaji Subhash Place,\nPitampura,\nDelhi – 110034",
        'phone' => '7827262956',
        'whatsapp' => '7827262956',
        'email' => 'info@zuvioglobalschool.com',
        'office_hours' => 'Monday–Saturday, 10:00 AM–7:00 PM'
    ];
}

// 2. Social Links Defaults
if (!isset($contact_cms['social_links'])) {
    $contact_cms['social_links'] = [
        [
            'id' => 1,
            'platform' => 'WhatsApp',
            'icon' => 'whatsapp',
            'url' => 'https://wa.me/917827262956',
            'sort_order' => 1,
            'is_published' => 1
        ],
        [
            'id' => 2,
            'platform' => 'Instagram',
            'icon' => 'instagram',
            'url' => 'https://www.instagram.com/thezuvio/',
            'sort_order' => 2,
            'is_published' => 1
        ],
        [
            'id' => 3,
            'platform' => 'Facebook',
            'icon' => 'facebook',
            'url' => 'https://www.facebook.com/share/1XsYWDm3rt/',
            'sort_order' => 3,
            'is_published' => 1
        ],
        [
            'id' => 4,
            'platform' => 'LinkedIn',
            'icon' => 'linkedin',
            'url' => 'https://www.linkedin.com/company/zuvio-global-school/',
            'sort_order' => 4,
            'is_published' => 1
        ],
        [
            'id' => 5,
            'platform' => 'YouTube',
            'icon' => 'youtube',
            'url' => 'https://www.youtube.com/@zuvioglobalschool',
            'sort_order' => 5,
            'is_published' => 1
        ]
    ];
} else {
    foreach ($contact_cms['social_links'] as &$s_link) {
        $plat = strtolower($s_link['platform'] ?? '');
        if ($plat === 'instagram') $s_link['url'] = 'https://www.instagram.com/thezuvio/';
        elseif ($plat === 'facebook') $s_link['url'] = 'https://www.facebook.com/share/1XsYWDm3rt/';
        elseif ($plat === 'linkedin') $s_link['url'] = 'https://www.linkedin.com/company/zuvio-global-school/';
        elseif ($plat === 'youtube') $s_link['url'] = 'https://www.youtube.com/@zuvioglobalschool';
    }
    unset($s_link);
}

// 3. Map Settings Defaults
if (!isset($contact_cms['map'])) {
    $contact_cms['map'] = [
        'is_visible' => 1,
        'title' => 'Our Headquarter Office',
        'subtitle' => 'Located at ITL Twin Tower, Netaji Subhash Place (NSP), Pitampura — easily accessible via Delhi Metro (Red & Pink Lines).',
        'embed_url' => 'https://maps.google.com/maps?q=ITL+Twin+Tower,+Netaji+Subhash+Place,+Pitampura,+Delhi+110034&t=&z=15&ie=UTF8&iwloc=&output=embed',
        'directions_url' => 'https://www.google.com/maps/search/?api=1&query=ITL+Twin+Tower,+Netaji+Subhash+Place,+Pitampura,+Delhi+110034',
        'height' => 420
    ];
} elseif (isset($contact_cms['map']['title']) && $contact_cms['map']['title'] === 'Our Academic & Admissions Office') {
    $contact_cms['map']['title'] = 'Our Headquarter Office';
}

// 4. Form Settings Defaults
if (!isset($contact_cms['form'])) {
    $contact_cms['form'] = [
        'title' => 'Enquire Now',
        'subtitle' => 'Please fill the form and we will get back to you within one working day.',
        'success_title' => 'Enquiry Submitted Successfully',
        'success_message' => 'Thank you! Your enquiry has been received. Our academic roadmap advisor will contact you within 24 working hours.',
        'button_text' => 'Submit Enquiry Form',
        'consent_text' => 'By submitting this form, you agree to receive official academic communications from Zuvio Global School.'
    ];
}

// 5. Conversion Banner Defaults
if (!isset($contact_cms['banner'])) {
    $contact_cms['banner'] = [
        'is_visible' => 1,
        'heading' => 'Ready to Experience Modern Virtual Schooling?',
        'subheading' => 'Join forward-thinking families who have chosen flexible, CBSE-aligned online education tailored to their child.',
        'cta_primary_label' => 'Book a Free 1-on-1 Demo',
        'cta_primary_url' => '/book-demo',
        'cta_secondary_label' => 'Admissions & Enrolment',
        'cta_secondary_url' => '/admissions'
    ];
}

$details = $contact_cms['details'];
$social_links = $contact_cms['social_links'];
$map = $contact_cms['map'];
$form_cfg = $contact_cms['form'];
$banner = $contact_cms['banner'];

// Form processing status
$form_status = 'idle';
$error_message = '';
$submitted_data = [];

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
        $school_type = trim($_POST['school_type'] ?? 'Online School');
        $city = trim($_POST['city'] ?? '');
        $country = trim($_POST['country'] ?? 'India');
        $raw_message = trim($_POST['message'] ?? '');

        // Preserve submitted values on validation error
        $submitted_data = compact('parent_name', 'student_name', 'email', 'phone', 'grade', 'school_type', 'city', 'country', 'raw_message');

        if (empty($parent_name) || empty($email) || empty($phone) || empty($grade)) {
            $form_status = 'error';
            $error_message = 'Please fill in all required fields (Parent Name, Email, Phone, and Grade Level).';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_status = 'error';
            $error_message = 'Please enter a valid email address.';
        } else {
            // Compose structured message for CRM
            $notes = [];
            if ($school_type) $notes[] = "School Type: {$school_type}";
            if ($city) $notes[] = "Location: {$city}, {$country}";
            if ($raw_message) $notes[] = "Notes: {$raw_message}";
            $full_message = implode(" | ", $notes);
            if (empty($full_message)) {
                $full_message = 'Submitted via Contact Us page';
            }

            // Save to database enquiries table if available
            $persisted = false;
            if ($db) {
                try {
                    $stmt = $db->prepare("
                        INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                        VALUES (?, ?, ?, ?, ?, ?, 'Contact Page', 1)
                    ");
                    $stmt->execute([
                        $parent_name,
                        $student_name ?: ($parent_name . ' (Student)'),
                        $grade,
                        $phone,
                        $email,
                        $full_message
                    ]);
                    $persisted = true;
                } catch (Exception $e) {
                    error_log("[Enquiry DB Error] " . $e->getMessage());
                }
            }

            // Always persist into mock enquiries session so lead is never lost in local/offline test environments
            if (!isset($_SESSION['mock_enquiries'])) {
                $_SESSION['mock_enquiries'] = [];
            }
            $_SESSION['mock_enquiries'][] = [
                'id' => count($_SESSION['mock_enquiries']) + 1001,
                'parent_name' => $parent_name,
                'student_name' => $student_name ?: ($parent_name . ' (Student)'),
                'grade' => $grade,
                'phone' => $phone,
                'email' => $email,
                'message' => $full_message,
                'source' => 'Contact Page',
                'status_id' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $form_status = 'success';
        }
    }
}

$page_slug = 'contact';
$seo = [
    'seo_title' => 'Contact Us & Enquire Now | Zuvio Global School',
    'meta_description' => 'Connect with Zuvio Global School. Reach our academic counsellors, enquire about admissions, view office hours, and locate our Delhi campus office.',
    'canonical_url' => BASE_URL . '/contact',
    'og_title' => 'Contact Us | Zuvio Global School',
    'og_description' => 'Get in touch with Zuvio Global School. Submit an enquiry, call our academic desk, or visit our office at ITL Twin Tower, Pitampura, Delhi.',
    'og_image' => '/assets/images/logo.png',
    'index_status' => 'index, follow'
];

include_once dirname(__FILE__) . '/../includes/header.php';

// Standardized full-width breadcrumbs matching Admissions and Beyond
render_breadcrumbs([
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Contact Us']
]);
?>

<main class="contact-page-main">

  <!-- Hero Banner Header -->
  <section class="contact-hero" style="background: linear-gradient(135deg, var(--color-navy-dark, #000A42) 0%, var(--color-navy, #062B63) 100%); color: #FFFFFF; padding: 4.5rem 1.5rem 4rem 1.5rem; position: relative; overflow: hidden; text-align: center;">
    <div style="position: absolute; top: -60px; right: -60px; width: 320px; height: 320px; border-radius: 50%; background: radial-gradient(circle, rgba(234, 179, 8, 0.12) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -40px; left: -40px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(14, 165, 233, 0.12) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="container" style="max-width: 820px; position: relative; z-index: 2;">
      <span style="display: inline-block; font-size: 0.8rem; font-weight: 700; color: var(--color-gold, #F59E0B); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.85rem; padding: 0.35rem 1rem; background: rgba(245, 158, 11, 0.12); border-radius: 9999px; border: 1px solid rgba(245, 158, 11, 0.3);">
        <?php echo h($details['badge']); ?>
      </span>
      <h1 style="font-size: 2.8rem; font-family: var(--font-primary); font-weight: 800; line-height: 1.2; margin-bottom: 1rem; color: #FFFFFF; letter-spacing: -0.5px;">
        <?php echo h($details['heading']); ?>
      </h1>
      <p style="font-size: 1.1rem; line-height: 1.65; color: #E2E8F0; margin: 0; font-weight: 300;">
        <?php echo h($details['subheading']); ?>
      </p>
    </div>
  </section>

  <!-- Two-Column Contact + Enquiry Section (Page 65 Reference) -->
  <section class="section" style="padding: 4.5rem 0; background-color: var(--color-white, #FFFFFF); border-bottom: 1px solid var(--color-border, #E2E8F0);">
    <div class="container">
      <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1.25fr; gap: 3.5rem; align-items: start;">
        
        <!-- LEFT COLUMN: Contact Details & Direct Connections -->
        <div class="contact-left-panel" style="display: flex; flex-direction: column; gap: 2rem;">
          
          <!-- Section Heading matching Page 65 -->
          <div style="border-bottom: 1px solid var(--color-border, #E2E8F0); padding-bottom: 1.5rem;">
            <h2 style="font-size: 2.2rem; font-family: var(--font-primary); color: var(--color-navy, #062B63); font-weight: 700; margin-bottom: 0.65rem; line-height: 1.2;">
              <?php echo h($form_cfg['title']); ?>
            </h2>
            <p style="font-size: 1rem; color: var(--color-muted, #64748B); line-height: 1.6; margin: 0;">
              <?php echo h($form_cfg['subtitle']); ?>
            </p>
          </div>

          <!-- Direct Communication Channels (Email, Phone, WhatsApp) -->
          <div style="display: flex; flex-direction: column; gap: 1.15rem;">
            
            <!-- Email Item -->
            <a href="mailto:<?php echo h($details['email']); ?>" class="contact-direct-item" style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.15rem; background-color: var(--color-surface, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-md, 10px); text-decoration: none; color: var(--color-navy, #062B63); transition: all 0.25s ease;">
              <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #EDE9FE; color: #7C3AED; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </div>
              <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-muted, #64748B); font-weight: 600; display: block;">Official Email</span>
                <span style="font-size: 0.95rem; font-weight: 600; color: var(--color-navy, #062B63);"><?php echo h($details['email']); ?></span>
              </div>
            </a>

            <!-- Phone Item -->
            <a href="tel:+91<?php echo preg_replace('/[^0-9]/', '', $details['phone']); ?>" class="contact-direct-item" style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.15rem; background-color: var(--color-surface, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-md, 10px); text-decoration: none; color: var(--color-navy, #062B63); transition: all 0.25s ease;">
              <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #E0F2FE; color: #0284C7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              </div>
              <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-muted, #64748B); font-weight: 600; display: block;">Admissions Hotline / Phone</span>
                <span style="font-size: 0.95rem; font-weight: 600; color: var(--color-navy, #062B63);">+91 <?php echo h($details['phone']); ?></span>
              </div>
            </a>

            <!-- WhatsApp Item -->
            <a href="https://wa.me/91<?php echo h($details['whatsapp']); ?>" target="_blank" rel="noopener" class="contact-direct-item" style="display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1.15rem; background-color: var(--color-surface, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-md, 10px); text-decoration: none; color: var(--color-navy, #062B63); transition: all 0.25s ease;">
              <div style="width: 42px; height: 42px; border-radius: 50%; background-color: #DCFCE7; color: #16A34A; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                </svg>
              </div>
              <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-muted, #64748B); font-weight: 600; display: block;">Instant WhatsApp Chat</span>
                <span style="font-size: 0.95rem; font-weight: 600; color: #15803D;">+91 <?php echo h($details['whatsapp']); ?> (Click to Chat)</span>
              </div>
            </a>

          </div>

          <!-- Physical Office Address Card (Full 4-sided border) -->
          <div class="card" style="border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-md, 10px); padding: 1.75rem; background-color: #FFFFFF; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <h3 style="font-size: 1.15rem; color: var(--color-navy, #062B63); margin: 0; font-family: var(--font-primary); font-weight: 700;">
                Our Headquarter Office
              </h3>
            </div>
            <p style="color: var(--color-text, #334155); font-size: 0.9rem; line-height: 1.65; margin: 0 0 0.85rem 0;">
              <?php echo nl2br(h($details['address'])); ?>
            </p>
            <a href="<?php echo h($map['directions_url']); ?>" target="_blank" rel="noopener" style="font-size: 0.85rem; color: var(--color-teal, #0D9488); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;">
              View Location Map &amp; Directions &rarr;
            </a>
          </div>

          <!-- Working Hours Card (Full 4-sided border, verified hours) -->
          <div class="card" style="border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-md, 10px); padding: 1.75rem; background-color: #FFFFFF; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
              <div style="width: 32px; height: 32px; border-radius: 6px; background-color: #E0E7FF; color: #4F46E5; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
              </div>
              <h3 style="font-size: 1.15rem; color: var(--color-navy, #062B63); margin: 0; font-family: var(--font-primary); font-weight: 700;">
                Office &amp; Counselling Hours
              </h3>
            </div>
            <p style="color: var(--color-text, #334155); font-size: 0.9rem; line-height: 1.65; margin: 0 0 0.5rem 0;">
              Our academic office and admissions counselors are available:
            </p>
            <div style="padding: 0.65rem 0.95rem; background-color: var(--color-surface, #F8FAFC); border: 1px solid var(--color-border, #E2E8F0); border-radius: 6px; font-weight: 700; color: var(--color-navy, #062B63); font-size: 0.9rem;">
              <?php echo h($details['office_hours']); ?>
            </div>
            <span style="font-size: 0.78rem; color: var(--color-muted, #64748B); display: block; margin-top: 0.5rem;">
              * Closed on Sundays and gazetted public holidays.
            </span>
          </div>

          <!-- Social Links Row (Page 65 Reference) -->
          <div style="padding-top: 0.5rem;">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-navy, #062B63); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 0.85rem;">
              Connect on Social Platforms
            </span>
            <div class="contact-social-row" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
              <?php foreach ($social_links as $item): ?>
                <?php if (!empty($item['is_published'])): ?>
                  <?php
                    $p = strtolower($item['platform']);
                    $icon_color = '#062B63';
                    if (strpos($p, 'whatsapp') !== false) $icon_color = '#16A34A';
                    elseif (strpos($p, 'instagram') !== false) $icon_color = '#E1306C';
                    elseif (strpos($p, 'facebook') !== false) $icon_color = '#1877F2';
                    elseif (strpos($p, 'linkedin') !== false) $icon_color = '#0A66C2';
                    elseif (strpos($p, 'youtube') !== false) $icon_color = '#FF0000';
                  ?>
                  <a href="<?php echo h($item['url']); ?>" target="_blank" rel="noopener" title="<?php echo h($item['platform']); ?>" aria-label="Connect with Zuvio on <?php echo h($item['platform']); ?>" class="contact-social-btn" style="width: 44px; height: 44px; border-radius: 50%; border: 1px solid var(--color-border, #E2E8F0); background-color: #FFFFFF; display: flex; align-items: center; justify-content: center; text-decoration: none; color: <?php echo $icon_color; ?>; box-shadow: var(--shadow-sm); transition: all 0.25s ease;">
                    <?php if (strpos($p, 'whatsapp') !== false): ?>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    <?php elseif (strpos($p, 'instagram') !== false): ?>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    <?php elseif (strpos($p, 'facebook') !== false): ?>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    <?php elseif (strpos($p, 'linkedin') !== false): ?>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    <?php elseif (strpos($p, 'youtube') !== false): ?>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    <?php else: ?>
                      <span style="font-size: 0.8rem; font-weight: 700;"><?php echo h(substr($item['platform'], 0, 2)); ?></span>
                    <?php endif; ?>
                  </a>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>

        </div>

        <!-- RIGHT COLUMN: Enquire Now Form Card (Page 65 Reference) -->
        <div class="contact-form-card" style="background-color: #FFFFFF; border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-lg, 16px); padding: 3rem 2.5rem; box-shadow: 0 10px 30px rgba(6, 43, 99, 0.06); position: sticky; top: 100px;">
          
          <?php if ($form_status === 'success'): ?>
            <div style="text-align: center; padding: 2.5rem 1rem;">
              <div style="width: 64px; height: 64px; border-radius: 50%; background-color: #DCFCE7; color: #16A34A; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
              <h3 style="font-size: 1.6rem; color: var(--color-navy, #062B63); margin-bottom: 0.85rem; font-family: var(--font-primary); font-weight: 700;">
                <?php echo h($form_cfg['success_title']); ?>
              </h3>
              <p style="color: var(--color-muted, #64748B); font-size: 1rem; line-height: 1.65; max-width: 480px; margin: 0 auto 2rem auto;">
                <?php echo h($form_cfg['success_message']); ?>
              </p>
              <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="/contact" class="btn btn-outline" style="padding: 0.65rem 1.25rem; font-size: 0.9rem;">Submit Another Enquiry</a>
                <a href="/admissions" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-size: 0.9rem;">Explore Admissions</a>
              </div>
            </div>
          <?php else: ?>
            
            <div style="margin-bottom: 1.75rem;">
              <h3 style="font-size: 1.55rem; color: var(--color-navy, #062B63); font-weight: 700; margin-bottom: 0.35rem; font-family: var(--font-primary);">
                Academic Enquiry Form
              </h3>
              <p style="font-size: 0.85rem; color: var(--color-muted, #64748B); margin: 0;">
                Fields marked with an asterisk (<span style="color: #EF4444;">*</span>) are required.
              </p>
            </div>

            <?php if ($form_status === 'error'): ?>
              <div style="background-color: #FEF2F2; border: 1px solid #FCA5A5; padding: 0.85rem 1.15rem; border-radius: var(--radius-sm, 6px); color: #DC2626; font-size: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span><?php echo h($error_message); ?></span>
              </div>
            <?php endif; ?>

            <form method="POST" action="" id="contactEnquiryForm">
              <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">

              <!-- School Type Preference (Page 65: "Are you looking for Online or Offline School?") -->
              <div style="margin-bottom: 1.25rem;">
                <label for="contact_school_type" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                  Are you looking for Online or Hybrid School? <span style="color: #EF4444;">*</span>
                </label>
                <select id="contact_school_type" name="school_type" class="contact-input" required style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; background-color: #FFFFFF; font-size: 0.9rem; color: var(--color-navy, #062B63); height: 44px;">
                  <option value="Online Schooling" <?php echo (($submitted_data['school_type'] ?? '') === 'Online Schooling') ? 'selected' : ''; ?>>Online Schooling (CBSE Aligned K–8)</option>
                  <option value="Hybrid Experiential Campus" <?php echo (($submitted_data['school_type'] ?? '') === 'Hybrid Experiential Campus') ? 'selected' : ''; ?>>Hybrid Experiential Learning Campus</option>
                  <option value="Exploring Both Options" <?php echo (($submitted_data['school_type'] ?? '') === 'Exploring Both Options') ? 'selected' : ''; ?>>Exploring Both Options</option>
                </select>
              </div>

              <!-- Full Name & Student Name Row -->
              <div class="contact-form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                  <label for="contact_parent_name" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                    Parent Full Name <span style="color: #EF4444;">*</span>
                  </label>
                  <input type="text" id="contact_parent_name" name="parent_name" value="<?php echo h($submitted_data['parent_name'] ?? ''); ?>" required placeholder="Enter full name" class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; font-size: 0.9rem; height: 44px; box-sizing: border-box;">
                </div>
                <div>
                  <label for="contact_student_name" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                    Student Name (Optional)
                  </label>
                  <input type="text" id="contact_student_name" name="student_name" value="<?php echo h($submitted_data['student_name'] ?? ''); ?>" placeholder="Enter child's name" class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; font-size: 0.9rem; height: 44px; box-sizing: border-box;">
                </div>
              </div>

              <!-- Email Address & Phone Number Row -->
              <div class="contact-form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                  <label for="contact_email" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                    Email Address <span style="color: #EF4444;">*</span>
                  </label>
                  <input type="email" id="contact_email" name="email" value="<?php echo h($submitted_data['email'] ?? ''); ?>" required placeholder="parent@example.com" class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; font-size: 0.9rem; height: 44px; box-sizing: border-box;">
                </div>
                <div>
                  <label for="contact_phone" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                    Phone Number <span style="color: #EF4444;">*</span>
                  </label>
                  <div style="display: flex; align-items: center; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); overflow: hidden; background-color: #FFFFFF; height: 44px; box-sizing: border-box;">
                    <select name="country_code" id="contact_country_code" style="padding: 0 0.5rem; font-size: 0.85rem; font-weight: 600; background-color: var(--color-surface, #F8FAFC); border: none; border-right: 1px solid var(--color-border, #CBD5E1); color: var(--color-navy, #062B63); height: 100%; outline: none; cursor: pointer;" aria-label="Country Code">
                      <option value="+91" selected>🇮🇳 +91</option>
                      <option value="+971">🇦🇪 +971</option>
                      <option value="+1">🇺🇸 +1</option>
                      <option value="+44">🇬🇧 +44</option>
                      <option value="+65">🇸🇬 +65</option>
                      <option value="+1">🇨🇦 +1</option>
                      <option value="+61">🇦🇺 +61</option>
                      <option value="+966">🇸🇦 +966</option>
                      <option value="+974">🇶🇦 +974</option>
                    </select>
                    <input type="tel" id="contact_phone" name="phone" value="<?php echo h($submitted_data['phone'] ?? ''); ?>" required placeholder="98765 43210" class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: none; outline: none; font-size: 0.9rem; height: 100%;">
                  </div>
                </div>
              </div>

              <!-- Grade Level & City Row -->
              <div class="contact-form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                  <label for="contact_grade" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                    Grade Level <span style="color: #EF4444;">*</span>
                  </label>
                  <select id="contact_grade" name="grade" required class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; background-color: #FFFFFF; font-size: 0.9rem; color: var(--color-navy, #062B63); height: 44px; box-sizing: border-box;">
                    <option value="">- Choose Grade -</option>
                    <option value="Early Years (Nursery, LKG, UKG)" <?php echo (($submitted_data['grade'] ?? '') === 'Early Years (Nursery, LKG, UKG)') ? 'selected' : ''; ?>>Early Years (Nursery, LKG, UKG)</option>
                    <option value="Grade 1" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 1') ? 'selected' : ''; ?>>Grade 1</option>
                    <option value="Grade 2" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 2') ? 'selected' : ''; ?>>Grade 2</option>
                    <option value="Grade 3" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 3') ? 'selected' : ''; ?>>Grade 3</option>
                    <option value="Grade 4" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 4') ? 'selected' : ''; ?>>Grade 4</option>
                    <option value="Grade 5" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 5') ? 'selected' : ''; ?>>Grade 5</option>
                    <option value="Grade 6" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 6') ? 'selected' : ''; ?>>Grade 6</option>
                    <option value="Grade 7" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 7') ? 'selected' : ''; ?>>Grade 7</option>
                    <option value="Grade 8" <?php echo (($submitted_data['grade'] ?? '') === 'Grade 8') ? 'selected' : ''; ?>>Grade 8</option>
                  </select>
                </div>
                <div>
                  <label for="contact_city" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                    City / Location
                  </label>
                  <input type="text" id="contact_city" name="city" value="<?php echo h($submitted_data['city'] ?? ''); ?>" placeholder="Enter your city" class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; font-size: 0.9rem; height: 44px; box-sizing: border-box;">
                </div>
              </div>

              <!-- Message / Queries (Optional) -->
              <div style="margin-bottom: 1.5rem;">
                <label for="contact_message" style="font-size: 0.85rem; font-weight: 700; color: var(--color-navy, #062B63); margin-bottom: 0.4rem; display: block;">
                  Message / Questions for Counselor
                </label>
                <textarea id="contact_message" name="message" rows="3" placeholder="Tell us about your child’s learning style, questions on CBSE curriculum, or schedule preference..." class="contact-input" style="width: 100%; padding: 0.75rem 0.95rem; border: 1px solid var(--color-border, #CBD5E1); border-radius: var(--radius-sm, 6px); outline: none; resize: vertical; font-size: 0.9rem; font-family: inherit; line-height: 1.5; box-sizing: border-box;"><?php echo h($submitted_data['raw_message'] ?? ''); ?></textarea>
              </div>

              <!-- Consent notice -->
              <p style="font-size: 0.75rem; color: var(--color-muted, #64748B); line-height: 1.5; margin-bottom: 1.5rem;">
                <?php echo h($form_cfg['consent_text']); ?>
              </p>

              <!-- Submit CTA Button -->
              <button type="submit" name="submit_enquiry" class="btn btn-primary" style="width: 100%; padding: 0.95rem; font-size: 1rem; font-weight: 700; border-radius: var(--radius-sm, 6px); display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 12px rgba(6, 43, 99, 0.2);">
                <span><?php echo h($form_cfg['button_text']); ?></span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="22" y1="2" x2="11" y2="13"></line>
                  <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
              </button>
            </form>

          <?php endif; ?>

        </div>

      </div>
    </div>
  </section>

  <!-- Real Interactive Map Section (Replaces Placeholder) -->
  <?php if (!empty($map['is_visible'])): ?>
    <section class="section" style="padding: 4.5rem 0; background-color: var(--color-surface, #F8FAFC); border-bottom: 1px solid var(--color-border, #E2E8F0);">
      <div class="container">
        
        <div style="text-align: center; max-width: 720px; margin: 0 auto 2.5rem auto;">
          <span style="font-size: 0.8rem; font-weight: 700; color: var(--color-gold, #F59E0B); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.5rem;">
            Find Us On the Map
          </span>
          <h2 style="font-size: 2.2rem; font-family: var(--font-primary); color: var(--color-navy, #062B63); font-weight: 700; margin-bottom: 0.75rem;">
            <?php echo h($map['title']); ?>
          </h2>
          <p style="font-size: 0.95rem; color: var(--color-muted, #64748B); line-height: 1.6; margin: 0;">
            <?php echo h($map['subtitle']); ?>
          </p>
        </div>

        <!-- Map Container with 4-side complete border -->
        <div style="background-color: #FFFFFF; border: 1px solid var(--color-border, #E2E8F0); border-radius: var(--radius-lg, 16px); overflow: hidden; box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.1));">
          
          <!-- Google Map Embed Iframe -->
          <div style="position: relative; width: 100%; height: <?php echo (int)$map['height']; ?>px; background-color: #E2E8F0;">
            <iframe 
              src="<?php echo h($map['embed_url']); ?>" 
              width="100%" 
              height="100%" 
              style="border: 0; display: block;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              title="Zuvio Global School Campus Map">
            </iframe>
          </div>

          <!-- Location Bar with Directions Action -->
          <div style="padding: 1.25rem 2rem; background-color: #FFFFFF; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-top: 1px solid var(--color-border, #E2E8F0);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <span style="font-size: 0.9rem; font-weight: 600; color: var(--color-navy, #062B63);">
                B-09, Lower Ground Floor, ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi – 110034
              </span>
            </div>
            
            <a href="<?php echo h($map['directions_url']); ?>" target="_blank" rel="noopener" class="btn btn-outline" style="padding: 0.55rem 1.25rem; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
              <span>Open in Google Maps</span>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
              </svg>
            </a>
          </div>

        </div>

      </div>
    </section>
  <?php endif; ?>

  <!-- Final Conversion Area (Section 3.5) -->
  <?php if (!empty($banner['is_visible'])): ?>
    <section class="section" style="padding: 5rem 1.5rem; background: linear-gradient(135deg, var(--color-navy-dark, #000A42) 0%, var(--color-navy, #062B63) 100%); color: #FFFFFF; text-align: center; position: relative; overflow: hidden;">
      <div class="container" style="max-width: 800px; position: relative; z-index: 2;">
        <h2 style="font-size: 2.3rem; font-family: var(--font-primary); font-weight: 800; color: #FFFFFF; margin-bottom: 1rem; line-height: 1.25;">
          <?php echo h($banner['heading']); ?>
        </h2>
        <p style="font-size: 1.05rem; line-height: 1.65; color: #E2E8F0; margin: 0 auto 2.25rem auto; font-weight: 300; max-width: 650px;">
          <?php echo h($banner['subheading']); ?>
        </p>
        <div style="display: flex; justify-content: center; gap: 1.25rem; flex-wrap: wrap;">
          <a href="<?php echo h($banner['cta_primary_url']); ?>" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 0.95rem; font-weight: 700; box-shadow: 0 4px 15px rgba(234, 179, 8, 0.35);">
            <?php echo h($banner['cta_primary_label']); ?> &rarr;
          </a>
          <a href="<?php echo h($banner['cta_secondary_url']); ?>" class="btn btn-outline" style="padding: 0.85rem 2rem; font-size: 0.95rem; font-weight: 600; color: #FFFFFF; border-color: rgba(255,255,255,0.4);">
            <?php echo h($banner['cta_secondary_label']); ?>
          </a>
        </div>
      </div>
    </section>
  <?php endif; ?>

</main>

<style>
  .contact-input:focus {
    border-color: var(--color-navy, #062B63) !important;
    box-shadow: 0 0 0 3px rgba(6, 43, 99, 0.1) !important;
  }
  .contact-direct-item:hover {
    border-color: var(--color-navy, #062B63) !important;
    background-color: #FFFFFF !important;
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }
  .contact-social-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(6, 43, 99, 0.15) !important;
    border-color: currentColor !important;
  }
  @media (max-width: 960px) {
    .contact-grid {
      grid-template-columns: 1fr !important;
      gap: 3rem !important;
    }
    .contact-form-card {
      position: static !important;
    }
  }
  @media (max-width: 600px) {
    .contact-form-row {
      grid-template-columns: 1fr !important;
    }
    .contact-form-card {
      padding: 2rem 1.5rem !important;
    }
    .contact-hero {
      padding: 3.5rem 1rem 3rem 1rem !important;
    }
    .contact-hero h1 {
      font-size: 2.1rem !important;
    }
  }
</style>

<?php
include_once dirname(__FILE__) . '/../includes/footer.php';
?>
