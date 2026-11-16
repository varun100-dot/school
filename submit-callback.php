<?php
// Zuvio Global School - Request Callback AJAX Form Handler
require_once dirname(__FILE__) . '/includes/db.php';
require_once dirname(__FILE__) . '/includes/helper.php';

safe_session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Security validation failed. Please refresh and try again.']);
    exit;
}

$parent_name = trim($_POST['parent_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$grade = trim($_POST['grade'] ?? '');
$preferred_time = trim($_POST['preferred_time'] ?? '') ?: 'Anytime';
$user_message = trim($_POST['message'] ?? '');

if (empty($parent_name) || empty($email) || empty($phone) || empty($grade)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parent Name, Email, Phone, and Grade are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

$student_name = trim($_POST['student_name'] ?? '');
$source = trim($_POST['source'] ?? '') ?: 'Callback Modal';

// Extract sanitized marketing attribution
$attr = extract_marketing_attribution_from_request();

// Server-side duplicate debounce check (15 seconds)
if (check_duplicate_enquiry_submission($email, $phone, $source, 15)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you. We have already received your callback request and our admissions team will contact you shortly.'
    ]);
    exit;
}

$full_message = '';
if (!empty($preferred_time)) {
    $full_message = "Preferred Callback Time: " . $preferred_time;
}
if (!empty($user_message)) {
    $full_message .= ($full_message ? "\n\n" : "") . "Message / Notes:\n" . $user_message;
}
if (empty($full_message)) {
    $full_message = "Submitted via " . $source;
}

$saved = false;
if ($db) {
    try {
        $stmt = $db->prepare("
            INSERT INTO `enquiries` (
                `parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`,
                `marketing_source`, `utm_source`, `utm_medium`, `utm_campaign`, `utm_content`, `utm_term`,
                `gclid`, `fbclid`, `landing_page`, `referrer`, `first_touch`, `last_touch`
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $parent_name,
            $student_name ?: ($parent_name . ' (Student)'),
            $grade,
            $phone,
            $email,
            $full_message,
            $source,
            $attr['marketing_source'],
            $attr['utm_source'],
            $attr['utm_medium'],
            $attr['utm_campaign'],
            $attr['utm_content'],
            $attr['utm_term'],
            $attr['gclid'],
            $attr['fbclid'],
            $attr['landing_page'],
            $attr['referrer'],
            $attr['first_touch'],
            $attr['last_touch']
        ]);
        $saved = true;
        record_enquiry_submission($email, $phone, $source);
    } catch (Exception $e) {
        // Dual-schema compatibility: if unmigrated database schema, fallback to legacy schema
        if (strpos($e->getMessage(), 'Unknown column') !== false || strpos($e->getMessage(), 'has no column named') !== false) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                ");
                $stmt->execute([
                    $parent_name,
                    $student_name ?: ($parent_name . ' (Student)'),
                    $grade,
                    $phone,
                    $email,
                    $full_message,
                    $source
                ]);
                $saved = true;
                record_enquiry_submission($email, $phone, $source);
            } catch (Exception $fallback_e) {
                error_log("[Callback Modal Legacy Schema Fallback Error] " . $fallback_e->getMessage());
                $saved = false;
            }
        } else {
            error_log("[Enquiry Submission DB Error] " . $e->getMessage());
            $saved = false;
        }
    }
}

if (!$saved) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unable to save your callback request right now. Please try again or contact us directly at info@zuvioglobalschool.com.'
    ]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Thank you. Our admissions team will get in touch with you shortly.'
]);
