<?php
// Zuvio Global School - PHP Front Controller / Router
require_once dirname(__FILE__) . '/includes/db.php';
require_once dirname(__FILE__) . '/includes/helper.php';

safe_session_start();

// Retrieve route parameters
$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';
if (empty($route)) {
    // Fallback for built-in PHP server or environments without rewrite rules
    $request_uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $route = trim($request_uri, '/');
}

// 1. Admin Routing Namespace Interception
if (strpos($route, 'admin') === 0) {
    $parts = explode('/', $route);
    $sub_route = isset($parts[1]) ? $parts[1] : '';
    
    switch ($sub_route) {
        case 'login':
            $page_slug = 'admin-login';
            include dirname(__FILE__) . '/admin/login.php';
            break;
        case 'logout':
            // Handle GET-based logout for Sign Out link
            require_once dirname(__FILE__) . '/includes/helper.php';
            require_once dirname(__FILE__) . '/includes/auth.php';
            safe_session_start();
            if (isset($_SESSION['user_id'])) {
                log_audit('USER_LOGOUT', 'auth', 'users', $_SESSION['user_id'], null, null, 'User logged out via direct link');
            }
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            header('Location: /admin/login');
            exit;
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
        case 'users':
            $page_slug = 'admin-users';
            include dirname(__FILE__) . '/admin/users.php';
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
