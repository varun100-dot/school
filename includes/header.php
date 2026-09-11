<?php
// Zuvio Global School - Main Header Template (Phase 5 Redesign)
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/helper.php';

safe_session_start();

// Resolve SEO variables
$current_slug = isset($page_slug) ? $page_slug : 'home';
if (!isset($seo)) {
    $seo = get_page_seo($current_slug);
}

// Fetch Active Announcement if available
$top_announcement = 'Admissions Open 2026–2027';
$announcement_btn_text = 'Enrol Now';
$announcement_btn_url = '/admissions#enrol';

if ($db) {
    try {
        $a_stmt = $db->query("SELECT * FROM `announcements` WHERE `is_active` = 1 ORDER BY `sort_order` ASC LIMIT 1");
        $ann_row = $a_stmt->fetch();
        if ($ann_row && !empty($ann_row['text'])) {
            $top_announcement = $ann_row['text'];
            if (!empty($ann_row['button_text'])) $announcement_btn_text = $ann_row['button_text'];
            if (!empty($ann_row['button_url'])) $announcement_btn_url = $ann_row['button_url'];
        }
    } catch (Exception $e) {
        // Fallback
    }
}

// Resolve Hierarchical Navigation Items
$raw_menu = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `navigation_items` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $raw_menu = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Header Nav Error] " . $e->getMessage());
    }
}

$nav_tree = [];
if (!empty($raw_menu)) {
    $by_id = [];
    foreach ($raw_menu as $item) {
        $item['children'] = [];
        $by_id[$item['id']] = $item;
    }
    foreach ($by_id as $id => &$item) {
        if (!empty($item['parent_id']) && isset($by_id[$item['parent_id']])) {
            $by_id[$item['parent_id']]['children'][] = &$item;
        } elseif (empty($item['parent_id'])) {
            $nav_tree[] = &$item;
        }
    }
}

// Fallback hierarchical menu if DB table is empty or missing sub-items
if (empty($nav_tree) || count($nav_tree) < 5 || empty($nav_tree[1]['children'])) {
    $nav_tree = [
        ['label' => 'Home', 'url' => '/', 'children' => []],
        [
            'label' => 'About Us', 'url' => '/about',
            'children' => [
                ['label' => 'About Zuvio', 'url' => '/about#about-zuvio'],
                ['label' => 'Our Team', 'url' => '/about#leadership'],
                ['label' => 'Founder’s Message', 'url' => '/founder-message'],
                ['label' => 'Affiliations & Accreditations', 'url' => '/about#accreditations']
            ]
        ],
        [
            'label' => 'Academics', 'url' => '/academics',
            'children' => [
                ['label' => 'Technology', 'url' => '/academics#technology'],
                ['label' => 'Curriculum', 'url' => '/curriculum'],
                ['label' => 'Special Education', 'url' => '/academics#special-education'],
                ['label' => 'Electives', 'url' => '/academics#electives'],
                ['label' => 'NEP 2020', 'url' => '/academics#nep-2020'],
                ['label' => 'Resources', 'url' => '/academics#resources']
            ]
        ],
        [
            'label' => 'Admissions', 'url' => '/admissions',
            'children' => [
                ['label' => 'Enrol Now', 'url' => '/admissions#enrol'],
                ['label' => 'Eligibility', 'url' => '/admissions#eligibility'],
                ['label' => 'Calendar', 'url' => '/admissions#calendar'],
                ['label' => 'Fees', 'url' => '/admissions#fees'],
                ['label' => 'FAQ', 'url' => '/faq']
            ]
        ],
        [
            'label' => 'Beyond', 'url' => '/beyond',
            'children' => []
        ],
        ['label' => 'Contact Us', 'url' => '/contact', 'children' => []]
    ];
}

$logo_path = get_setting('logo_url', '/assets/images/logo.png');
$phone_number = get_setting('phone', '7827262956');
$email_address = get_setting('general_email', 'info@zuvioglobalschool.com');
$affiliation_info = 'Affiliation No: IA 4883 • IAO Accredited • ISSO Member';
$social_fb = get_setting('social_facebook', '#');
$social_insta = get_setting('social_instagram', '#');
$social_linkedin = get_setting('social_linkedin', '#');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo h($seo['seo_title']); ?></title>
  <meta name="description" content="<?php echo h($seo['meta_description']); ?>">
  <link rel="canonical" href="<?php echo h($seo['canonical_url']); ?>">
  
  <!-- Open Graph -->
  <meta property="og:title" content="<?php echo h($seo['og_title']); ?>">
  <meta property="og:description" content="<?php echo h($seo['og_description']); ?>">
  <meta property="og:image" content="<?php echo h($seo['og_image']); ?>">
  <meta property="og:type" content="website">
  <meta name="robots" content="<?php echo h($seo['index_status']); ?>">

  <?php 
  $css_version = '';
  $css_filepath = dirname(__FILE__) . '/../css/main.css';
  if (file_exists($css_filepath)) {
      $css_version = '?v=' . substr(md5_file($css_filepath), 0, 8);
  }
  ?>
  <link rel="stylesheet" href="/css/main.css<?php echo $css_version; ?>">
  
  <style>
    /* Header Base Layout */
    .site-header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background-color: var(--color-white);
      box-shadow: var(--shadow-sm);
      border-bottom: none;
      font-family: var(--font-secondary);
      transition: padding 0.3s ease;
    }
    .header-container {
      max-width: var(--max-width);
      margin: 0 auto;
      padding: 0.8rem 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header-logo-img {
      height: 64px;
      width: auto;
      object-fit: contain;
      display: block;
      transition: height 0.3s ease;
    }
    .desktop-nav {
      display: flex;
      align-items: center;
      gap: 1.25rem;
    }
    .nav-link-item {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--color-navy);
      padding: 0.5rem 0.85rem;
      border-radius: var(--radius-sm);
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      transition: all var(--transition-fast);
      text-decoration: none;
    }
    .nav-link-item:hover,
    .nav-item-has-dropdown:hover > .nav-link-item,
    .nav-item-has-dropdown:focus-within > .nav-link-item,
    .nav-link-item.active {
      background-color: var(--color-navy);
      color: #FFFFFF !important;
    }
    .nav-item-has-dropdown:hover .nav-dropdown-arrow,
    .nav-item-has-dropdown:focus-within .nav-dropdown-arrow,
    .nav-link-item:hover .nav-dropdown-arrow {
      border-color: #FFFFFF !important;
      transform: rotate(-135deg) translateY(-2px);
    }
    .nav-cta-outline {
      transition: all var(--transition-fast);
    }
    .nav-cta-outline:hover {
      background-color: var(--color-navy) !important;
      color: #FFFFFF !important;
      border-color: var(--color-navy) !important;
    }
    .mobile-menu-trigger {
      display: none;
      background: none;
      border: none;
      color: var(--color-navy);
      cursor: pointer;
      padding: 0.5rem;
    }

    /* Mobile Navigation Drawer */
    .mobile-nav-drawer {
      position: fixed;
      top: 0;
      right: -100%;
      width: 320px;
      height: 100vh;
      background-color: var(--color-white);
      box-shadow: -5px 0 25px rgba(6, 43, 99, 0.15);
      z-index: 1100;
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      overflow-y: auto;
      transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .mobile-nav-drawer.open {
      right: 0;
    }
    .mobile-drawer-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      background: rgba(3, 27, 66, 0.5);
      z-index: 1050;
      display: none;
    }
    .mobile-drawer-overlay.show {
      display: block;
    }
    .mobile-close-btn {
      align-self: flex-end;
      background: none;
      border: none;
      font-size: 1.75rem;
      cursor: pointer;
      color: var(--color-navy);
      line-height: 1;
    }

    @media (max-width: 1024px) {
      .header-logo-img {
        height: 52px;
      }
      .desktop-nav {
        display: none;
      }
      .mobile-menu-trigger {
        display: block;
      }
    }
  </style>
</head>
<body>

  <!-- 1. Top Utility Bar -->
  <div class="top-announcement-strip">
    <div class="announcement-container">
      <div class="announcement-left">
        <span class="top-bar-affiliation" style="font-weight: 600; color: #FFFFFF; font-size: 0.85rem; letter-spacing: 0.2px;">
          Affiliation No: IA 4883 • IAO Accredited • ISSO Member
        </span>
      </div>
      <div class="announcement-right">
        <a href="tel:<?php echo h($phone_number); ?>" class="announcement-link" title="Call Us">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          +91 <?php echo h($phone_number); ?>
        </a>
        <span class="announcement-divider" style="color: rgba(255,255,255,0.4); font-size: 0.8rem;">•</span>
        <a href="mailto:<?php echo h($email_address); ?>" class="announcement-link" title="Email Us">
          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          <?php echo h($email_address); ?>
        </a>
        <span class="announcement-divider" style="color: rgba(255,255,255,0.4); font-size: 0.8rem;">•</span>
        <div class="announcement-socials">
          <a href="<?php echo h($social_fb); ?>" target="_blank" rel="noopener" class="announcement-social-icon" aria-label="Facebook" title="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
          <a href="<?php echo h($social_insta); ?>" target="_blank" rel="noopener" class="announcement-social-icon" aria-label="Instagram" title="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
          </a>
          <a href="<?php echo h($social_linkedin); ?>" target="_blank" rel="noopener" class="announcement-social-icon" aria-label="LinkedIn" title="LinkedIn">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Moving USP Strip -->
  <div class="usp-ticker-strip">
    <div class="ticker-wrap">
      <div class="ticker-track">
        <div class="ticker-item"><span class="ticker-grade-pill">K to Grade 8</span> 100% Online Schooling</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">CBSE & NEP 2020 Aligned Curriculum</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Oxford Thematic Learning Approach</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">IBM-Supported AI & Future Skills</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Live Interactive Small-Group Classes</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Personalised Academic & Special Ed Support</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Global Exposure Beyond Boundaries</div>
        <span class="ticker-dot"></span>
        <!-- Duplicate items for seamless continuous marquee loop -->
        <div class="ticker-item"><span class="ticker-grade-pill">K to Grade 8</span> 100% Online Schooling</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">CBSE & NEP 2020 Aligned Curriculum</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Oxford Thematic Learning Approach</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">IBM-Supported AI & Future Skills</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Live Interactive Small-Group Classes</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Personalised Academic & Special Ed Support</div>
        <span class="ticker-dot"></span>
        <div class="ticker-item">Global Exposure Beyond Boundaries</div>
      </div>
    </div>
  </div>

  <!-- 3. Primary Header with Multi-Level Dropdown Navigation -->
  <header class="site-header">
    <div class="header-container">
      <a href="/" title="Zuvio Global School Home">
        <img src="<?php echo h($logo_path); ?>" alt="Zuvio Global School" class="header-logo-img">
      </a>
      
      <!-- Desktop Dropdown Navigation Menu -->
      <nav class="desktop-nav">
        <?php foreach ($nav_tree as $item): 
          $has_children = !empty($item['children']);
          $is_active = ($_SERVER['REQUEST_URI'] === $item['url'] || 
                       (strpos($_SERVER['REQUEST_URI'], $item['url']) === 0 && $item['url'] !== '/'));
        ?>
          <?php if ($has_children): ?>
            <div class="nav-item-has-dropdown">
              <a href="<?php echo h($item['url']); ?>" class="nav-link-item <?php echo $is_active ? 'active' : ''; ?> nav-dropdown-trigger">
                <?php echo h($item['label']); ?>
                <span class="nav-dropdown-arrow"></span>
              </a>
              <div class="nav-dropdown-menu">
                <?php foreach ($item['children'] as $child): ?>
                  <a href="<?php echo h($child['url']); ?>" class="nav-dropdown-item">
                    <?php echo h($child['label']); ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          <?php else: ?>
            <a href="<?php echo h($item['url']); ?>" class="nav-link-item <?php echo $is_active ? 'active' : ''; ?>">
              <?php echo h($item['label']); ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>

        <a href="/contact" class="btn btn-outline nav-cta-outline" style="padding: 0.55rem 1.25rem; font-size: 0.85rem; border-color: var(--color-navy); margin-left: 0.5rem;">Enquire Now</a>
        <a href="javascript:void(0)" onclick="openCallbackModal()" class="btn btn-primary btn-demo" style="padding: 0.55rem 1.25rem; font-size: 0.85rem; background-color: var(--color-teal); border-color: var(--color-teal); color: #fff;">Book a Demo</a>
      </nav>
      
      <!-- Mobile hamburger trigger -->
      <button class="mobile-menu-trigger" aria-label="Toggle mobile menu" onclick="toggleMobileMenu(true)">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
    </div>
  </header>

  <!-- Mobile Drawer Overlay -->
  <div class="mobile-drawer-overlay" id="mobileDrawerOverlay" onclick="toggleMobileMenu(false)"></div>

  <!-- Mobile Navigation Drawer with Accordions -->
  <div class="mobile-nav-drawer" id="mobileNavDrawer">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem;">
      <img src="<?php echo h($logo_path); ?>" alt="Zuvio Global School" style="height: 42px; width: auto;">
      <button class="mobile-close-btn" onclick="toggleMobileMenu(false)">&times;</button>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.25rem; margin-top: 0.5rem;">
      <?php foreach ($nav_tree as $idx => $item): 
        $has_children = !empty($item['children']);
      ?>
        <?php if ($has_children): ?>
          <div class="mobile-nav-group">
            <button class="mobile-nav-header" onclick="toggleMobileSubmenu(this)">
              <span><?php echo h($item['label']); ?></span>
              <span class="mobile-nav-arrow">&#9662;</span>
            </button>
            <div class="mobile-sub-menu">
              <a href="<?php echo h($item['url']); ?>" class="mobile-sub-link" style="font-weight: 700; color: var(--color-navy);">Overview &rarr;</a>
              <?php foreach ($item['children'] as $child): ?>
                <a href="<?php echo h($child['url']); ?>" class="mobile-sub-link" onclick="toggleMobileMenu(false)">
                  <?php echo h($child['label']); ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: ?>
          <div style="border-bottom: 1px solid var(--color-border); padding: 0.75rem 0;">
            <a href="<?php echo h($item['url']); ?>" style="font-size: 1.05rem; font-weight: 600; color: var(--color-navy); text-decoration: none;" onclick="toggleMobileMenu(false)">
              <?php echo h($item['label']); ?>
            </a>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
      <a href="/contact" class="btn btn-outline" style="width: 100%; text-align: center; border-color: var(--color-navy); color: var(--color-navy);" onclick="toggleMobileMenu(false)">Enquire Now</a>
      <a href="javascript:void(0)" onclick="openCallbackModal(); toggleMobileMenu(false);" class="btn btn-primary" style="width: 100%; text-align: center; background-color: var(--color-teal); border-color: var(--color-teal); color: #fff;">Book a Demo</a>
    </div>

    <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--color-border); font-size: 0.82rem; color: var(--color-muted);">
      <p style="margin-bottom: 0.25rem;"><strong>Phone:</strong> <a href="tel:<?php echo h($phone_number); ?>" style="color: var(--color-navy);">+91 <?php echo h($phone_number); ?></a></p>
      <p><strong>Email:</strong> <a href="mailto:<?php echo h($email_address); ?>" style="color: var(--color-navy);"><?php echo h($email_address); ?></a></p>
    </div>
  </div>

  <script>
    function toggleMobileMenu(open) {
      const drawer = document.getElementById('mobileNavDrawer');
      const overlay = document.getElementById('mobileDrawerOverlay');
      if (open) {
        drawer.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
      } else {
        drawer.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
      }
    }

    function toggleMobileSubmenu(btn) {
      btn.classList.toggle('active');
      const subMenu = btn.nextElementSibling;
      if (subMenu) {
        subMenu.classList.toggle('open');
      }
    }
  </script>
