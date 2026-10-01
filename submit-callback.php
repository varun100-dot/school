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
    } catch (Exception $e) {
        error_log("[Enquiry Submission DB Error] " . $e->getMessage());
    }
}

// Persist in session fallback
if (!isset($_SESSION['mock_enquiries'])) {
    $_SESSION['mock_enquiries'] = [];
}
$_SESSION['mock_enquiries'][] = [
    'id' => count($_SESSION['mock_enquiries']) + 1001,
    'parent_name' => $parent_name,
    'student_name' => $student_name ?: ($parent_name . ' (Student)'),
    'grade' => $grade,
    'phone' => $phone,
    'email' => $email,
    'message' => $full_message,
    'source' => $source,
    'status_id' => 1,
    'created_at' => date('Y-m-d H:i:s')
];

echo json_encode([
    'status' => 'success',
    'message' => 'Thank you. Our admissions team will get in touch with you shortly.'
]);
