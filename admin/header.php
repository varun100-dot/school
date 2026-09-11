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

    @media (max-width: 900px) {
      body {
        flex-direction: column;
      }
      .admin-sidebar {
        width: 100%;
        height: auto;
        position: static;
      }
      .sidebar-menu {
        flex-direction: row;
        flex-wrap: wrap;
        padding: 0.75rem;
        gap: 0.5rem;
      }
      .sidebar-item {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
      }
      .sidebar-user {
        display: none;
      }
      .admin-main {
        height: auto;
        overflow-y: visible;
      }
      .admin-body {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <!-- Admin Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <img src="<?php echo h($logo_path); ?>" alt="Zuvio Admin" class="sidebar-logo">
    </div>
    
    <nav class="sidebar-menu">
      <div class="sidebar-heading">Main Overview</div>
      <?php if (has_permission('dashboard.view') || true): ?>
        <a href="/admin" class="sidebar-item <?php echo $current_page === 'admin-dashboard' ? 'active' : ''; ?>">Dashboard</a>
      <?php endif; ?>
      <a href="/admin/homepage.php" class="sidebar-item <?php echo $current_page === 'admin-homepage' ? 'active' : ''; ?>">Homepage Sections CMS</a>

      <div class="sidebar-heading">About Us</div>
      <a href="/admin/about-cms.php" class="sidebar-item <?php echo $current_page === 'admin-about-cms' ? 'active' : ''; ?>">About Zuvio & Story</a>
      <a href="/admin/profiles.php" class="sidebar-item <?php echo $current_page === 'admin-profiles' ? 'active' : ''; ?>">Leadership Team</a>
      <a href="/admin/about-cms.php?tab=founder" class="sidebar-item">Founder’s Message</a>
      <a href="/admin/accreditations.php" class="sidebar-item <?php echo $current_page === 'admin-accreditations' ? 'active' : ''; ?>">Affiliations & Accreditations</a>

      <div class="sidebar-heading">Academics</div>
      <a href="/admin/academics-cms.php" class="sidebar-item <?php echo $current_page === 'admin-academics-cms' ? 'active' : ''; ?>">Academics Architecture</a>

      <div class="sidebar-heading">Admissions</div>
      <?php if (has_permission('enquiries.view') || true): ?>
        <a href="/admin/enquiries" class="sidebar-item <?php echo $current_page === 'admin-enquiries' ? 'active' : ''; ?>">Enrolment & Enquiries</a>
      <?php endif; ?>
      <a href="/admin/faqs.php" class="sidebar-item <?php echo $current_page === 'admin-faqs' ? 'active' : ''; ?>">Parent FAQs (18 Items)</a>
      <a href="/admin/admissions-cms.php" class="sidebar-item <?php echo $current_page === 'admin-admissions-cms' ? 'active' : ''; ?>">Admissions Settings</a>

      <div class="sidebar-heading">Beyond</div>
      <a href="/admin/beyond-cms.php" class="sidebar-item <?php echo $current_page === 'admin-beyond-cms' ? 'active' : ''; ?>">Beyond Programmes & Clubs</a>

      <div class="sidebar-heading">Media & Engagement</div>
      <a href="/admin/testimonials.php" class="sidebar-item <?php echo $current_page === 'admin-testimonials' ? 'active' : ''; ?>">Parent Testimonials</a>
      <?php if (has_permission('media.view') || true): ?>
        <a href="/admin/media" class="sidebar-item <?php echo $current_page === 'admin-media' ? 'active' : ''; ?>">Media Manager (IMG/VID/PDF)</a>
      <?php endif; ?>
      <?php if (has_permission('blogs.view') || true): ?>
        <a href="/admin/blogs" class="sidebar-item <?php echo $current_page === 'admin-blogs' ? 'active' : ''; ?>">Manage Blogs & News</a>
      <?php endif; ?>
      <a href="/admin/announcements.php" class="sidebar-item <?php echo $current_page === 'admin-announcements' ? 'active' : ''; ?>">Announcements Strip</a>

      <div class="sidebar-heading">System & Admin</div>
      <?php if (has_permission('users.view') || true): ?>
        <a href="/admin/users" class="sidebar-item <?php echo $current_page === 'admin-users' ? 'active' : ''; ?>">User Management</a>
      <?php endif; ?>
      <?php if (has_permission('settings.view') || true): ?>
        <a href="/admin/settings" class="sidebar-item <?php echo $current_page === 'admin-settings' ? 'active' : ''; ?>">Site Settings</a>
      <?php endif; ?>
      <a href="/admin/migrate.php" class="sidebar-item <?php echo $current_page === 'admin-migrate' ? 'active' : ''; ?>">Database Migrations</a>
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
      <h2 style="font-size: 1.15rem; color: var(--color-navy); font-family: var(--font-secondary);">System Management Dashboard</h2>
      
      <!-- Sign Out link -->
      <a href="/admin/logout" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem; border-color: #EF4444; color: #EF4444;" onclick="return confirm('Sign out?');">Sign Out</a>
    </div>
    <div class="admin-body">
