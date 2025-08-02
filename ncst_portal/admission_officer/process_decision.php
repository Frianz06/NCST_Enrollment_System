<?php
session_start();
require_once '../../db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');

// Debug log file
$debug_log = __DIR__ . '/process_decision_debug.log';
file_put_contents($debug_log, "\n==== " . date('Y-m-d H:i:s') . " ====".PHP_EOL, FILE_APPEND);
file_put_contents($debug_log, 'POST: ' . print_r($_POST, true) . PHP_EOL, FILE_APPEND);

// Test database connection
if (!$conn || $conn->connect_error) {
    file_put_contents($debug_log, 'DB connection failed: ' . ($conn ? $conn->connect_error : 'No connection') . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$checklist = isset($_POST['checklist']) ? $_POST['checklist'] : '';

file_put_contents($debug_log, 'Raw checklist: ' . $checklist . PHP_EOL, FILE_APPEND);

if (!$id || !$action || !$checklist) {
    file_put_contents($debug_log, 'Missing data' . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit;
}

$decoded_checklist = json_decode($checklist, true);
file_put_contents($debug_log, 'Decoded checklist: ' . print_r($decoded_checklist, true) . PHP_EOL, FILE_APPEND);

if ($decoded_checklist === null) {
    file_put_contents($debug_log, 'Invalid checklist data' . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Invalid checklist data.']);
    exit;
}

if ($action === 'approve') {
    // Update status to approved (no student_id generation yet)
    $stmt = $conn->prepare("UPDATE student_applications SET requirements_checklist = ?, status = 'approved' WHERE id = ?");
    $stmt->bind_param('si', $checklist, $id);
} elseif ($action === 'reject') {
    $stmt = $conn->prepare("UPDATE student_applications SET requirements_checklist = ?, status = 'rejected' WHERE id = ?");
    $stmt->bind_param('si', $checklist, $id);
} else {
    file_put_contents($debug_log, 'Invalid action' . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

try {
    if ($stmt->execute()) {
        file_put_contents($debug_log, 'DB update success' . PHP_EOL, FILE_APPEND);
        
        // Verify the update worked by checking the current status
        $verify = $conn->query("SELECT status FROM student_applications WHERE id = $id");
        if ($verify && $row = $verify->fetch_assoc()) {
            file_put_contents($debug_log, 'Current status after update: ' . $row['status'] . PHP_EOL, FILE_APPEND);
        }
        
        echo json_encode(['success' => true, 'message' => 'Applicant status updated successfully']);
    } else {
        file_put_contents($debug_log, 'DB error: ' . $stmt->error . PHP_EOL, FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
} catch (Exception $e) {
    file_put_contents($debug_log, 'Exception: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
}

if (isset($stmt)) {
    $stmt->close();
}
if (isset($conn)) {
    $conn->close();
} 