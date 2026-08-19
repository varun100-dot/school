<?php
header('Content-Type: text/plain');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo "File not found";
    exit;
}

$content = file_get_contents($path);

// Obscure DB_PASS using regex replacement
$obscured = preg_replace("/(define\s*\(\s*['\"]DB_PASS['\"]\s*,\s*['\"])[^'\"]*(['\"]\s*\))/i", "$1******$2", $content);

echo $obscured;
exit;
