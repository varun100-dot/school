<?php
// Zuvio Global School - Helper functions & Utilities

function safe_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        // Enforce cookie security rules
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
        }
        session_start();
    }
}

// Escapes output html strings to prevent XSS
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Generate CSRF token for forms
function get_csrf_token() {
    safe_session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validate_csrf_token($token) {
    safe_session_start();
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Get site settings key-value pair from database
function get_setting($key, $default = '') {
    global $db;
    if (!$db) return $default;
    try {
        $stmt = $db->prepare("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Exception $e) {
        error_log("[Settings Error] " . $e->getMessage());
        return $default;
    }
}

// Retrieve page SEO metadata
function get_page_seo($page_slug) {
    global $db;
    $default = [
        'seo_title' => 'Zuvio Global School | Learning Beyond Boundaries',
        'meta_description' => 'A future-ready online school combining CBSE curriculum with personalized pathways.',
        'canonical_url' => 'https://zuvioglobalschool.com/',
        'og_title' => 'Zuvio Global School',
        'og_description' => 'Learning Beyond Boundaries',
        'og_image' => '/assets/images/logo.png',
        'index_status' => 'index, follow'
    ];
    
    if (!$db) return $default;
    
    try {
        $stmt = $db->prepare("
            SELECT s.* FROM `page_seo` s
            JOIN `pages` p ON p.id = s.page_id
            WHERE p.slug = ? LIMIT 1
        ");
        $stmt->execute([$page_slug]);
        $row = $stmt->fetch();
        if ($row) {
            return array_merge($default, array_filter($row));
        }
    } catch (Exception $e) {
        error_log("[SEO Error] " . $e->getMessage());
    }
    return $default;
}
