<?php
// Zuvio Global School - PHP Front Controller / Router
require_once dirname(__FILE__) . '/includes/db.php';
require_once dirname(__FILE__) . '/includes/helper.php';

safe_session_start();

// Retrieve route parameters
$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// 1. Admin Routing Namespace Interception
if (strpos($route, 'admin') === 0) {
    $parts = explode('/', $route);
    $sub_route = isset($parts[1]) ? $parts[1] : '';
    
    switch ($sub_route) {
        case 'login':
            $page_slug = 'admin-login';
            include dirname(__FILE__) . '/admin/login.php';
            break;
        case '':
            $page_slug = 'admin-dashboard';
            include dirname(__FILE__) . '/admin/index.php';
            break;
        case 'blogs':
            $page_slug = 'admin-blogs';
            include dirname(__FILE__) . '/admin/blogs.php';
            break;
        case 'hero':
            $page_slug = 'admin-hero';
            include dirname(__FILE__) . '/admin/hero.php';
            break;
        case 'enquiries':
            $page_slug = 'admin-enquiries';
            include dirname(__FILE__) . '/admin/enquiries.php';
            break;
        case 'settings':
            $page_slug = 'admin-settings';
            include dirname(__FILE__) . '/admin/settings.php';
            break;
        case 'media':
            $page_slug = 'admin-media';
            include dirname(__FILE__) . '/admin/media.php';
            break;
        default:
            header('HTTP/1.1 404 Not Found');
            include dirname(__FILE__) . '/pages/404.php';
            break;
    }
    exit;
}

// 2. Public Routing Namespace
switch ($route) {
    case '':
    case 'home':
        $page_slug = 'home';
        include dirname(__FILE__) . '/pages/home.php';
        break;
        
    case 'about':
    case 'about-us':
        $page_slug = 'about';
        include dirname(__FILE__) . '/pages/about.php';
        break;
        
    case 'our-curriculum':
    case 'curriculum':
        $page_slug = 'our-curriculum';
        include dirname(__FILE__) . '/pages/curriculum.php';
        break;
        
    case 'zuvio-beyond':
    case 'beyond':
        $page_slug = 'zuvio-beyond';
        include dirname(__FILE__) . '/pages/beyond.php';
        break;
        
    case 'contact':
    case 'contact-us':
        $page_slug = 'contact';
        include dirname(__FILE__) . '/pages/contact.php';
        break;
        
    case 'blogs':
        $page_slug = 'blogs';
        include dirname(__FILE__) . '/pages/blogs.php';
        break;
        
    default:
        // Handle potential nested routing like blogs/{slug}
        $parts = explode('/', $route);
        if ($parts[0] === 'blogs' && isset($parts[1])) {
            $blog_slug = $parts[1];
            $page_slug = 'blogs';
            include dirname(__FILE__) . '/pages/blog-detail.php';
        } else {
            header('HTTP/1.1 404 Not Found');
            include dirname(__FILE__) . '/pages/404.php';
        }
        break;
}
