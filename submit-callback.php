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
$preferred_time = trim($_POST['preferred_time'] ?? '');
$user_message = trim($_POST['message'] ?? '');

if (empty($parent_name) || empty($email) || empty($phone) || empty($grade) || empty($preferred_time)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parent Name, Email, Phone, Grade, and Preferred Callback Time are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    if (!$db) {
        throw new Exception("Database connection required.");
    }
    
    // Concatenate the Preferred Callback Time and original message into the message field
    $full_message = "Preferred Callback Time: " . $preferred_time;
    if (!empty($user_message)) {
        $full_message .= "\n\nParent Message:\n" . $user_message;
    }
    
    // Insert into enquiries table using prepared statements
    $stmt = $db->prepare("
        INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
        VALUES (?, ?, ?, ?, ?, ?, 'Callback Modal', 1)
    ");
    $stmt->execute([
        $parent_name,
        $parent_name . ' (Student)',
        $grade,
        $phone,
        $email,
        $full_message
    ]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you. Our team will get in touch with you shortly.'
    ]);
} catch (Exception $e) {
    error_log("[Callback Submission Error] " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection required. Form could not be persisted.'
    ]);
}
