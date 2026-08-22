<?php
// Zuvio Global School - Main Header Template
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/helper.php';

safe_session_start();

// Resolve SEO variables
$current_slug = isset($page_slug) ? $page_slug : 'home';
if (!isset($seo)) {
    $seo = get_page_seo($current_slug);
}

// Resolve Navigation Items
$menu_items = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM `navigation_items` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $menu_items = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("[Header Nav Error] " . $e->getMessage());
    }
}

// Fallback menu if DB navigation_items table query fails
if (empty($menu_items)) {
    $menu_items = [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'About Us', 'url' => '/about'],
        ['label' => 'Our Curriculum', 'url' => '/our-curriculum'],
        ['label' => 'Zuvio Beyond', 'url' => '/zuvio-beyond'],
        ['label' => 'Blogs', 'url' => '/blogs'],
        ['label' => 'Contact Us', 'url' => '/contact']
    ];
}

$logo_path = get_setting('logo_url', '/assets/images/logo.png');
$phone_number = get_setting('phone', '7827262956');
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
    /* Header Layout & Brand Transitions */
    .site-header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background-color: var(--color-white);
      box-shadow: var(--shadow-sm);
      border-bottom: 1px solid var(--color-border);
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
      height: 56px;
      width: auto;
      object-fit: contain;
      display: block;
      transition: height 0.3s ease;
    }
    .desktop-nav {
      display: flex;
      align-items: center;
      gap: 2.25rem;
    }
    .nav-link {
      font-size: 0.95rem;
      font-weight: 500;
      color: var(--color-navy);
      position: relative;
      padding: 0.5rem 0;
      transition: color var(--transition-fast);
    }
    .nav-link:hover, .nav-link.active {
      color: var(--color-gold);
    }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background-color: var(--color-gold);
      transition: width var(--transition-fast);
    }
    .nav-link:hover::after, .nav-link.active::after {
      width: 100%;
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
      width: 280px;
      height: 100vh;
      background-color: var(--color-white);
      box-shadow: -5px 0 25px rgba(6, 43, 99, 0.15);
      z-index: 1100;
      padding: 2.5rem 2rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      transition: right 0.3s ease;
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
      background: rgba(3, 27, 66, 0.4);
      z-index: 1050;
      display: none;
    }
    .mobile-drawer-overlay.show {
      display: block;
    }
    .mobile-nav-link {
      font-size: 1.15rem;
      font-weight: 600;
      color: var(--color-navy);
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--color-border);
    }
    .mobile-nav-link:hover, .mobile-nav-link.active {
      color: var(--color-gold);
    }
    .mobile-close-btn {
      align-self: flex-end;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--color-navy);
    }

    @media (max-width: 900px) {
      .site-header {
        position: sticky !important;
        top: 0;
      }
      .header-logo-img {
        height: 44px;
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

  <!-- Header Section -->
  <header class="site-header">
    <div class="header-container">
      <a href="/">
        <img src="<?php echo h($logo_path); ?>" alt="Zuvio Global School" class="header-logo-img">
      </a>
      
      <!-- Desktop Navigation Menu -->
      <nav class="desktop-nav">
        <?php foreach ($menu_items as $item): 
          $active_class = ($_SERVER['REQUEST_URI'] === $item['url'] || 
                           (strpos($_SERVER['REQUEST_URI'], $item['url']) === 0 && $item['url'] !== '/')) ? 'active' : '';
        ?>
          <a href="<?php echo h($item['url']); ?>" class="nav-link <?php echo $active_class; ?>">
            <?php echo h($item['label']); ?>
          </a>
        <?php endforeach; ?>
        <a href="/contact" class="btn btn-primary" style="padding: 0.6rem 1.25rem; font-size: 0.85rem;">Enquire Now</a>
      </nav>
      
      <!-- Mobile hamburger trigger -->
      <button class="mobile-menu-trigger" aria-label="Toggle mobile menu" onclick="toggleMobileMenu(true)">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
    </div>
  </header>

  <!-- Mobile Drawer Overlay -->
  <div class="mobile-drawer-overlay" id="mobileDrawerOverlay" onclick="toggleMobileMenu(false)"></div>

  <!-- Mobile Navigation Drawer -->
  <div class="mobile-nav-drawer" id="mobileNavDrawer">
    <button class="mobile-close-btn" onclick="toggleMobileMenu(false)">&times;</button>
    <?php foreach ($menu_items as $item): 
      $active_class = ($_SERVER['REQUEST_URI'] === $item['url'] || 
                       (strpos($_SERVER['REQUEST_URI'], $item['url']) === 0 && $item['url'] !== '/')) ? 'active' : '';
    ?>
      <a href="<?php echo h($item['url']); ?>" class="mobile-nav-link <?php echo $active_class; ?>">
        <?php echo h($item['label']); ?>
      </a>
    <?php endforeach; ?>
    <a href="/contact" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Enquire Now</a>
  </div>

  <script>
    function toggleMobileMenu(open) {
      const drawer = document.getElementById('mobileNavDrawer');
      const overlay = document.getElementById('mobileDrawerOverlay');
      if (open) {
        drawer.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden'; // Lock background scroll
      } else {
        drawer.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
      }
    }
  </script>
