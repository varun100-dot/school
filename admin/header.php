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
      padding: 1.5rem 0.75rem;
      flex-grow: 1;
    }
    .sidebar-item {
      display: flex;
      align-items: center;
      padding: 0.8rem 1rem;
      font-size: 0.9rem;
      font-weight: 500;
      color: #94A3B8;
      border-radius: var(--radius-sm);
      transition: all var(--transition-fast);
    }
    .sidebar-item:hover, .sidebar-item.active {
      color: #FFFFFF;
      background-color: rgba(255,255,255,0.06);
    }
    .sidebar-item.active {
      border-left: 4px solid var(--color-gold);
      background-color: rgba(255,255,255,0.10);
    }
    .sidebar-user {
      padding: 1.5rem;
      border-top: 1px solid rgba(255,255,255,0.08);
      font-size: 0.8rem;
      color: #94A3B8;
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
      <?php if (has_permission('dashboard.view')): ?>
        <a href="/admin" class="sidebar-item <?php echo $current_page === 'admin-dashboard' ? 'active' : ''; ?>">Dashboard</a>
      <?php endif; ?>
      <?php if (has_permission('blogs.view')): ?>
        <a href="/admin/blogs" class="sidebar-item <?php echo $current_page === 'admin-blogs' ? 'active' : ''; ?>">Manage Blogs</a>
      <?php endif; ?>
      <?php if (has_permission('hero.view')): ?>
        <a href="/admin/hero" class="sidebar-item <?php echo $current_page === 'admin-hero' ? 'active' : ''; ?>">Homepage Hero</a>
      <?php endif; ?>
      <?php if (has_permission('enquiries.view')): ?>
        <a href="/admin/enquiries" class="sidebar-item <?php echo $current_page === 'admin-enquiries' ? 'active' : ''; ?>">Enquiries</a>
      <?php endif; ?>
      <?php if (has_permission('media.view')): ?>
        <a href="/admin/media" class="sidebar-item <?php echo $current_page === 'admin-media' ? 'active' : ''; ?>">Media Manager</a>
      <?php endif; ?>
      <?php if (has_permission('users.view')): ?>
        <a href="/admin/users" class="sidebar-item <?php echo $current_page === 'admin-users' ? 'active' : ''; ?>">User Management</a>
      <?php endif; ?>
      <?php if (has_permission('settings.view')): ?>
        <a href="/admin/settings" class="sidebar-item <?php echo $current_page === 'admin-settings' ? 'active' : ''; ?>">Site Settings</a>
      <?php endif; ?>
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
      
      <!-- Safe custom post logout trigger -->
      <form method="POST" action="/admin" style="margin: 0;">
        <button type="submit" name="logout" class="btn btn-outline" style="padding: 0.4rem 1rem; font-size: 0.8rem; border-color: #EF4444; color: #EF4444;">Sign Out</button>
      </form>
    </div>
    <div class="admin-body">
