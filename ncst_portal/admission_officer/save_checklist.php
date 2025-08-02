<?php
session_start();
require_once '../../db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$checklist = isset($_POST['checklist']) ? $_POST['checklist'] : '';

if (!$id || !$checklist) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit;
}

if (json_decode($checklist) === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid checklist data.']);
    exit;
}

$statusRes = $conn->query("SELECT status FROM student_applications WHERE id = $id");
$currentStatus = ($statusRes && $statusRes->num_rows) ? $statusRes->fetch_assoc()['status'] : '';
$movedToPending = false;
if ($currentStatus === 'new') {
    $stmt = $conn->prepare("UPDATE student_applications SET requirements_checklist = ?, status = 'pending' WHERE id = ?");
    $stmt->bind_param('si', $checklist, $id);
    $movedToPending = true;
} else {
    $stmt = $conn->prepare("UPDATE student_applications SET requirements_checklist = ? WHERE id = ?");
    $stmt->bind_param('si', $checklist, $id);
}
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'movedToPending' => $movedToPending]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
$stmt->close();
$conn->close(); 