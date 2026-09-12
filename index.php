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

    case 'about-zuvio':
        $page_slug = 'about-zuvio';
        include dirname(__FILE__) . '/pages/about-zuvio.php';
        break;

    case 'our-team':
    case 'team':
        $page_slug = 'our-team';
        include dirname(__FILE__) . '/pages/our-team.php';
        break;

    case 'founder-message':
    case 'founders-message':
        $page_slug = 'founder-message';
        include dirname(__FILE__) . '/pages/founder-message.php';
        break;

    case 'affiliations-accreditations':
    case 'accreditations':
        $page_slug = 'affiliations-accreditations';
        include dirname(__FILE__) . '/pages/affiliations-accreditations.php';
        break;

    case 'academics':
        $page_slug = 'academics';
        include dirname(__FILE__) . '/pages/academics.php';
        break;

    case 'technology':
        $page_slug = 'technology';
        include dirname(__FILE__) . '/pages/technology.php';
        break;
        
    case 'our-curriculum':
    case 'curriculum':
        $page_slug = 'our-curriculum';
        include dirname(__FILE__) . '/pages/curriculum.php';
        break;

    case 'special-education':
        $page_slug = 'special-education';
        include dirname(__FILE__) . '/pages/special-education.php';
        break;

    case 'electives':
        $page_slug = 'electives';
        include dirname(__FILE__) . '/pages/electives.php';
        break;

    case 'nep-2020':
        $page_slug = 'nep-2020';
        include dirname(__FILE__) . '/pages/nep-2020.php';
        break;

    case 'resources':
        $page_slug = 'resources';
        include dirname(__FILE__) . '/pages/resources.php';
        break;

    case 'admissions':
    case 'admission':
        $page_slug = 'admissions';
        include dirname(__FILE__) . '/pages/admissions.php';
        break;

    case 'enrol-now':
    case 'enrol':
        $page_slug = 'admissions-enrol';
        include dirname(__FILE__) . '/pages/admissions-enrol.php';
        break;

    case 'eligibility':
        $page_slug = 'admissions-eligibility';
        include dirname(__FILE__) . '/pages/admissions-eligibility.php';
        break;

    case 'calendar':
        $page_slug = 'admissions-calendar';
        include dirname(__FILE__) . '/pages/admissions-calendar.php';
        break;

    case 'fees':
        $page_slug = 'admissions-fees';
        include dirname(__FILE__) . '/pages/admissions-fees.php';
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

    case 'co-curricular':
    case 'clubs':
        $page_slug = 'beyond-cocurricular';
        include dirname(__FILE__) . '/pages/beyond-cocurricular.php';
        break;

    case 'student-achievers':
    case 'achievers':
        $page_slug = 'beyond-achievers';
        include dirname(__FILE__) . '/pages/beyond-achievers.php';
        break;

    case 'gallery':
        $page_slug = 'beyond-gallery';
        include dirname(__FILE__) . '/pages/beyond-gallery.php';
        break;

    case 'virtual-classroom':
    case 'inside-virtual-classroom':
        $page_slug = 'beyond-virtual-classroom';
        include dirname(__FILE__) . '/pages/beyond-virtual-classroom.php';
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
        // Handle potential nested routing like blogs/{slug}, about/{sub}, academics/{sub}, or admissions/{sub}
        $parts = explode('/', $route);
        if ($parts[0] === 'blogs' && isset($parts[1])) {
            $blog_slug = $parts[1];
            $page_slug = 'blogs';
            include dirname(__FILE__) . '/pages/blog-detail.php';
        } elseif (($parts[0] === 'about' || $parts[0] === 'about-us') && isset($parts[1])) {
            $sub = $parts[1];
            if ($sub === 'about-zuvio') {
                $page_slug = 'about-zuvio';
                include dirname(__FILE__) . '/pages/about-zuvio.php';
            } elseif ($sub === 'our-team' || $sub === 'team') {
                $page_slug = 'our-team';
                include dirname(__FILE__) . '/pages/our-team.php';
            } elseif ($sub === 'founder-message' || $sub === 'founders-message') {
                $page_slug = 'founder-message';
                include dirname(__FILE__) . '/pages/founder-message.php';
            } elseif ($sub === 'affiliations-accreditations' || $sub === 'accreditations') {
                $page_slug = 'affiliations-accreditations';
                include dirname(__FILE__) . '/pages/affiliations-accreditations.php';
            } else {
                $profile_slug = $sub;
                $page_slug = 'about';
                include dirname(__FILE__) . '/pages/about-detail.php';
            }
        } elseif ($parts[0] === 'academics' && isset($parts[1])) {
            $sub = $parts[1];
            if ($sub === 'technology') {
                $page_slug = 'technology';
                include dirname(__FILE__) . '/pages/technology.php';
            } elseif ($sub === 'curriculum' || $sub === 'our-curriculum') {
                $page_slug = 'our-curriculum';
                include dirname(__FILE__) . '/pages/curriculum.php';
            } elseif ($sub === 'special-education') {
                $page_slug = 'special-education';
                include dirname(__FILE__) . '/pages/special-education.php';
            } elseif ($sub === 'electives') {
                $page_slug = 'electives';
                include dirname(__FILE__) . '/pages/electives.php';
            } elseif ($sub === 'nep-2020') {
                $page_slug = 'nep-2020';
                include dirname(__FILE__) . '/pages/nep-2020.php';
            } elseif ($sub === 'resources') {
                $page_slug = 'resources';
                include dirname(__FILE__) . '/pages/resources.php';
            } else {
                $page_slug = 'academics';
                include dirname(__FILE__) . '/pages/academics.php';
            }
        } elseif (($parts[0] === 'admissions' || $parts[0] === 'admission') && isset($parts[1])) {
            $sub = $parts[1];
            if ($sub === 'enrol-now' || $sub === 'enrol') {
                $page_slug = 'admissions-enrol';
                include dirname(__FILE__) . '/pages/admissions-enrol.php';
            } elseif ($sub === 'eligibility') {
                $page_slug = 'admissions-eligibility';
                include dirname(__FILE__) . '/pages/admissions-eligibility.php';
            } elseif ($sub === 'calendar') {
                $page_slug = 'admissions-calendar';
                include dirname(__FILE__) . '/pages/admissions-calendar.php';
            } elseif ($sub === 'fees' || $sub === 'fee-structure') {
                $page_slug = 'admissions-fees';
                include dirname(__FILE__) . '/pages/admissions-fees.php';
            } else {
                $page_slug = 'admissions';
                include dirname(__FILE__) . '/pages/admissions.php';
            }
        } elseif (($parts[0] === 'beyond' || $parts[0] === 'zuvio-beyond') && isset($parts[1])) {
            $sub = $parts[1];
            if ($sub === 'co-curricular' || $sub === 'clubs') {
                $page_slug = 'beyond-cocurricular';
                include dirname(__FILE__) . '/pages/beyond-cocurricular.php';
            } elseif ($sub === 'student-achievers' || $sub === 'achievers') {
                $page_slug = 'beyond-achievers';
                include dirname(__FILE__) . '/pages/beyond-achievers.php';
            } elseif ($sub === 'gallery') {
                $page_slug = 'beyond-gallery';
                include dirname(__FILE__) . '/pages/beyond-gallery.php';
            } elseif ($sub === 'virtual-classroom' || $sub === 'inside-virtual-classroom') {
                $page_slug = 'beyond-virtual-classroom';
                include dirname(__FILE__) . '/pages/beyond-virtual-classroom.php';
            } else {
                $page_slug = 'zuvio-beyond';
                include dirname(__FILE__) . '/pages/beyond.php';
            }
        } else {
            header('HTTP/1.1 404 Not Found');
            include dirname(__FILE__) . '/pages/404.php';
        }
        break;
}
