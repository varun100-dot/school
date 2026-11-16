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

function generate_csrf_token() {
    return get_csrf_token();
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . h(get_csrf_token()) . '">';
}

// Validate CSRF token
function validate_csrf_token($token) {
    safe_session_start();
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Automatically migrate and sanitize school-wide grade range references from Grade 8 to Grade 12.
 * Ensures database-backed settings and CMS JSON blobs seamlessly reflect the Grade 12 offering.
 */
function sanitize_grade_range_strings($data) {
    if (is_string($data)) {
        $replacements = [
            'Kindergarten to Grade 8th' => 'Kindergarten to Grade 12',
            'Kindergarten to Grade 8' => 'Kindergarten to Grade 12',
            'Kindergarten through Grade 8' => 'Kindergarten through Grade 12',
            'KG to 8th' => 'KG to 12th',
            'KG to Grade 8' => 'KG to Grade 12',
            'KG–8' => 'KG–12',
            'KG-8' => 'KG-12',
            'K–8' => 'K–12',
            'K-8' => 'K-12',
            'Nursery to Grade 8th' => 'KG to 12th',
            'Nursery to Grade 8' => 'Nursery to Grade 12',
            'NURSERY – GRADE 8' => 'NURSERY – GRADE 12',
            'NURSERY - GRADE 8' => 'NURSERY - GRADE 12',
            'Nursery–Grade 8' => 'Nursery–Grade 12',
            'Nursery - Grade 8' => 'Nursery - Grade 12',
            'grades K to 8.' => 'grades K to 12.',
            'Grades K to 8.' => 'Grades K to 12.',
            'grades K to 8' => 'grades K to 12',
            'Grades K to 8' => 'Grades K to 12',
            'Accredited K–8 Global School' => 'Accredited K–12 Global School',
            'Accredited K-8 Global School' => 'Accredited K-12 Global School',
            'Online K–8 Homeschooling' => 'Online K–12 Homeschooling',
            'Online K-8 Homeschooling' => 'Online K-12 Homeschooling',
            'flexible K–8 education' => 'flexible K–12 education',
            'flexible K-8 education' => 'flexible K-12 education',
            'our K–8 curriculum' => 'our K–12 curriculum',
            'our K-8 curriculum' => 'our K-12 curriculum',
            'K–8 online schooling' => 'K–12 online schooling',
            'K-8 online schooling' => 'K-12 online schooling',
            'application form for K–8.' => 'application form for K–12.',
            'application form for K-8.' => 'application form for K-12.',
            'Early Years to Grade 8 live class timings' => 'Early Years to Grade 12 live class timings',
            'Early Years to Grade 8' => 'Early Years to Grade 12',
            'All Stages (KG to Grade 8)' => 'All Stages (KG to Grade 12)',
            'The Zuvio Graduate — by the end of Grade 8' => 'The Zuvio Graduate — by the end of Grade 12',
            'by the end of Grade 8' => 'by the end of Grade 12',
            'Pre-Primary to Grade 8 Fee Matrix' => 'Pre-Primary to Grade 12 Fee Matrix',
            'Pre-Primary to Grade 8 quarterly tuition' => 'Pre-Primary to Grade 12 quarterly tuition',
            'matrix for Pre-Primary to Grade 8' => 'matrix for Pre-Primary to Grade 12',
            'Pre-Primary to Grade 8' => 'Pre-Primary to Grade 12',
            'CBSE Aligned K–8' => 'CBSE Aligned K–12',
            'CBSE Aligned K-8' => 'CBSE Aligned K-12',
        ];
        foreach ($replacements as $search => $replace) {
            $data = str_ireplace($search, $replace, $data);
        }
        return $data;
    }
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = sanitize_grade_range_strings($v);
        }
    }
    return $data;
}

// Get site settings key-value pair from database
function get_setting($key, $default = '') {
    global $db;
    if (!$db) {
        safe_session_start();
        $val = $_SESSION['mock_settings'][$key] ?? $default;
        return sanitize_grade_range_strings($val);
    }
    try {
        $stmt = $db->prepare("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        $val = $row ? $row['setting_value'] : $default;
        return sanitize_grade_range_strings($val);
    } catch (Exception $e) {
        error_log("[Settings Error] " . $e->getMessage());
        return sanitize_grade_range_strings($default);
    }
}

// Set or update a site setting in database (or fallback session)
function set_setting($key, $value, $description = null) {
    global $db;
    safe_session_start();
    $_SESSION['mock_settings'][$key] = $value;
    
    if (!$db) return true;
    try {
        $stmt = $db->prepare("SELECT `id` FROM `site_settings` WHERE `setting_key` = ? LIMIT 1");
        $stmt->execute([$key]);
        if ($stmt->fetch()) {
            if ($description !== null) {
                $up = $db->prepare("UPDATE `site_settings` SET `setting_value` = ?, `description` = ? WHERE `setting_key` = ?");
                return $up->execute([$value, $description, $key]);
            } else {
                $up = $db->prepare("UPDATE `site_settings` SET `setting_value` = ? WHERE `setting_key` = ?");
                return $up->execute([$value, $key]);
            }
        } else {
            $ins = $db->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`, `description`) VALUES (?, ?, ?)");
            return $ins->execute([$key, $value, $description]);
        }
    } catch (Exception $e) {
        error_log("[Set Setting Error] " . $e->getMessage());
        return false;
    }
}

// Retrieve JSON-encoded setting with default fallback
function get_json_setting($key, $default = []) {
    $raw = get_setting($key, null);
    if ($raw === null || $raw === '') {
        safe_session_start();
        $val = $_SESSION['mock_' . $key] ?? $default;
        return sanitize_grade_range_strings($val);
    }
    $decoded = json_decode($raw, true);
    $val = is_array($decoded) ? $decoded : $default;
    return sanitize_grade_range_strings($val);
}

// Persist structured JSON setting directly into database
function set_json_setting($key, $data, $description = null) {
    safe_session_start();
    $_SESSION['mock_' . $key] = $data;
    $encoded = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return set_setting($key, $encoded, $description);
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
    
    if (!$db) return sanitize_grade_range_strings($default);
    
    try {
        $stmt = $db->prepare("
            SELECT s.* FROM `page_seo` s
            JOIN `pages` p ON p.id = s.page_id
            WHERE p.slug = ? LIMIT 1
        ");
        $stmt->execute([$page_slug]);
        $row = $stmt->fetch();
        if ($row) {
            return sanitize_grade_range_strings(array_merge($default, array_filter($row)));
        }
    } catch (Exception $e) {
        error_log("[SEO Error] " . $e->getMessage());
    }
    return sanitize_grade_range_strings($default);
}

// Render standardized breadcrumbs navigation
function render_breadcrumbs(array $crumbs) {
    if (empty($crumbs)) return;
    echo '<div class="site-breadcrumbs" style="background-color: var(--pastel-blue); padding: 0.85rem 0; border-bottom: 1px solid var(--color-border);">';
    echo '<div class="container" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--color-muted); flex-wrap: wrap;">';
    $total = count($crumbs);
    foreach ($crumbs as $i => $crumb) {
        $is_last = ($i === $total - 1);
        if ($is_last || empty($crumb['url'])) {
            echo '<span style="color: var(--color-navy); font-weight: 700;">' . h($crumb['label']) . '</span>';
        } else {
            echo '<a href="' . h($crumb['url']) . '" style="color: var(--color-teal); text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color=\'var(--color-navy)\'" onmouseout="this.style.color=\'var(--color-teal)\'">' . h($crumb['label']) . '</a>';
            echo '<span style="color: var(--color-gold); font-weight: bold; font-size: 0.75rem;">&rsaquo;</span>';
        }
    }
    echo '</div></div>';
}

/**
 * Sanitize rich text HTML for blog articles before storage or rendering.
 * Strips script tags, unsafe tags, on* event handlers, unsafe protocols (javascript:, data:, etc.)
 * Preserves safe formatting, typography, links, images, tables, lists, colors, highlights and safe styles.
 */
function sanitize_blog_html($html) {
    if ($html === null || trim($html) === '') {
        return '';
    }

    // Pre-strip dangerous tags and comments to prevent parser confusion
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
    $html = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $html);

    // Whitelist of allowed HTML tags
    $allowed_tags = [
        'p', 'br', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'strong', 'b', 'em', 'i', 'u', 's', 'del', 'strike', 'span', 'sub', 'sup', 'mark',
        'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'kbd',
        'a', 'img', 'figure', 'figcaption',
        'div', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption'
    ];

    // Allowed attributes per tag or globally
    $global_allowed_attrs = ['class', 'id', 'title', 'dir', 'style'];
    $tag_specific_attrs = [
        'a' => ['href', 'target', 'rel', 'title', 'download'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'loading', 'style', 'class'],
        'table' => ['cellpadding', 'cellspacing', 'border'],
        'th' => ['colspan', 'rowspan', 'align', 'valign', 'scope'],
        'td' => ['colspan', 'rowspan', 'align', 'valign'],
        'ol' => ['start', 'type'],
    ];

    // Allowed CSS style properties
    $allowed_styles = [
        'color', 'background-color', 'font-family', 'font-size', 'font-weight', 'font-style',
        'text-decoration', 'text-align', 'line-height', 'letter-spacing',
        'margin', 'margin-left', 'margin-right', 'margin-top', 'margin-bottom',
        'padding', 'padding-left', 'padding-right', 'padding-top', 'padding-bottom',
        'border', 'border-left', 'border-right', 'border-top', 'border-bottom',
        'border-color', 'border-width', 'border-style', 'border-radius',
        'width', 'max-width', 'min-width', 'height', 'max-height', 'min-height',
        'float', 'display', 'vertical-align', 'list-style-type'
    ];

    // Gracefully handle environments missing DOMDocument/libxml extensions
    if (!class_exists('DOMDocument') || !function_exists('libxml_use_internal_errors')) {
        error_log("[Blog Sanitizer Warning] DOMDocument or libxml extension not available. Falling back to regex sanitization.");
        $fallback = strip_tags($html, '<p><br><hr><h1><h2><h3><h4><h5><h6><strong><b><em><i><u><s><del><strike><span><sub><sup><mark><ul><ol><li><blockquote><pre><code><kbd><a><img><figure><figcaption><div><table><thead><tbody><tfoot><tr><th><td><caption>');
        $fallback = preg_replace('/<([a-z][a-z0-9]*)[^>]*?(\bon\w+\s*=\s*[\'"][^\'"]*[\'"][^>]*?)>/i', '<$1>', $fallback);
        $fallback = preg_replace('/href\s*=\s*[\'"]\s*(javascript|vbscript|data):[^\'"]*[\'"]/i', 'href="#"', $fallback);
        $fallback = preg_replace('/src\s*=\s*[\'"]\s*(javascript|vbscript):[^\'"]*[\'"]/i', 'src=""', $fallback);
        return $fallback;
    }

    try {
        $dom = new DOMDocument();
        $prev_errors = libxml_use_internal_errors(true);

        // Encode as UTF-8 properly for DOMDocument
        $wrapped = '<?xml encoding="utf-8" ?><div>' . $html . '</div>';
        $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev_errors);

        // Helper to sanitize CSS inline styles
        $clean_style = function($style_string) use ($allowed_styles) {
            if (empty($style_string)) return '';
        if (preg_match('/(expression|javascript|behavior|url\s*\(|@import|-moz-binding|<|>)/i', $style_string)) {
            return '';
        }
        $rules = explode(';', $style_string);
        $safe_rules = [];
        foreach ($rules as $rule) {
            $parts = explode(':', $rule, 2);
            if (count($parts) === 2) {
                $prop = strtolower(trim($parts[0]));
                $val = trim($parts[1]);
                if (in_array($prop, $allowed_styles)) {
                    if (!preg_match('/(javascript|expression|behavior|url|<|>)/i', $val)) {
                        $safe_rules[] = $prop . ': ' . $val;
                    }
                }
            }
        }
        return !empty($safe_rules) ? implode('; ', $safe_rules) : '';
    };

    // Recursive node cleaner
    $clean_node = function(DOMNode $node) use (&$clean_node, $allowed_tags, $global_allowed_attrs, $tag_specific_attrs, $clean_style) {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $tag_name = strtolower($node->nodeName);
            if (!in_array($tag_name, $allowed_tags)) {
                // If tag is dangerous, remove completely
                if (in_array($tag_name, ['script', 'style', 'iframe', 'object', 'embed', 'applet', 'meta', 'link', 'form', 'input', 'button', 'select', 'textarea'])) {
                    $node->parentNode->removeChild($node);
                    return;
                }
                // Otherwise replace with child nodes (unwrap)
                $parent = $node->parentNode;
                while ($node->hasChildNodes()) {
                    $child = $node->firstChild;
                    $node->removeChild($child);
                    $clean_node($child);
                    $parent->insertBefore($child, $node);
                }
                $parent->removeChild($node);
                return;
            }

            // Allowed tag: clean attributes
            if ($node->hasAttributes()) {
                $allowed_for_this = array_merge(
                    $global_allowed_attrs,
                    $tag_specific_attrs[$tag_name] ?? []
                );

                $attrs_to_remove = [];
                foreach ($node->attributes as $attr) {
                    $attr_name = strtolower($attr->nodeName);
                    // Remove any on* event handler or unwhitelisted attribute
                    if (strpos($attr_name, 'on') === 0 || !in_array($attr_name, $allowed_for_this)) {
                        $attrs_to_remove[] = $attr->nodeName;
                        continue;
                    }

                    // Check URL schemes on href and src
                    if ($attr_name === 'href' || $attr_name === 'src') {
                        $url = trim($attr->nodeValue);
                        if (preg_match('#^\s*(javascript|vbscript|data(?!\s*:\s*image)):#i', $url)) {
                            $attrs_to_remove[] = $attr->nodeName;
                            continue;
                        }
                    }

                    // Clean style attribute
                    if ($attr_name === 'style') {
                        $cleaned_style = $clean_style($attr->nodeValue);
                        if (!empty($cleaned_style)) {
                            $attr->nodeValue = $cleaned_style;
                        } else {
                            $attrs_to_remove[] = $attr->nodeName;
                        }
                    }
                }

                foreach ($attrs_to_remove as $attr_name) {
                    $node->removeAttribute($attr_name);
                }

                // If link has target="_blank", enforce rel="noopener noreferrer"
                if ($tag_name === 'a' && $node->getAttribute('target') === '_blank') {
                    $node->setAttribute('rel', 'noopener noreferrer');
                }
            }
        }

        // Clean children
        if ($node->hasChildNodes()) {
            $children = [];
            foreach ($node->childNodes as $child) {
                $children[] = $child;
            }
            foreach ($children as $child) {
                $clean_node($child);
            }
        }
    };

    // Clean all root children inside <div>
    $root = $dom->getElementsByTagName('div')->item(0);
    if ($root) {
        $children = [];
        foreach ($root->childNodes as $child) {
            $children[] = $child;
        }
        foreach ($children as $child) {
            $clean_node($child);
        }

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }
        return trim($output);
    }

        return '';
    } catch (Throwable $e) {
        error_log("[Blog Sanitizer Error] " . $e->getMessage());
        return strip_tags($html, '<p><br><hr><h1><h2><h3><h4><h5><h6><strong><b><em><i><u><s><del><strike><span><sub><sup><mark><ul><ol><li><blockquote><pre><code><kbd><a><img><figure><figcaption><div><table><thead><tbody><tfoot><tr><th><td><caption>');
    }
}

/**
 * Render blog article content safely with support for both modern rich HTML and legacy plain text.
 */
function render_blog_content($content) {
    if ($content === null || trim($content) === '') {
        return '';
    }

    // Detect if content contains HTML tags
    $has_html = (preg_match('/<[a-z][\s\S]*>/i', $content) === 1);

    if ($has_html) {
        return sanitize_blog_html($content);
    } else {
        return nl2br(h($content));
    }
}

/**
 * Generate a clean, URL-friendly slug from a title or text string.
 * Converts to lowercase, strips apostrophes, transforms spaces/punctuation to hyphens,
 * collapses repeated hyphens, and trims leading/trailing hyphens.
 */
function generate_blog_slug($text) {
    if ($text === null) {
        return '';
    }
    // Remove apostrophes so e.g. "Parent's" -> "Parents"
    $text = preg_replace("/['’]/u", '', (string)$text);
    // Convert en-dashes, em-dashes and underscores to hyphens
    $text = preg_replace('/[–—_]/u', '-', $text);
    // Convert to lowercase (multibyte-safe if mbstring extension loaded)
    $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    // Replace non-alphanumeric characters with hyphens
    $text = preg_replace('/[^a-z0-9\-]+/u', '-', $text);
    // Collapse consecutive hyphens
    $text = preg_replace('/-+/', '-', $text);
    // Trim leading/trailing hyphens
    return trim($text, '-');
}

/**
 * Alias for generate_blog_slug for backward compatibility
 */
function slugify($text) {
    return generate_blog_slug($text);
}

/**
 * Normalizes and classifies incoming marketing traffic into standard CRM categories.
 * Supported categories:
 * - Google Ads
 * - Facebook Ads
 * - Instagram Ads
 * - Organic Search
 * - Direct
 * - Referral
 * - Other / Unknown
 */
function classify_marketing_source($utm_source = '', $utm_medium = '', $gclid = '', $fbclid = '', $referrer = '') {
    $src = strtolower(trim((string)$utm_source));
    $med = strtolower(trim((string)$utm_medium));
    $gcl = trim((string)$gclid);
    $fbc = trim((string)$fbclid);
    $ref = trim((string)$referrer);

    // 1. Google Ads Detection (gclid click ID or explicit google cpc/paid/ad params)
    if (!empty($gcl) || (strpos($src, 'google') !== false && preg_match('/^(cpc|ppc|paid|ad|ads|search_ad|display|smart|gdn|search)$/i', $med))) {
        return 'Google Ads';
    }

    // 2. Meta Ads Detection (Instagram vs Facebook Ads)
    if (!empty($fbc)) {
        if (strpos($src, 'instagram') !== false || strpos($src, 'ig') !== false) {
            return 'Instagram Ads';
        }
        return 'Facebook Ads';
    }

    if (strpos($src, 'instagram') !== false || strpos($src, 'ig') !== false) {
        if (preg_match('/^(cpc|ppc|paid|ad|ads|paidsocial|paid-social|sponsored|story|reel)$/i', $med) || !empty($fbc)) {
            return 'Instagram Ads';
        }
    }

    if (strpos($src, 'facebook') !== false || strpos($src, 'fb') !== false || strpos($src, 'meta') !== false) {
        if (preg_match('/^(cpc|ppc|paid|ad|ads|paidsocial|paid-social|sponsored)$/i', $med) || !empty($fbc)) {
            return 'Facebook Ads';
        }
    }

    // 3. Organic Search Detection
    if ($med === 'organic' || $med === 'organic-search') {
        return 'Organic Search';
    }

    $ref_host = parse_url($ref, PHP_URL_HOST) ?: '';
    if (!empty($ref_host)) {
        $search_engines = ['google.', 'bing.', 'yahoo.', 'duckduckgo.', 'baidu.', 'yandex.', 'ask.', 'ecosia.', 'brave.'];
        foreach ($search_engines as $se) {
            if (strpos($ref_host, $se) !== false) {
                // If paid medium was specified, it would have been caught above. Remaining search engine referrals are Organic.
                return 'Organic Search';
            }
        }
    }

    // 4. Referral Traffic Detection
    if ($med === 'referral') {
        return 'Referral';
    }

    if (!empty($ref_host)) {
        $current_host = parse_url($_SERVER['HTTP_HOST'] ?? '', PHP_URL_HOST) ?: ($_SERVER['HTTP_HOST'] ?? '');
        $current_host = preg_replace('/:\d+$/', '', strtolower($current_host));
        $clean_ref_host = preg_replace('/:\d+$/', '', strtolower($ref_host));

        $internal_domains = ['zuvioglobalschool.com', 'www.zuvioglobalschool.com', 'localhost', '127.0.0.1'];
        if (!in_array($clean_ref_host, $internal_domains, true) && $clean_ref_host !== $current_host) {
            return 'Referral';
        }
    }

    // 5. Direct Traffic Detection
    if (empty($src) && empty($med) && empty($gcl) && empty($fbc)) {
        return 'Direct';
    }

    // 6. Any other tagged campaigns (e.g. email, sms, affiliate, offline)
    if (!empty($src) || !empty($med)) {
        return 'Other / Unknown';
    }

    return 'Direct';
}

/**
 * Extracts and sanitizes marketing attribution data from POST/GET/Cookies/Server.
 * Preserves first-touch and last-touch JSON payloads.
 *
 * @return array Associative array of 12 attribution fields
 */
function extract_marketing_attribution_from_request() {
    safe_session_start();

    // Helper to read and sanitize string
    $get_val = function($key, $max_len = 255) {
        $val = $_POST[$key] ?? $_GET[$key] ?? null;
        if ($val === null || $val === '') {
            return null;
        }
        $val = trim((string)$val);
        $val = strip_tags($val);
        return mb_substr($val, 0, $max_len);
    };

    $utm_source   = $get_val('utm_source', 150);
    $utm_medium   = $get_val('utm_medium', 150);
    $utm_campaign = $get_val('utm_campaign', 255);
    $utm_content  = $get_val('utm_content', 255);
    $utm_term     = $get_val('utm_term', 255);
    $gclid        = $get_val('gclid', 255);
    $fbclid       = $get_val('fbclid', 255);
    $landing_page = $get_val('landing_page', 255);
    $referrer     = $get_val('referrer', 500);

    // Fallbacks from environment if not sent via POST fields
    if (empty($landing_page) && isset($_SERVER['REQUEST_URI'])) {
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'zuvioglobalschool.com';
        $landing_page = mb_substr($scheme . '://' . $host . $_SERVER['REQUEST_URI'], 0, 255);
    }
    if (empty($referrer) && isset($_SERVER['HTTP_REFERER'])) {
        $referrer = mb_substr(strip_tags(trim($_SERVER['HTTP_REFERER'])), 0, 500);
    }

    // Read first_touch and last_touch JSON objects
    $first_touch_raw = $_POST['first_touch'] ?? $_COOKIE['zuvio_first_touch'] ?? null;
    $last_touch_raw  = $_POST['last_touch']  ?? $_COOKIE['zuvio_last_touch']  ?? null;

    $clean_touch_json = function($raw) {
        if (empty($raw)) return null;
        if (is_array($raw)) {
            return json_encode($raw, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $decoded = json_decode((string)$raw, true);
        if (is_array($decoded)) {
            // Re-encode sanitized
            $clean = [];
            foreach ($decoded as $k => $v) {
                if (is_scalar($v)) {
                    $clean[strip_tags($k)] = mb_substr(strip_tags((string)$v), 0, 500);
                }
            }
            return json_encode($clean, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        return null;
    };

    $first_touch = $clean_touch_json($first_touch_raw);
    $last_touch  = $clean_touch_json($last_touch_raw);

    // If utm parameters were empty in direct POST but available in last_touch / first_touch, hydrate them
    if (empty($utm_source) && empty($gclid) && empty($fbclid)) {
        $touch_data = json_decode($last_touch ?: ($first_touch ?: '{}'), true);
        if (is_array($touch_data) && !empty($touch_data)) {
            $utm_source   = !empty($touch_data['utm_source'])   ? mb_substr($touch_data['utm_source'], 0, 150) : $utm_source;
            $utm_medium   = !empty($touch_data['utm_medium'])   ? mb_substr($touch_data['utm_medium'], 0, 150) : $utm_medium;
            $utm_campaign = !empty($touch_data['utm_campaign']) ? mb_substr($touch_data['utm_campaign'], 0, 255) : $utm_campaign;
            $utm_content  = !empty($touch_data['utm_content'])  ? mb_substr($touch_data['utm_content'], 0, 255) : $utm_content;
            $utm_term     = !empty($touch_data['utm_term'])     ? mb_substr($touch_data['utm_term'], 0, 255) : $utm_term;
            $gclid        = !empty($touch_data['gclid'])        ? mb_substr($touch_data['gclid'], 0, 255) : $gclid;
            $fbclid       = !empty($touch_data['fbclid'])       ? mb_substr($touch_data['fbclid'], 0, 255) : $fbclid;
            if (empty($landing_page) && !empty($touch_data['landing_page'])) {
                $landing_page = mb_substr($touch_data['landing_page'], 0, 255);
            }
            if (empty($referrer) && !empty($touch_data['referrer'])) {
                $referrer = mb_substr($touch_data['referrer'], 0, 500);
            }
        }
    }

    // Determine marketing_source classification
    $provided_mktg_source = $get_val('marketing_source', 100);
    $marketing_source = classify_marketing_source($utm_source, $utm_medium, $gclid, $fbclid, $referrer);
    if (!empty($provided_mktg_source) && in_array($provided_mktg_source, ['Google Ads', 'Facebook Ads', 'Instagram Ads', 'Organic Search', 'Direct', 'Referral', 'Other / Unknown'], true)) {
        $marketing_source = $provided_mktg_source;
    }

    return [
        'marketing_source' => $marketing_source,
        'utm_source'       => $utm_source,
        'utm_medium'       => $utm_medium,
        'utm_campaign'     => $utm_campaign,
        'utm_content'      => $utm_content,
        'utm_term'         => $utm_term,
        'gclid'            => $gclid,
        'fbclid'           => $fbclid,
        'landing_page'     => $landing_page,
        'referrer'         => $referrer,
        'first_touch'      => $first_touch,
        'last_touch'       => $last_touch
    ];
}

/**
 * Server-side duplicate lead submission prevention.
 * Uses 15-second debounce window across session and active database.
 *
 * @param string $email User email
 * @param string $phone User phone
 * @param string $source Form source
 * @param int $window_seconds Debounce window (default 15)
 * @return bool True if duplicate submission detected, false otherwise
 */
function check_duplicate_enquiry_submission($email, $phone, $source, $window_seconds = 15) {
    safe_session_start();
    global $db;

    $clean_email = strtolower(trim((string)$email));
    $clean_phone = preg_replace('/[^0-9]/', '', (string)$phone);
    $sub_hash = md5($clean_email . '|' . $clean_phone . '|' . trim((string)$source));
    $now = time();

    // 1. Session-level check
    if (isset($_SESSION['last_enquiry_hash'], $_SESSION['last_enquiry_time'])) {
        if ($_SESSION['last_enquiry_hash'] === $sub_hash && ($now - (int)$_SESSION['last_enquiry_time']) < $window_seconds) {
            return true;
        }
    }

    // 2. Database-level check
    if (isset($db) && $db) {
        try {
            $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $check_sql = "SELECT id FROM `enquiries` 
                              WHERE (`email` = ? OR `phone` LIKE ?) 
                                AND `source` = ? 
                                AND `created_at` >= datetime('now', '-' || ? || ' seconds') 
                              LIMIT 1";
            } else {
                $check_sql = "SELECT id FROM `enquiries` 
                              WHERE (`email` = ? OR `phone` LIKE ?) 
                                AND `source` = ? 
                                AND `created_at` >= (NOW() - INTERVAL ? SECOND) 
                              LIMIT 1";
            }
            $stmt = $db->prepare($check_sql);
            $stmt->execute([$clean_email, '%' . $clean_phone, $source, $window_seconds]);
            if ($stmt->fetch()) {
                return true;
            }
        } catch (Exception $e) {
            error_log("[Duplicate Check Warning] " . $e->getMessage());
        }
    }

    return false;
}

/**
 * Records successful enquiry submission in session for debounce tracking.
 */
function record_enquiry_submission($email, $phone, $source) {
    safe_session_start();
    $clean_email = strtolower(trim((string)$email));
    $clean_phone = preg_replace('/[^0-9]/', '', (string)$phone);
    $_SESSION['last_enquiry_hash'] = md5($clean_email . '|' . $clean_phone . '|' . trim((string)$source));
    $_SESSION['last_enquiry_time'] = time();
}

