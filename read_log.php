<?php
header('Content-Type: text/plain');

$log_file = ini_get('error_log');

echo "Error Log Path: " . $log_file . "\n";
echo "==================================================\n";

if ($log_file && file_exists($log_file)) {
    $content = file_get_contents($log_file);
    echo substr($content, -4000); // output last 4KB of log
} else {
    // Check local directory for error_log
    $local_log = dirname(__FILE__) . '/error_log';
    if (file_exists($local_log)) {
        echo "Found local error_log file:\n";
        echo substr(file_get_contents($local_log), -4000);
    } else {
        echo "No error log file found or accessible.";
    }
}
exit;
