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
      <!-- Overview & Quick Access -->
      <div class="nav-section-group">
        <a href="/admin" class="sidebar-item <?php echo ($current_page === 'admin-dashboard' || $current_page === 'dashboard') ? 'active' : ''; ?>">
          <span style="display:flex; align-items:center; gap:0.5rem;">
            <span>&#9638;</span> Dashboard Overview
          </span>
        </a>
      </div>

      <!-- 2. Dedicated Lead Management -->
      <?php 
        $curr_lead_src = $_GET['source'] ?? '';
        $curr_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        $is_leads_active = ($current_page === 'admin-enquiries' || strpos($curr_uri, 'enquiries') !== false);
        $is_home_active = ($current_page === 'admin-hero' || $current_page === 'admin-homepage' || strpos($curr_uri, 'hero') !== false || strpos($curr_uri, 'homepage') !== false);
        $is_about_active = ($current_page === 'admin-about-cms' || $current_page === 'admin-profiles' || $current_page === 'admin-accreditations' || strpos($curr_uri, 'about-cms') !== false || strpos($curr_uri, 'profiles') !== false || strpos($curr_uri, 'accreditations') !== false);
        $is_academics_active = ($current_page === 'admin-academics-cms' || strpos($curr_uri, 'academics-cms') !== false);
        $is_admissions_active = ($current_page === 'admin-admissions-cms' || $current_page === 'admin-faqs' || strpos($curr_uri, 'admissions-cms') !== false || strpos($curr_uri, 'faqs') !== false);
        $is_beyond_active = ($current_page === 'admin-beyond-cms' || strpos($curr_uri, 'beyond-cms') !== false);
        $is_contact_active = ($current_page === 'admin-contact-cms' || strpos($curr_uri, 'contact-cms') !== false);
        $is_media_active = ($current_page === 'admin-testimonials' || $current_page === 'admin-media' || $current_page === 'admin-blogs' || $current_page === 'admin-announcements' || strpos($curr_uri, 'testimonials') !== false || strpos($curr_uri, 'media') !== false || strpos($curr_uri, 'blogs') !== false || strpos($curr_uri, 'announcements') !== false);
        $is_seo_active = ($current_page === 'admin-seo' || $current_page === 'admin-navigation' || strpos($curr_uri, 'seo') !== false || strpos($curr_uri, 'navigation') !== false);
        $is_system_active = ($current_page === 'admin-users' || $current_page === 'admin-settings' || $current_page === 'admin-migrate' || strpos($curr_uri, 'users') !== false || strpos($curr_uri, 'settings') !== false || strpos($curr_uri, 'migrate') !== false);
      ?>
      <div class="nav-section <?php echo $is_leads_active ? 'open' : ''; ?>" id="nav-leads">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-leads')">
          <span>Lead Management</span>
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

      <!-- Homepage Management -->
      <div class="nav-section <?php echo $is_home_active ? 'open' : ''; ?>" id="nav-home">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-home')">
          <span>Homepage</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/hero.php" class="sidebar-item <?php echo ($current_page === 'admin-hero' || strpos($curr_uri, 'hero') !== false) ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#128444; Hero Banners (Live Carousel)</span>
          </a>
          <a href="/admin/homepage.php" class="sidebar-item <?php echo $current_page === 'admin-homepage' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">
            <span>&#9881; Homepage Sections CMS</span>
          </a>
        </div>
      </div>

      <!-- About Us -->
      <div class="nav-section <?php echo $is_about_active ? 'open' : ''; ?>" id="nav-about">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-about')">
          <span>About Us</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/about-cms.php" class="sidebar-item <?php echo ($current_page === 'admin-about-cms' && empty($_GET['tab'])) ? 'active' : ''; ?>" style="padding-left: 1.5rem;">About Zuvio</a>
          <a href="/admin/profiles.php" class="sidebar-item <?php echo $current_page === 'admin-profiles' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Our Team</a>
          <a href="/admin/about-cms.php?tab=founder" class="sidebar-item <?php echo ($current_page === 'admin-about-cms' && ($_GET['tab'] ?? '') === 'founder') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Founder's Message</a>
          <a href="/admin/accreditations.php" class="sidebar-item <?php echo $current_page === 'admin-accreditations' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Affiliations &amp; Accreditations</a>
        </div>
      </div>

      <!-- Academics -->
      <div class="nav-section <?php echo $is_academics_active ? 'open' : ''; ?>" id="nav-academics">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-academics')">
          <span>Academics</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/academics-cms.php?tab=technology" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms' && ($_GET['tab'] ?? '') === 'technology') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Technology &amp; LMS</a>
          <a href="/admin/academics-cms.php?tab=curriculum" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms' && ($_GET['tab'] ?? '') === 'curriculum') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Curriculum Framework</a>
          <a href="/admin/academics-cms.php?tab=special_ed" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms' && ($_GET['tab'] ?? '') === 'special_ed') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Special Education</a>
          <a href="/admin/academics-cms.php?tab=electives" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms' && ($_GET['tab'] ?? '') === 'electives') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Electives</a>
          <a href="/admin/academics-cms.php?tab=nep_2020" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms' && ($_GET['tab'] ?? '') === 'nep_2020') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">NEP 2020</a>
          <a href="/admin/academics-cms.php?tab=resources" class="sidebar-item <?php echo ($current_page === 'admin-academics-cms' && ($_GET['tab'] ?? '') === 'resources') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Academic Resources</a>
        </div>
      </div>

      <!-- Admissions -->
      <div class="nav-section <?php echo $is_admissions_active ? 'open' : ''; ?>" id="nav-admissions">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-admissions')">
          <span>Admissions</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/admissions-cms.php?tab=overview" class="sidebar-item <?php echo ($current_page === 'admin-admissions-cms' && (empty($_GET['tab']) || $_GET['tab'] === 'overview')) ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Admissions Overview</a>
          <a href="/admin/admissions-cms.php?tab=enrol" class="sidebar-item <?php echo ($current_page === 'admin-admissions-cms' && ($_GET['tab'] ?? '') === 'enrol') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Enrol Now (5 Steps)</a>
          <a href="/admin/admissions-cms.php?tab=eligibility" class="sidebar-item <?php echo ($current_page === 'admin-admissions-cms' && ($_GET['tab'] ?? '') === 'eligibility') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Eligibility Matrix</a>
          <a href="/admin/admissions-cms.php?tab=calendar" class="sidebar-item <?php echo ($current_page === 'admin-admissions-cms' && ($_GET['tab'] ?? '') === 'calendar') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Academic Calendar</a>
          <a href="/admin/admissions-cms.php?tab=fees" class="sidebar-item <?php echo ($current_page === 'admin-admissions-cms' && ($_GET['tab'] ?? '') === 'fees') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Fees Structure</a>
          <a href="/admin/faqs.php" class="sidebar-item <?php echo $current_page === 'admin-faqs' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Parent FAQs (18 Items)</a>
        </div>
      </div>

      <!-- Beyond Academics -->
      <div class="nav-section <?php echo $is_beyond_active ? 'open' : ''; ?>" id="nav-beyond">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-beyond')">
          <span>Beyond Academics</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/beyond-cms.php?tab=cocurricular" class="sidebar-item <?php echo ($current_page === 'admin-beyond-cms' && ($_GET['tab'] ?? 'cocurricular') === 'cocurricular') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Co-curricular / Clubs</a>
          <a href="/admin/beyond-cms.php?tab=hybrid" class="sidebar-item <?php echo ($current_page === 'admin-beyond-cms' && ($_GET['tab'] ?? '') === 'hybrid') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Hybrid Campus</a>
          <a href="/admin/beyond-cms.php?tab=achievers" class="sidebar-item <?php echo ($current_page === 'admin-beyond-cms' && ($_GET['tab'] ?? '') === 'achievers') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Student Achievers</a>
          <a href="/admin/beyond-cms.php?tab=gallery" class="sidebar-item <?php echo ($current_page === 'admin-beyond-cms' && ($_GET['tab'] ?? '') === 'gallery') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Photo Gallery</a>
          <a href="/admin/beyond-cms.php?tab=classroom" class="sidebar-item <?php echo ($current_page === 'admin-beyond-cms' && ($_GET['tab'] ?? '') === 'classroom') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Virtual Classroom</a>
        </div>
      </div>

      <!-- Contact Us -->
      <div class="nav-section <?php echo $is_contact_active ? 'open' : ''; ?>" id="nav-contact">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-contact')">
          <span>Contact Us</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/contact-cms.php" class="sidebar-item <?php echo $current_page === 'admin-contact-cms' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Contact Us Page CMS</a>
          <a href="/admin/enquiries.php?source=contact" class="sidebar-item <?php echo ($is_leads_active && $curr_lead_src === 'contact') ? 'active' : ''; ?>" style="padding-left: 1.5rem;">View Contact Leads</a>
        </div>
      </div>

      <!-- Media & Engagement -->
      <div class="nav-section <?php echo $is_media_active ? 'open' : ''; ?>" id="nav-media">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-media')">
          <span>Media &amp; Community</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/testimonials.php" class="sidebar-item <?php echo $current_page === 'admin-testimonials' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Parent Testimonials</a>
          <a href="/admin/media.php" class="sidebar-item <?php echo $current_page === 'admin-media' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Media Manager (IMG/VID/PDF)</a>
          <a href="/admin/blogs.php" class="sidebar-item <?php echo $current_page === 'admin-blogs' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Manage Blogs &amp; Articles</a>
          <a href="/admin/announcements.php" class="sidebar-item <?php echo $current_page === 'admin-announcements' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Announcements Strip</a>
        </div>
      </div>

      <!-- Navigation & SEO -->
      <div class="nav-section <?php echo $is_seo_active ? 'open' : ''; ?>" id="nav-seo">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-seo')">
          <span>SEO &amp; Navigation</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/seo.php" class="sidebar-item <?php echo $current_page === 'admin-seo' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Page SEO &amp; Meta (26 Pages)</a>
          <a href="/admin/navigation.php" class="sidebar-item <?php echo $current_page === 'admin-navigation' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Navigation Menu Tree</a>
        </div>
      </div>

      <!-- System & Settings -->
      <div class="nav-section <?php echo $is_system_active ? 'open' : ''; ?>" id="nav-system">
        <div class="nav-section-title" onclick="toggleNavGroup('nav-system')">
          <span>System &amp; Settings</span>
          <span class="nav-section-toggle-icon">&#9662;</span>
        </div>
        <div class="nav-section-items">
          <a href="/admin/users.php" class="sidebar-item <?php echo $current_page === 'admin-users' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">User Management</a>
          <a href="/admin/settings.php" class="sidebar-item <?php echo $current_page === 'admin-settings' ? 'active' : ''; ?>" style="padding-left: 1.5rem;">Site Settings</a>
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

