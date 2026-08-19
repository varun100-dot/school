<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

$exists = file_exists($path);
$readable = $exists ? is_readable($path) : false;

echo json_encode([
    'exists' => $exists,
    'readable' => $readable
]);
exit;
