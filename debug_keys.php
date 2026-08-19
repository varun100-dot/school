<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo json_encode(['error' => 'File not found']);
    exit;
}

$lines = file($path);
$keys = [];
foreach ($lines as $line) {
    if (preg_match('/define\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,/i', $line, $matches)) {
        $keys[] = $matches[1];
    }
}

echo json_encode($keys);
exit;
