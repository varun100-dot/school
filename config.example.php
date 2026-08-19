<?php
// Zuvio Global School - Application & Database Configuration Template
// Copy this file to 'config.php' locally or place it outside the web root in production.

// Disable public displaying of errors in production (conceal credentials/warnings)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

// Database Connection Settings
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'u869064717_school');

// Application Settings
define('BASE_URL', getenv('BASE_URL') ?: 'https://zuvioglobalschool.com');
