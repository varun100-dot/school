<?php
// Temporary DB test
require_once dirname(__FILE__) . '/includes/db.php';

header('Content-Type: application/json');

$response = [
    'db_host' => defined('DB_HOST') ? DB_HOST : null,
    'db_user_configured' => defined('DB_USER') ? DB_USER : null,
    'db_name' => defined('DB_NAME') ? DB_NAME : null,
    'db_port' => defined('DB_PORT') ? DB_PORT : null,
    'pdo_connection' => 'fail',
    'error_code' => null
];

if (isset($db) && $db !== null) {
    try {
        $stmt = $db->query("SELECT 1");
        $stmt->execute();
        
        $stmt2 = $db->query("SELECT USER(), CURRENT_USER(), DATABASE()");
        $res = $stmt2->fetch(PDO::FETCH_NUM);
        
        $response['pdo_connection'] = 'success';
        $response['query_result'] = $res;
    } catch (Exception $e) {
        $response['pdo_connection'] = 'fail';
        $response['error_code'] = $e->getCode();
        $response['error_message'] = $e->getMessage();
    }
} else {
    $response['pdo_connection'] = 'fail';
    $response['error_code'] = 'NO_DB_OBJECT';
}

echo json_encode($response);
exit;
