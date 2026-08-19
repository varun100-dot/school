<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo json_encode(['status' => 'fail', 'message' => 'Config file not found']);
    exit;
}

$content = file_get_contents($path);

// Replace host and password inline
$content = str_replace("'srv1113.hstgr.io'", "'127.0.0.1'", $content);
$content = str_replace('"srv1113.hstgr.io"', '"127.0.0.1"', $content);
$content = str_replace("'School@1234#$'", "'ZuvioGlobalSchool@1234#'", $content);
$content = str_replace('"School@1234#$"', '"ZuvioGlobalSchool@1234#"', $content);

$res = file_put_contents($path, $content);

if ($res === false) {
    echo json_encode(['status' => 'fail', 'message' => 'Failed to write config file']);
    exit;
}

@chmod($path, 0600);

echo json_encode(['status' => 'success', 'bytes_written' => $res]);
exit;
