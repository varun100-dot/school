<?php
header('Content-Type: application/json');

$hostinger_path = '/home/u869064717/config.php';

$info = [
    'php_version' => PHP_VERSION,
    'sapi_name' => php_sapi_name(),
    'open_basedir' => ini_get('open_basedir'),
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? null,
    'hostinger_exists' => file_exists($hostinger_path),
    'hostinger_readable' => is_readable($hostinger_path),
    'config_constants' => []
];

// Suppress errors during load attempt to gather diagnostics
if ($info['hostinger_readable']) {
    try {
        @include_once $hostinger_path;
        $info['config_constants'] = [
            'DB_HOST' => defined('DB_HOST') ? DB_HOST : 'undefined',
            'DB_PORT' => defined('DB_PORT') ? DB_PORT : 'undefined',
            'DB_USER' => defined('DB_USER') ? DB_USER : 'undefined',
            'DB_NAME' => defined('DB_NAME') ? DB_NAME : 'undefined',
            'DB_PASS_defined' => defined('DB_PASS'),
            'BASE_URL' => defined('BASE_URL') ? BASE_URL : 'undefined'
        ];
    } catch (Exception $e) {
        $info['include_error'] = $e->getMessage();
    }
} else {
    // Check if we can list parent directories
    $info['parent_two_exists'] = file_exists(dirname(dirname($_SERVER['DOCUMENT_ROOT'] ?? '')));
    $info['parent_two_path'] = dirname(dirname($_SERVER['DOCUMENT_ROOT'] ?? ''));
}

echo json_encode($info, JSON_PRETTY_PRINT);
exit;
