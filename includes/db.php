<?php
// Zuvio Global School - PDO Database Connection Handler
// Secure parent-root configuration path resolution strategy
$config_path = null;

if (isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT'])) {
    $doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
    
    // Level 2 Lookup: /home/u869064717/config.php (Account home root)
    $parent_two = dirname(dirname($doc_root));
    if (file_exists($parent_two . '/config.php')) {
        $config_path = $parent_two . '/config.php';
    }
    
    // Level 1 Lookup: /home/u869064717/domains/zuvioglobalschool.com/config.php
    if (!$config_path) {
        $parent_one = dirname($doc_root);
        if (file_exists($parent_one . '/config.php')) {
            $config_path = $parent_one . '/config.php';
        }
    }
}

// Fallback to local configuration file for development
if (!$config_path) {
    $local_path = dirname(__FILE__) . '/../config.php';
    if (file_exists($local_path)) {
        $config_path = $local_path;
    }
}

// If no config file is located, terminate execution to prevent default credentials leak
if (!$config_path) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<h1>500 Internal Server Error</h1><p>Application configuration file is missing.</p>";
    exit;
}

require_once $config_path;

$db = null;

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log error securely, prevent credential exposure, display a 500 page
    error_log("[DB Error] " . $e->getMessage());
    
    // Check if we are requested inside router or CLI context
    if (php_sapi_name() !== 'cli') {
        header('HTTP/1.1 500 Internal Server Error');
        $error_detail = "Database connection offline.";
        // Fallback layout if pages/500.php is not yet loaded
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
