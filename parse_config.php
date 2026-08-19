<?php
header('Content-Type: text/plain');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo "File not found";
    exit;
}

$content = file_get_contents($path);

// Safe mask using string replacement
$obscured = str_replace('ZuvioGlobalSchool@1234#', '******', $content);

echo $obscured;
exit;
