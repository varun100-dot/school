<?php
/**
 * Zuvio Global School - Production Database Diagnostic & Verification Script
 * Strictly Read-Only: Does NOT modify, create, delete, or alter any database or configuration.
 * Safe for execution via Hostinger SSH CLI:
 *   php tests/hostinger_db_verify.php
 * or:
 *   php /home/u869064717/domains/zuvioglobalschool.com/public_html/tests/hostinger_db_verify.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=======================================================\n";
echo " ZUVIO GLOBAL SCHOOL - PRODUCTION DB VERIFICATION\n";
echo " Time: " . date('Y-m-d H:i:s T') . "\n";
echo " Host: " . gethostname() . " (" . php_uname('n') . ")\n";
echo " PHP Version: " . PHP_VERSION . "\n";
echo " Current User: " . get_current_user() . " (UID: " . getmyuid() . ")\n";
echo "=======================================================\n\n";

// 1. Locate and inspect config.php
$config_candidates = [
    '/home/u869064717/config.php',
    dirname(__FILE__) . '/../config.php',
    dirname(dirname(dirname(__FILE__))) . '/config.php'
];

$active_config = null;
foreach ($config_candidates as $cfg) {
    if (file_exists($cfg)) {
        $active_config = $cfg;
        break;
    }
}

echo "[1] CONFIGURATION INSPECTION:\n";
if (!$active_config) {
    echo "  [-] ERROR: No config.php found in checked paths:\n";
    foreach ($config_candidates as $c) echo "      - $c\n";
    exit(1);
}

echo "  [+] Active Config File: $active_config\n";
echo "  [+] File Size: " . filesize($active_config) . " bytes\n";
echo "  [+] Permissions: " . substr(sprintf('%o', fileperms($active_config)), -4) . "\n";

// Read and parse constants safely (Masking DB_PASS)
$lines = file($active_config);
foreach ($lines as $line) {
    $line = trim($line);
    if (strpos($line, 'define(') !== false) {
        if (preg_match('/define\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*(.*?)\);/', $line, $m)) {
            $key = $m[1];
            $val = $m[2];
            if (stripos($key, 'pass') !== false || stripos($key, 'secret') !== false) {
                echo "      - $key: ****** (MASKED FOR SECURITY)\n";
            } else {
                echo "      - $key: $val\n";
            }
        }
    }
}

require_once $active_config;

// 2. Test PDO Connection
echo "\n[2] DATABASE CONNECTION TEST:\n";
$db_host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
if ($db_host === 'srv1113.hstgr.io') {
    echo "  [*] Mapping srv1113.hstgr.io to 127.0.0.1 (IPv4 loopback)\n";
    $db_host = '127.0.0.1';
}
$db_port = defined('DB_PORT') ? DB_PORT : 3306;
$db_name = defined('DB_NAME') ? DB_NAME : '';
$db_user = defined('DB_USER') ? DB_USER : '';
$db_pass = defined('DB_PASS') ? DB_PASS : '';

echo "  [*] Target Host: $db_host\n";
echo "  [*] Target Port: $db_port\n";
echo "  [*] Target Database: $db_name\n";
echo "  [*] Target User: $db_user\n";

$pdo = null;
try {
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5
    ];
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    echo "  [+] PDO MySQL Connection: SUCCESSFUL!\n";
} catch (PDOException $e) {
    echo "  [-] PDO MySQL Connection FAILED: " . $e->getMessage() . "\n";
    
    // Test connection without dbname (server level)
    try {
        echo "  [*] Testing MySQL server root connection (without dbname)...\n";
        $dsn_root = "mysql:host=$db_host;port=$db_port;charset=utf8mb4";
        $pdo_root = new PDO($dsn_root, $db_user, $db_pass, $options);
        echo "  [+] Connected to MySQL Server! Database '$db_name' may not exist yet.\n";
        $dbs = $pdo_root->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
        echo "  [*] Available Databases on server:\n";
        foreach ($dbs as $d) echo "      - $d\n";
    } catch (Exception $e_root) {
        echo "  [-] Server-level connection also failed: " . $e_root->getMessage() . "\n";
    }
}

// 3. Read-Only Database Queries
if ($pdo) {
    echo "\n[3] READ-ONLY QUERIES ON ACTIVE DATABASE:\n";
    
    // SELECT DATABASE()
    $cur_db = $pdo->query("SELECT DATABASE()")->fetchColumn();
    echo "  [+] Active Selected Database: $cur_db\n";
    
    // Server Version
    $ver = $pdo->query("SELECT VERSION()")->fetchColumn();
    echo "  [+] MySQL / MariaDB Server Version: $ver\n";
    
    // Current User & Session Timezone
    $user_res = $pdo->query("SELECT USER(), @@session.time_zone")->fetch(PDO::FETCH_NUM);
    echo "  [+] Authenticated As: " . $user_res[0] . " | Session Timezone: " . $user_res[1] . "\n";
    
    // SHOW TABLES
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "  [+] Total Tables in '$cur_db': " . count($tables) . "\n";
    echo "  [*] Tables List:\n";
    foreach (array_chunk($tables, 3) as $chunk) {
        echo "      " . implode(" | ", array_map(function($t) { return str_pad($t, 25); }, $chunk)) . "\n";
    }
    
    // Check enquiries table
    echo "\n[4] ENQUIRIES TABLE VERIFICATION:\n";
    if (in_array('enquiries', $tables)) {
        echo "  [+] 'enquiries' table EXISTS!\n";
        
        // Describe table
        echo "  [*] Table Structure (DESCRIBE enquiries):\n";
        $cols = $pdo->query("DESCRIBE enquiries")->fetchAll();
        foreach ($cols as $c) {
            echo sprintf("      - %-20s %-15s Null:%-4s Key:%-4s Default:%s\n", 
                $c['Field'], $c['Type'], $c['Null'], $c['Key'], $c['Default'] ?? 'NULL');
        }
        
        // Count total rows
        $count = $pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
        echo "\n  [+] Total Enquiries in Production Database: $count\n";
        
        // Show recent rows (if any)
        if ($count > 0) {
            echo "  [*] Recent Enquiries Sample (Up to 5):\n";
            $leads = $pdo->query("SELECT id, parent_name, email, phone, source, created_at FROM enquiries ORDER BY id DESC LIMIT 5")->fetchAll();
            foreach ($leads as $l) {
                echo sprintf("      #%-4d | %-20s | %-25s | %-12s | %-20s | %s\n",
                    $l['id'], substr($l['parent_name'], 0, 20), substr($l['email'], 0, 25), 
                    $l['phone'], substr($l['source'], 0, 20), $l['created_at']);
            }
        } else {
            echo "  [*] Note: The enquiries table is currently empty (0 records).\n";
        }
    } else {
        echo "  [-] 'enquiries' table does NOT exist in database '$cur_db'!\n";
    }
}

// 5. Inspect SQLite Database State (if present)
echo "\n[5] LOCAL SQLITE FILE CHECK:\n";
$sqlite_path = dirname(__FILE__) . '/../database/local.sqlite';
if (file_exists($sqlite_path)) {
    echo "  [+] SQLite File Found: $sqlite_path\n";
    echo "  [+] File Size: " . filesize($sqlite_path) . " bytes\n";
    try {
        $sq_pdo = new PDO("sqlite:$sqlite_path");
        $sq_count = $sq_pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
        echo "  [+] Enquiries in SQLite file: $sq_count\n";
    } catch (Exception $e) {
        echo "  [-] Could not query SQLite file: " . $e->getMessage() . "\n";
    }
} else {
    echo "  [*] No local.sqlite file found at expected path.\n";
}

echo "\n=======================================================\n";
echo " END OF VERIFICATION\n";
echo "=======================================================\n";

