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
    $section_id = $_POST['section_id'] ?? '';
    
    if ($section_id) {
        // Get schedules for the selected section
        $stmt = $conn->prepare('SELECT * FROM schedules WHERE section_id = ? ORDER BY day, time_start');
        $stmt->bind_param('i', $section_id);
        $stmt->execute();
        $schedules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        if ($schedules) {
            $html = '<table class="table table-sm table-bordered">';
            $html .= '<thead class="table-light"><tr><th>Day</th><th>Time</th><th>Subject</th><th>Room</th><th>Instructor</th></tr></thead>';
            $html .= '<tbody>';
            
            foreach ($schedules as $schedule) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($schedule['day']) . '</td>';
                $html .= '<td>' . date('h:i A', strtotime($schedule['time_start'])) . ' - ' . date('h:i A', strtotime($schedule['time_end'])) . '</td>';
                $html .= '<td>' . htmlspecialchars($schedule['subject']) . '</td>';
                $html .= '<td>' . htmlspecialchars($schedule['room']) . '</td>';
                $html .= '<td>' . htmlspecialchars($schedule['instructor']) . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '</tbody></table>';
            
            echo json_encode(['success' => true, 'html' => $html]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No schedule found for this section']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Section ID is required']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?> 