<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

try {
    include_once $path;
    echo json_encode([
        'status' => 'included',
        'DB_HOST' => defined('DB_HOST') ? DB_HOST : 'undefined',
        'DB_PORT' => defined('DB_PORT') ? DB_PORT : 'undefined',
        'DB_USER' => defined('DB_USER') ? DB_USER : 'undefined',
        'DB_NAME' => defined('DB_NAME') ? DB_NAME : 'undefined'
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
exit;
