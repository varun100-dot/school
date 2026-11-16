<?php
// Zuvio Global School — Legacy Landing Page (/landing-page/)
// Migrated permanently (301) to /online-school/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $qs = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    header('Location: /online-school/' . $qs, true, 301);
    exit;
}

// Forward any legacy POST submissions directly to online-school.php
require_once dirname(__FILE__) . '/online-school.php';
exit;
