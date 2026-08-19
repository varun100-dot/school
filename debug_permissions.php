<?php
header('Content-Type: application/json');

$path = '/home/u869064717/config.php';

$info = [
    'exists' => file_exists($path),
    'readable' => @is_readable($path),
    'perms' => file_exists($path) ? sprintf('%o', @fileperms($path)) : null,
    'current_user' => get_current_user(),
    'my_uid' => function_exists('posix_getuid') ? posix_getuid() : 'n/a',
    'my_gid' => function_exists('posix_getgid') ? posix_getgid() : 'n/a'
];

echo json_encode($info, JSON_PRETTY_PRINT);
exit;
