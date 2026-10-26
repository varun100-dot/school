<?php
// Zuvio Global School - Homeschooling Lead Form Backend Handler
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();

$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false)
    || isset($_POST['is_ajax']);

if ($is_ajax) {
    header('Content-Type: application/json; charset=utf-8');
}

// 1. Reject unsupported HTTP methods
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    if ($is_ajax) {
        echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    } else {
        echo "405 Method Not Allowed";
    }
    exit;
}

// 2. CSRF Token Validation
$submitted_csrf = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($submitted_csrf)) {
    http_response_code(403);
    $error_msg = 'Security validation failed. Please refresh the page and try again.';
    if ($is_ajax) {
        echo json_encode(['status' => 'error', 'message' => $error_msg]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'error', 'message' => $error_msg];
        header('Location: /homeschooling/');
    }
    exit;
}

// 3. Extract & Sanitize User Inputs
$parent_name = trim($_POST['parent_name'] ?? $_POST['parent'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$email       = trim($_POST['email'] ?? '');
$grade       = trim($_POST['grade'] ?? '');

// 4. Validate All Required Fields (Mandatory)
if ($parent_name === '' || $phone === '' || $email === '' || $grade === '') {
    http_response_code(400);
    $error_msg = 'All four fields are required: Parent Name, Phone Number, Email Address, and Child’s Grade.';
    if ($is_ajax) {
        echo json_encode(['status' => 'error', 'message' => $error_msg]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'error', 'message' => $error_msg];
        header('Location: /homeschooling/');
    }
    exit;
}

// 5. Validate Email Format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    $error_msg = 'Please enter a valid email address.';
    if ($is_ajax) {
        echo json_encode(['status' => 'error', 'message' => $error_msg]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'error', 'message' => $error_msg];
        header('Location: /homeschooling/');
    }
    exit;
}

// 6. Validate Phone Number Format Consistently
$digits_only = preg_replace('/[^0-9]/', '', $phone);
if (strlen($digits_only) < 7 || strlen($digits_only) > 15 || !preg_match('/^[+0-9 ()-]{7,20}$/', $phone)) {
    http_response_code(400);
    $error_msg = 'Please enter a valid phone number (at least 7 digits).';
    if ($is_ajax) {
        echo json_encode(['status' => 'error', 'message' => $error_msg]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'error', 'message' => $error_msg];
        header('Location: /homeschooling/');
    }
    exit;
}

// 7. Validate Grade Option Against Permitted Whitelist
$permitted_grades = [
    'Nursery',
    'LKG',
    'UKG',
    'Grade 1',
    'Grade 2',
    'Grade 3',
    'Grade 4',
    'Grade 5',
    'Grade 6',
    'Grade 7',
    'Grade 8'
];
if (!in_array($grade, $permitted_grades, true)) {
    http_response_code(400);
    $error_msg = 'Please select a valid grade option from Nursery to Grade 8.';
    if ($is_ajax) {
        echo json_encode(['status' => 'error', 'message' => $error_msg]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'error', 'message' => $error_msg];
        header('Location: /homeschooling/');
    }
    exit;
}

// 8. CRM Field Mapping
$student_name = $parent_name . ' (Student)';
$source       = 'Homeschooling Landing Page';
$status_id    = 1; // New initial enquiry status
$message      = 'Submitted via Homeschooling Landing Page';

// Enforce max field lengths matching schema
$parent_name  = mb_substr($parent_name, 0, 150);
$student_name = mb_substr($student_name, 0, 150);
$email        = mb_substr($email, 0, 150);
$phone        = mb_substr($phone, 0, 30);
$grade        = mb_substr($grade, 0, 50);

// Prevent accidental duplicate rapid submission (10-second debounce)
$submission_hash = md5($parent_name . '|' . $email . '|' . $phone . '|' . $grade);
$now = time();
if (isset($_SESSION['last_lead_sub_hash'], $_SESSION['last_lead_sub_time'])) {
    if ($_SESSION['last_lead_sub_hash'] === $submission_hash && ($now - $_SESSION['last_lead_sub_time']) < 10) {
        $success_msg = 'Thank you! Your request for a free demo has been submitted successfully. Our admissions team will get in touch with you shortly.';
        if ($is_ajax) {
            echo json_encode([
                'status'  => 'success',
                'message' => $success_msg
            ]);
        } else {
            $_SESSION['lead_feedback'] = ['type' => 'success', 'message' => $success_msg];
            header('Location: /homeschooling/');
        }
        exit;
    }
}

// 9. Execute Prepared SQL Insertion
$saved = false;
if (isset($db) && $db) {
    try {
        $stmt = $db->prepare("
            INSERT INTO `enquiries` (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $parent_name,
            $student_name,
            $grade,
            $phone,
            $email,
            $message,
            $source,
            $status_id
        ]);
        $saved = true;
    } catch (Exception $e) {
        error_log("[Homeschooling Enquiry DB Error] " . $e->getMessage());
    }
}

// 10. Return Response
if ($saved) {
    $_SESSION['last_lead_sub_hash'] = $submission_hash;
    $_SESSION['last_lead_sub_time'] = $now;

    $success_msg = 'Thank you! Your request for a free demo has been submitted successfully. Our admissions team will get in touch with you shortly.';
    if ($is_ajax) {
        echo json_encode([
            'status'  => 'success',
            'message' => $success_msg
        ]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'success', 'message' => $success_msg];
        header('Location: /homeschooling/');
    }
    exit;
} else {
    http_response_code(500);
    $fail_msg = 'Unable to save your enquiry at this moment. Please try again later or contact us at info@zuvioglobalschool.com.';
    if ($is_ajax) {
        echo json_encode([
            'status'  => 'error',
            'message' => $fail_msg
        ]);
    } else {
        $_SESSION['lead_feedback'] = ['type' => 'error', 'message' => $fail_msg];
        header('Location: /homeschooling/');
    }
    exit;
}
