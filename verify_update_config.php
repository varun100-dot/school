<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

$new_config = <<<EOD
<?php
// Zuvio Global School - Application & Database Configuration
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_USER', 'u869064717_school');
define('DB_PASS', 'ZuvioGlobalSchool@1234#');
define('DB_NAME', 'u869064717_school');
define('BASE_URL', 'https://zuvioglobalschool.com');
EOD;

$result = @file_put_contents($path, $new_config);

if ($result === false) {
    echo json_encode(['status' => 'fail', 'message' => 'Failed to write config file.']);
    exit;
}

// Secure file with 600 permissions
$chmod_res = @chmod($path, 0600);

echo json_encode([
    'status' => 'success',
    'bytes_written' => $result,
    'chmod_status' => $chmod_res ? '600_applied' : 'chmod_failed'
]);
exit;
