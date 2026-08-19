<?php
// Zuvio Global School - PDO Database Connection Handler

// 1. Check environment variable override
$config_path = getenv('ZUVIO_CONFIG_PATH');

// 2. Check Hostinger account-level production path directly
if (!$config_path) {
    $hostinger_path = '/home/u869064717/config.php';
    if (file_exists($hostinger_path)) {
        $config_path = $hostinger_path;
    }
}

// 3. Fallback to derived path relative to DOCUMENT_ROOT if not local
if (!$config_path && isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT'])) {
    $doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
    
    // Level 2: /home/u869064717/config.php (double check)
    $parent_two = dirname(dirname($doc_root)) . '/config.php';
    if (file_exists($parent_two)) {
        $config_path = $parent_two;
    }
    
    // Level 1: /home/u869064717/domains/zuvioglobalschool.com/config.php
    if (!$config_path) {
        $parent_one = dirname($doc_root) . '/config.php';
        if (file_exists($parent_one)) {
            $config_path = $parent_one;
        }
    }
}

// 4. Fallback to local configuration file for development ONLY if we did not find a production config path
$is_production_env = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'zuvioglobalschool.com') !== false);
if (!$config_path) {
    if ($is_production_env) {
        header('HTTP/1.1 500 Internal Server Error');
        error_log("[DB Error] Production config file not found at expected paths.");
        echo "<h1>500 Internal Server Error</h1><p>Application configuration error.</p>";
        exit;
    }
    
    $local_path = dirname(__FILE__) . '/../config.php';
    if (file_exists($local_path)) {
        $config_path = $local_path;
    }
}

// If no config file is located, terminate execution to prevent defaults leak
if (!$config_path) {
    header('HTTP/1.1 500 Internal Server Error');
    error_log("[DB Error] Configuration path could not be resolved.");
    echo "<h1>500 Internal Server Error</h1><p>Application configuration file is missing.</p>";
    exit;
}

require_once $config_path;

// Validate that loaded production config doesn't use root/empty password
if ($is_production_env) {
    if (defined('DB_USER') && (DB_USER === 'root' || DB_USER === '')) {
        header('HTTP/1.1 500 Internal Server Error');
        error_log("[DB Error] Production configuration cannot use root or empty database user.");
        echo "<h1>500 Internal Server Error</h1><p>Application configuration is invalid.</p>";
        exit;
    }
}

$db = null;

try {
    // Intercept srv1113.hstgr.io to force 127.0.0.1 IPv4 connection
    $db_host = DB_HOST;
    if ($db_host === 'srv1113.hstgr.io') {
        $db_host = '127.0.0.1';
    }
    
    $dsn = "mysql:host=" . $db_host . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    $GLOBALS['db_connection_error'] = $e->getMessage();
    // Log error securely, prevent credential exposure, display a 500 page
    $sanitized_message = str_replace(DB_PASS, '******', $e->getMessage());
    error_log("[DB] host=" . (defined('DB_HOST') ? DB_HOST : 'undefined') . " user_configured=" . (defined('DB_USER') ? 'YES' : 'NO') . " database_configured=" . (defined('DB_NAME') ? 'YES' : 'NO') . " error_code=" . $e->getCode() . " error_message=" . $sanitized_message);
    
    $script_name = basename($_SERVER['SCRIPT_NAME'] ?? '');
    if (php_sapi_name() !== 'cli' && strpos($script_name, 'debug_db') === false) {
        header('HTTP/1.1 500 Internal Server Error');
        if (file_exists(dirname(__FILE__) . '/../pages/500.php')) {
            include dirname(__FILE__) . '/../pages/500.php';
        } else {
            echo "<h1>500 Internal Server Error</h1><p>Our database service is temporarily offline. Please try again later.</p>";
        }
        exit;
    }
}

function testConnection() {
    global $db;
    if (!$db) {
        return ['connected' => false, 'error' => 'Database connection could not be established.'];
    }
    try {
        $stmt = $db->query("SELECT 1");
        $stmt->execute();
        return ['connected' => true];
    } catch (Exception $e) {
        return ['connected' => false, 'error' => $e->getMessage()];
    }
}
