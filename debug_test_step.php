<?php
header('Content-Type: application/json');

$response = ['step' => 0];

try {
    $response['step'] = 1;
    $path = '/home/u869064717/config.php';
    $response['path'] = $path;
    
    $response['step'] = 2;
    $exists = @file_exists($path);
    $response['exists'] = $exists;
    
    $response['step'] = 3;
    if ($exists) {
        $readable = @is_readable($path);
        $response['readable'] = $readable;
    }
    
    $response['step'] = 4;
} catch (Throwable $t) {
    $response['error'] = $t->getMessage();
    $response['error_file'] = $t->getFile();
    $response['error_line'] = $t->getLine();
}

echo json_encode($response);
exit;
