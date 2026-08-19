<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo json_encode(['error' => 'File does not exist']);
    exit;
}

$content = file_get_contents($path);

// Match define('CONSTANT', 'value') or define("CONSTANT", "value")
preg_match_all('/define\s*\(\s*[\'"]([^\'"]+)[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]\s*\)/i', $content, $matches);

$definitions = [];
for ($i = 0; $i < count($matches[0]); $i++) {
    $key = $matches[1][$i];
    $val = $matches[2][$i];
    // Mask password
    if (strpos($key, 'PASS') !== false || strpos($key, 'SEC') !== false || strpos($key, 'KEY') !== false) {
        $val = '******';
    }
    $definitions[$key] = $val;
}

echo json_encode([
    'file_path' => $path,
    'file_size' => strlen($content),
    'definitions' => $definitions
], JSON_PRETTY_PRINT);
exit;
