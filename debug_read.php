<?php
header('Content-Type: text/plain');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo "File not found\n";
    exit;
}

$content = @file_get_contents($path);
if ($content === false) {
    echo "Could not read file (permission denied)\n";
    exit;
}

echo "File Size: " . strlen($content) . " bytes\n";
echo "First 100 chars: " . substr($content, 0, 100) . "\n";
exit;
