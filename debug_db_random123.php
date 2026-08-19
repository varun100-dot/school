<?php
// Unique DB test script to bypass server-side caching
require_once dirname(__FILE__) . '/includes/db.php';

header('Content-Type: application/json');

$response = [
    'db_host' => defined('DB_HOST') ? DB_HOST : null,
    'db_user' => defined('DB_USER') ? DB_USER : null,
    'db_name' => defined('DB_NAME') ? DB_NAME : null,
    'db_port' => defined('DB_PORT') ? DB_PORT : null,
    'pdo_connection' => 'fail',
    'error_message' => null
];

if (isset($db) && $db !== null) {
    $response['pdo_connection'] = 'success';
} else {
    $response['pdo_connection'] = 'fail';
    $response['error_message'] = isset($GLOBALS['db_connection_error']) ? $GLOBALS['db_connection_error'] : 'Unknown error';
}

// Mask password if leaked in error message
if (defined('DB_PASS') && DB_PASS !== '') {
    $response['error_message'] = str_replace(DB_PASS, '******', $response['error_message']);
}

echo json_encode($response);
exit;
