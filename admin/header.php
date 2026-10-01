<?php
// Zuvio Global School - Admin Header Layout
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';
require_once dirname(__FILE__) . '/../includes/auth.php';

safe_session_start();
require_login();

$logo_path = get_setting('logo_url', '/assets/images/logo.png');
$current_page = $page_slug ?? 'admin-dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Zuvio Global School</title>
  <link rel="stylesheet" href="/css/main.css">
  
  <style>
    body {
      background-color: var(--color-surface-blue);
      color: var(--color-text);
      display: flex;
      min-height: 100vh;
      margin: 0;
    }
    
    /* Sidebar Navigation */
    .admin-sidebar {
      width: 250px;
      background-color: var(--color-navy-dark);
      color: #FFFFFF;
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      border-right: 1px solid rgba(255,255,255,0.08);
      position: sticky;
      top: 0;
      height: 100vh;
    }
    .sidebar-brand {
      padding: 2rem 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .sidebar-logo {
      height: 48px;
      width: auto;
      object-fit: contain;
      display: block;
      filter: brightness(0) invert(1);
    }
    .sidebar-menu {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
      padding: 1rem 0.75rem 2rem 0.75rem;
      flex-grow: 1;
    }
    .sidebar-heading {
      font-size: 0.68rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      color: #D9A441;
      padding: 1rem 1rem 0.35rem 1rem;
    }
    .nav-section {
      margin-bottom: 0.25rem;
    }
    .nav-section-title {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.74rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #CBD5E1;
      padding: 0.65rem 0.85rem;
      cursor: pointer;
      user-select: none;
      border-radius: var(--radius-sm);
      transition: background 0.15s ease, color 0.15s ease;
    }
    .nav-section-title:hover {
      color: #FFFFFF;
      background: rgba(255, 255, 255, 0.08);
    }
    .nav-section.open .nav-section-title {
      color: #D9A441;
      background: rgba(255, 255, 255, 0.05);
    }
    .nav-section-toggle-icon {
      font-size: 0.65rem;
      transition: transform 0.2s ease;
      color: #94A3B8;
      transform: rotate(-90deg); /* points right (closed) by default */
    }
    .nav-section.open .nav-section-toggle-icon {
      transform: rotate(0deg); /* points down (open) */
      color: #D9A441;
    }
    .nav-section-items {
      display: none; /* CLOSED BY DEFAULT */
      flex-direction: column;
      gap: 0.2rem;
      padding: 0.2rem 0 0.4rem 0;
      transition: all 0.2s ease;
    }
    .nav-section.open .nav-section-items {
      display: flex !important;
    }
    .sidebar-item {
      display: flex;
      align-items: center;
      padding: 0.65rem 1rem;
      font-size: 0.88rem;
      font-weight: 500;
      color: #94A3B8;
      border-radius: var(--radius-sm);
      transition: all var(--transition-fast);
      text-decoration: none;
    }
    .sidebar-item:hover, .sidebar-item.active {
      color: #FFFFFF;
      background-color: rgba(255,255,255,0.06);
    }
    .sidebar-item.active {
      border-left: 4px solid var(--color-gold);
      background-color: rgba(255,255,255,0.10);
      color: #FFFFFF;
      font-weight: 600;
    }
    .sidebar-user {
      padding: 1.25rem;
      border-top: 1px solid rgba(255,255,255,0.08);
      font-size: 0.8rem;
      color: #94A3B8;
      background-color: rgba(0,0,0,0.15);
    }
    
    /* Main Layout Area */
    .admin-main {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      height: 100vh;
      overflow-y: auto;
    }
    .admin-topbar {
      background-color: #FFFFFF;
      border-bottom: 1px solid var(--color-border);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-shrink: 0;
    }
    .admin-body {
      padding: 2.5rem;
      flex-grow: 1;
    }

    .admin-form-group {
      margin-bottom: 1.25rem;
    }
    .admin-label {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--color-navy);
      display: block;
      margin-bottom: 0.4rem;
    }
    .admin-input {
      width: 100%;
      padding: 0.65rem 0.85rem;
      border: 1px solid rgba(6, 43, 99, 0.2);
      border-radius: var(--radius-sm);
      outline: none;
      font-size: 0.85rem;
      background-color: #FFFFFF;
    }
    .admin-input:focus {
      border-color: var(--color-navy);
    }

    /* Table and UI helpers */
    .table-responsive {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin-bottom: 1rem;
    }
    
    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(6, 43, 99, 0.6);
      backdrop-filter: blur(2px);
      z-index: 998;
    }
    .sidebar-overlay.active {
      display: block;
    }

    .mobile-menu-toggle {
      display: none;
      background: none;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-sm);
      padding: 0.45rem 0.75rem;
      font-size: 1.2rem;
      cursor: pointer;
      color: var(--color-navy);
      line-height: 1;
    }

    @media (max-width: 991px) {
      .mobile-menu-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }
      .admin-sidebar {
        position: fixed;
        left: -280px;
        top: 0;
        width: 270px;
        height: 100vh;
        z-index: 999;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      }
      .admin-sidebar.open {
        left: 0;
      }
      .sidebar-brand {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .sidebar-close-btn {
        display: block !important;
        background: none;
        border: none;
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.25rem;
      }
      .admin-main {
        width: 100%;
        height: 100vh;
      }
      .admin-topbar {
        padding: 0.85rem 1.25rem;
      }
      .admin-body {
        padding: 1.25rem;
      }
    }
  </style>
</head>
<body>

  <!-- Mobile Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

  <!-- Admin Sidebar -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <img src="<?php echo h($logo_path); ?>" alt="Zuvio Admin" class="sidebar-logo">
      <button class="sidebar-close-btn" style="display: none;" onclick="toggleAdminSidebar()">&times;</button>
    </div>
    
    <nav class="sidebar-menu" style="overflow-y: auto;">
      <!-- Dashboard Overview -->
      <div class="nav-section-group" style="margin-bottom: 0.5rem;">
        <a href="/admin" class="sidebar-item <?php echo ($current_page === 'admin-dashboard' || $current_page === 'dashboard') ? 'active' : ''; ?>">
          <span style="display:flex; align-items:center; gap:0.5rem;">
            <span>&#9638;</span> Dashboard
          </span>
        </a>
      </div>

      <?php 
        $curr_lead_src = $_GET['source'] ?? '';
        $curr_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        $is_leads_active = ($current_page === 'admin-enquiries' || strpos($curr_uri, 'enquiries') !== false);
        $is_header_active = ($current_page === 'admin-header-settings' || $current_page === 'admin-sliding-strip' || strpos($curr_uri, 'header-settings') !== false || strpos($curr_uri, 'sliding-strip') !== false);
        $is_home_active = ($current_page === 'admin-homepage' || strpos($curr_uri, 'homepage') !== false);
        $is_hero_active = ($current_page === 'admin-hero' || strpos($curr_uri, 'hero') !== false);
        $is_about_active = ($current_page === 'admin-about-cms' || $current_page === 'admin-profiles' || strpos($curr_uri, 'about-cms') !== false || strpos($curr_uri, 'profiles') !== false);
        $is_academics_active = ($current_page === 'admin-academics-cms' || strpos($curr_uri, 'academics-cms') !== false);
        $is_admissions_active = ($current_page === 'admin-admissions-cms' || strpos($curr_uri, 'admissions-cms') !== false);
        $is_beyond_active = ($current_page === 'admin-beyond-cms' || strpos($curr_uri, 'beyond-cms') !== false);
        $is_contact_active = ($current_page === 'admin-contact-cms' || strpos($curr_uri, 'contact-cms') !== false);
        $is_content_active = ($current_page === 'admin-testimonials' || $current_page === 'admin-faqs' || $current_page === 'admin-blogs' || $current_page === 'admin-media' || $current_page === 'admin-accreditations' || $current_page === 'admin-announcements' || strpos($curr_uri, 'testimonials') !== false || strpos($curr_uri, 'faqs') !== false || strpos($curr_uri, 'blogs') !== false || strpos($curr_uri, 'media') !== false || strpos($curr_uri, 'accreditations') !== false || strpos($curr_uri, 'announcements') !== false);
        $is_settings_active = ($current_page === 'admin-users' || $current_page === 'admin-settings' || $current_page === 'admin-seo' || $current_page === 'admin-navigation' || $current_page === 'admin-migrate' || strpos($curr_uri, 'users') !== false || strpos($curr_uri, 'settings') !== false || strpos($curr_uri, 'seo') !== false || strpos($curr_uri, 'navigation') !== false || strpos($curr_uri, 'migrate') !== false);
        $is_website_active = ($is_header_active || $is_home_active || $is_about_active || $is_academics_active || $is_admissions_active || $is_beyond_active || $is_contact_active);
      ?>

      <!-- 1. WEBSITE HIERARCHICAL CMS -->
      <div class="nav-section <?php echo $is_website_active ? 'open' : ''; ?>" id="nav-website">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-website')">
          <span>&#127760; Website</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <!-- Header Submenu -->
          <div style="padding: 0.25rem 0.75rem 0.25rem 1.25rem; font-size: 0.7rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 0.5px;">
            Header
          </div>
          <a href="/admin/header-settings.php" class="sidebar-item <?php echo ($current_page === 'admin-header-settings' || strpos($curr_uri, 'header-settings') !== false) ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&bull; Main Header</span>
          </a>
          <a href="/admin/sliding-strip.php" class="sidebar-item <?php echo ($current_page === 'admin-sliding-strip' || strpos($curr_uri, 'sliding-strip') !== false) ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&bull; Sliding Strip</span>
          </a>

          <!-- Page Section Editors -->
          <div style="padding: 0.5rem 0.75rem 0.25rem 1.25rem; font-size: 0.7rem; font-weight: 700; color: var(--color-gold); text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid rgba(255,255,255,0.06); margin-top: 0.25rem;">
            Pages &amp; Sections
          </div>
          <a href="/admin/homepage.php" class="sidebar-item <?php echo ($current_page === 'admin-homepage' || strpos($curr_uri, 'homepage') !== false) ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&#127968; Home (16 Sections)</span>
          </a>
          <a href="/admin/about-cms.php" class="sidebar-item <?php echo ($current_page === 'admin-about-cms' || $current_page === 'admin-profiles') ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&#128214; About</span>
          </a>
          <a href="/admin/academics-cms.php" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms') ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&#127891; Academics</span>
          </a>
          <a href="/admin/admissions-cms.php" class="sidebar-item <?php echo ($current_page === 'admin-admissions-cms') ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&#128221; Admissions</span>
          </a>
          <a href="/admin/beyond-cms.php" class="sidebar-item <?php echo ($current_page === 'admin-beyond-cms') ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&#127912; Beyond Academics</span>
          </a>
          <a href="/admin/contact-cms.php" class="sidebar-item <?php echo ($current_page === 'admin-contact-cms') ? 'active' : ''; ?>" style="padding-left: 2rem;">
            <span>&#9993; Contact</span>
          </a>
        </div>
      </div>

      <!-- 2. CONTENT MODULES -->
      <div class="nav-section <?php echo $is_content_active ? 'open' : ''; ?>" id="nav-content">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-content')">
          <span>&#128196; Content</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/academics-cms.php?tab=technology" class="sidebar-item <?php echo (strpos($curr_uri, 'technology') !== false) ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#128187; LMS Features</span>
          </a>
          <a href="/admin/testimonials.php" class="sidebar-item <?php echo $current_page === 'admin-testimonials' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#128172; Testimonials</span>
          </a>
          <a href="/admin/faqs.php" class="sidebar-item <?php echo $current_page === 'admin-faqs' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#10067; FAQs (18 Items)</span>
          </a>
          <a href="/admin/blogs.php" class="sidebar-item <?php echo $current_page === 'admin-blogs' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#128240; News &amp; Blogs</span>
          </a>
          <a href="/admin/media.php" class="sidebar-item <?php echo $current_page === 'admin-media' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#128444; Media</span>
          </a>
          <a href="/admin/accreditations.php" class="sidebar-item <?php echo $current_page === 'admin-accreditations' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#127942; Affiliations</span>
          </a>
        </div>
      </div>

      <!-- 3. LEAD MANAGEMENT -->
      <div class="nav-section <?php echo $is_leads_active ? 'open' : ''; ?>" id="nav-leads">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-leads')">
          <span>&#128227; Lead Management</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/enquiries.php?source=homepage" class="sidebar-item <?php echo ($is_leads_active && $curr_lead_src === 'homepage') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span style="display:flex; justify-content:space-between; width:100%; align-items:center;">
              <span>&#9873; Homepage Leads</span>
              <span class="badge" style="background:#2563EB; color:#fff; font-size:0.65rem; padding:1px 6px; border-radius:10px;">Home</span>
            </span>
          </a>
          <a href="/admin/enquiries.php?source=contact" class="sidebar-item <?php echo ($is_leads_active && $curr_lead_src === 'contact') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span style="display:flex; justify-content:space-between; width:100%; align-items:center;">
              <span>&#9993; Contact Us Leads</span>
              <span class="badge" style="background:#059669; color:#fff; font-size:0.65rem; padding:1px 6px; border-radius:10px;">Contact</span>
            </span>
          </a>
          <a href="/admin/enquiries.php" class="sidebar-item <?php echo ($is_leads_active && empty($curr_lead_src)) ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#10003; All Enquiries CRM</span>
          </a>
        </div>
      </div>

      <!-- 4. HERO BANNERS -->
      <div class="nav-section-group" style="margin-bottom: 0.5rem;">
        <a href="/admin/hero.php" class="sidebar-item <?php echo $is_hero_active ? 'active' : ''; ?>">
          <span style="display:flex; align-items:center; gap:0.5rem;">
            <span>&#127916;</span> Hero Banners
          </span>
        </a>
      </div>

      <!-- 5. SETTINGS -->
      <div class="nav-section <?php echo $is_settings_active ? 'open' : ''; ?>" id="nav-settings">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-settings')">
          <span>&#9881; Settings</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/settings.php" class="sidebar-item <?php echo $current_page === 'admin-settings' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Site Settings</a>
          <a href="/admin/seo.php" class="sidebar-item <?php echo $current_page === 'admin-seo' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Page SEO</a>
          <a href="/admin/navigation.php" class="sidebar-item <?php echo $current_page === 'admin-navigation' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Navigation</a>
          <a href="/admin/users.php" class="sidebar-item <?php echo $current_page === 'admin-users' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Users</a>
          <a href="/admin/migrate.php" class="sidebar-item <?php echo $current_page === 'admin-migrate' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Database Migrations</a>
        </div>
      </div>
    </nav>
    
    <div class="sidebar-user">
      <span>Logged in as:</span><br>
      <strong style="color: #FFFFFF;"><?php echo h($_SESSION['username']); ?></strong><br>
      <span style="font-size: 0.7rem; color: var(--color-gold); text-transform: uppercase; font-weight: bold;">
        <?php echo h($_SESSION['role_name']); ?>
      </span>
    </div>
  </aside>

  <!-- Admin Main Content Area -->
  <main class="admin-main">
    <div class="admin-topbar">
      <div style="display: flex; align-items: center; gap: 1rem;">
        <button class="mobile-menu-toggle" onclick="toggleAdminSidebar()" aria-label="Toggle navigation menu">&#9776;</button>
        <h2 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-secondary); margin: 0;">System Management Dashboard</h2>
      </div>
      
      <!-- Topbar Actions -->
      <div style="display: flex; align-items: center; gap: 0.75rem;">
        <a href="/" target="_blank" class="btn btn-outline" style="padding: 0.4rem 0.85rem; font-size: 0.8rem;">View Website &nearr;</a>
        <a href="/admin/logout" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Sign out?');">Sign Out</a>
      </div>
    </div>
    <div class="admin-body">

    <script>
      function toggleAdminSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
          sidebar.classList.toggle('open');
          overlay.classList.toggle('active');
        }
      }

      function toggleNavGroup(id) {
        const sec = document.getElementById(id);
        if (sec) {
          sec.classList.toggle('open');
        }
      }
    </script>

