<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $student_id = $_SESSION['student_id'];
    
    switch ($action) {
        case 'get_queue_number':
            // Check if queue is still open (before 5 PM)
            $current_time = date('H:i:s');
            $current_date = date('Y-m-d');
            
            if ($current_time >= '17:00:00') {
                echo json_encode(['success' => false, 'message' => 'Queue is closed for today. Please come back tomorrow.']);
                exit;
            }
            
            // Check if student already has a queue number for today
            $stmt = $conn->prepare('SELECT * FROM queue_system WHERE student_id = ? AND queue_date = ? AND status != "cancelled"');
            $stmt->bind_param('ss', $student_id, $current_date);
            $stmt->execute();
            $existing_queue = $stmt->get_result()->fetch_assoc();
            
            if ($existing_queue) {
                echo json_encode(['success' => true, 'queue_number' => $existing_queue['queue_number']]);
                exit;
            }
            
            // Get the next queue number for today
            $stmt = $conn->prepare('SELECT MAX(CAST(SUBSTRING(queue_number, 3) AS UNSIGNED)) as max_num FROM queue_system WHERE queue_date = ?');
            $stmt->bind_param('s', $current_date);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            $next_number = ($result['max_num'] ?? 0) + 1;
            $queue_number = 'E-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
            
            // Insert new queue entry
            $stmt = $conn->prepare('INSERT INTO queue_system (queue_number, student_id, queue_date) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $queue_number, $student_id, $current_date);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'queue_number' => $queue_number]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to generate queue number']);
            }
            break;
            
        case 'get_queue_status':
            $current_date = date('Y-m-d');
            
            // Get current queue status
            $stmt = $conn->prepare('SELECT * FROM queue_system WHERE student_id = ? AND queue_date = ? ORDER BY created_at DESC LIMIT 1');
            $stmt->bind_param('ss', $student_id, $current_date);
            $stmt->execute();
            $queue_status = $stmt->get_result()->fetch_assoc();
            
            if ($queue_status) {
                echo json_encode([
                    'success' => true,
                    'queue_number' => $queue_status['queue_number'],
                    'status' => $queue_status['status'],
                    'position' => $queue_status['id']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No queue number found']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?> 