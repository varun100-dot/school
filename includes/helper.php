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

// Get site settings key-value pair from database
function get_setting($key, $default = '') {
    global $db;
    if (!$db) {
        safe_session_start();
        return $_SESSION['mock_settings'][$key] ?? $default;
    }
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
        return $_SESSION['mock_' . $key] ?? $default;
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : $default;
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

