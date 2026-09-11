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
        case 'announcements':
        case 'announcements.php':
            $page_slug = 'admin-announcements';
            include dirname(__FILE__) . '/admin/announcements.php';
            break;
        case 'migrate':
        case 'migrate.php':
            $page_slug = 'admin-migrate';
            include dirname(__FILE__) . '/admin/migrate.php';
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
        case 'homepage':
        case 'homepage.php':
            $page_slug = 'admin-homepage';
            include dirname(__FILE__) . '/admin/homepage.php';
            break;
        case 'faqs':
        case 'faqs.php':
            $page_slug = 'admin-faqs';
            include dirname(__FILE__) . '/admin/faqs.php';
            break;
        case 'testimonials':
        case 'testimonials.php':
            $page_slug = 'admin-testimonials';
            include dirname(__FILE__) . '/admin/testimonials.php';
            break;
        case 'accreditations':
        case 'accreditations.php':
            $page_slug = 'admin-accreditations';
            include dirname(__FILE__) . '/admin/accreditations.php';
            break;
        case 'about-cms':
        case 'about-cms.php':
            $page_slug = 'admin-about-cms';
            include dirname(__FILE__) . '/admin/about-cms.php';
            break;
        case 'academics-cms':
        case 'academics-cms.php':
            $page_slug = 'admin-academics-cms';
            include dirname(__FILE__) . '/admin/academics-cms.php';
            break;
        case 'admissions-cms':
        case 'admissions-cms.php':
            $page_slug = 'admin-admissions-cms';
            include dirname(__FILE__) . '/admin/admissions-cms.php';
            break;
        case 'beyond-cms':
        case 'beyond-cms.php':
            $page_slug = 'admin-beyond-cms';
            include dirname(__FILE__) . '/admin/beyond-cms.php';
            break;
        case 'profiles':
            $page_slug = 'admin-profiles';
            include dirname(__FILE__) . '/admin/profiles.php';
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

    case 'founder-message':
    case 'founders-message':
        $page_slug = 'founder-message';
        include dirname(__FILE__) . '/pages/founder-message.php';
        break;

    case 'academics':
        $page_slug = 'academics';
        include dirname(__FILE__) . '/pages/academics.php';
        break;
        
    case 'our-curriculum':
    case 'curriculum':
        $page_slug = 'our-curriculum';
        include dirname(__FILE__) . '/pages/curriculum.php';
        break;

    case 'admissions':
    case 'admission':
        $page_slug = 'admissions';
        include dirname(__FILE__) . '/pages/admissions.php';
        break;

    case 'faq':
    case 'faqs':
        $page_slug = 'faq';
        include dirname(__FILE__) . '/pages/faq.php';
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
        // Handle potential nested routing like blogs/{slug} or about/{slug}
        $parts = explode('/', $route);
        if ($parts[0] === 'blogs' && isset($parts[1])) {
            $blog_slug = $parts[1];
            $page_slug = 'blogs';
            include dirname(__FILE__) . '/pages/blog-detail.php';
        } elseif (($parts[0] === 'about' || $parts[0] === 'about-us') && isset($parts[1])) {
            $profile_slug = $parts[1];
            $page_slug = 'about';
            include dirname(__FILE__) . '/pages/about-detail.php';
        } elseif ($parts[0] === 'academics' && isset($parts[1])) {
            $academic_sub = $parts[1];
            if ($academic_sub === 'curriculum') {
                $page_slug = 'our-curriculum';
                include dirname(__FILE__) . '/pages/curriculum.php';
            } else {
                $page_slug = 'academics';
                include dirname(__FILE__) . '/pages/academics.php';
            }
        } else {
            header('HTTP/1.1 404 Not Found');
            include dirname(__FILE__) . '/pages/404.php';
        }
        break;
}
